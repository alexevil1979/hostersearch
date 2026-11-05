<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class WhoIsHostingController extends Controller
{
    public function index(): View
    {
        return view('public.who-is-hosting', [
            'domain' => null,
            'result' => null,
            'matched' => null,
        ]);
    }

    public function lookup(Request $request): View
    {
        $data = $request->validate([
            'domain' => ['required', 'string', 'max:255'],
        ]);

        $domain = strtolower(trim($data['domain']));
        $domain = preg_replace('#^https?://#', '', $domain) ?? $domain;
        $domain = explode('/', $domain)[0];
        $domain = explode(':', $domain)[0];
        $domain = preg_replace('/^www\./', '', $domain) ?? $domain;

        abort_unless((bool) preg_match('/^[a-z0-9.-]+\.[a-z]{2,}$/i', $domain), 422, 'Invalid domain');

        $result = Cache::remember("whois.host.{$domain}", 3600, function () use ($domain) {
            $aRecords = @dns_get_record($domain, DNS_A) ?: [];
            $aaaa = @dns_get_record($domain, DNS_AAAA) ?: [];
            $ns = @dns_get_record($domain, DNS_NS) ?: [];
            $mx = @dns_get_record($domain, DNS_MX) ?: [];
            $cname = @dns_get_record($domain, DNS_CNAME) ?: [];

            $ips = collect($aRecords)->pluck('ip')->filter()->unique()->values()->all();
            $ptr = [];
            foreach (array_slice($ips, 0, 3) as $ip) {
                $host = @gethostbyaddr($ip);
                if ($host && $host !== $ip) {
                    $ptr[$ip] = $host;
                }
            }

            return [
                'domain' => $domain,
                'ips' => $ips,
                'ipv6' => collect($aaaa)->pluck('ipv6')->filter()->values()->all(),
                'ns' => collect($ns)->pluck('target')->filter()->values()->all(),
                'mx' => collect($mx)->sortBy('pri')->map(fn ($r) => ($r['target'] ?? '').' (prio '.($r['pri'] ?? '?').')')->values()->all(),
                'cname' => collect($cname)->pluck('target')->filter()->values()->all(),
                'ptr' => $ptr,
                'guess' => $this->guessProvider($ips, $ptr, $ns),
            ];
        });

        $matched = null;
        if (! empty($result['guess']['slug'])) {
            $matched = Company::query()->approved()->where('slug', $result['guess']['slug'])->first();
        }

        return view('public.who-is-hosting', [
            'domain' => $domain,
            'result' => $result,
            'matched' => $matched,
        ]);
    }

    private function guessProvider(array $ips, array $ptr, array $ns): array
    {
        $haystack = strtolower(implode(' ', array_merge($ptr, $ns)));

        $map = [
            'cloudflare' => ['name' => 'Cloudflare (CDN/proxy)', 'slug' => null],
            'digitalocean' => ['name' => 'DigitalOcean', 'slug' => 'digitalocean'],
            'amazon' => ['name' => 'Amazon AWS', 'slug' => null],
            'aws' => ['name' => 'Amazon AWS', 'slug' => null],
            'google' => ['name' => 'Google Cloud', 'slug' => null],
            'hetzner' => ['name' => 'Hetzner', 'slug' => 'hetzner'],
            'ovh' => ['name' => 'OVH', 'slug' => null],
            'linode' => ['name' => 'Akamai Linode', 'slug' => null],
            'vultr' => ['name' => 'Vultr', 'slug' => 'vultr'],
            'hostinger' => ['name' => 'Hostinger', 'slug' => 'hostinger'],
            'bluehost' => ['name' => 'Bluehost', 'slug' => 'bluehost'],
            'siteground' => ['name' => 'SiteGround', 'slug' => 'siteground'],
            'godaddy' => ['name' => 'GoDaddy', 'slug' => 'godaddy'],
            'namecheap' => ['name' => 'Namecheap', 'slug' => 'namecheap'],
            'ionos' => ['name' => 'IONOS', 'slug' => 'ionos'],
        ];

        foreach ($map as $needle => $info) {
            if (str_contains($haystack, $needle)) {
                return $info + ['confidence' => 'medium'];
            }
        }

        return [
            'name' => $ips ? 'Unknown / dedicated IP' : 'No A records found',
            'slug' => null,
            'confidence' => 'low',
        ];
    }
}
