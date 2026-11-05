<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClickLog;
use App\Models\Company;
use App\Models\Review;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function companies(): StreamedResponse
    {
        return $this->csv('companies.csv', ['id', 'slug', 'name', 'status', 'overall_score', 'review_count', 'price_from', 'website'], function ($out) {
            Company::query()->orderBy('id')->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->id, $row->slug, $row->name, $row->status->value,
                        $row->overall_score, $row->review_count, $row->price_from, $row->website,
                    ]);
                }
            });
        });
    }

    public function reviews(): StreamedResponse
    {
        return $this->csv('reviews.csv', ['id', 'company_id', 'type', 'status', 'rating_overall', 'title', 'created_at'], function ($out) {
            Review::query()->orderBy('id')->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->id, $row->company_id, $row->type->value, $row->status->value,
                        $row->rating_overall, $row->title, $row->created_at,
                    ]);
                }
            });
        });
    }

    public function clicks(): StreamedResponse
    {
        return $this->csv('clicks.csv', ['id', 'company_id', 'source', 'ip_address', 'destination_url', 'created_at'], function ($out) {
            ClickLog::query()->orderByDesc('id')->limit(5000)->get()->each(function ($row) use ($out) {
                fputcsv($out, [
                    $row->id, $row->company_id, $row->source, $row->ip_address,
                    $row->destination_url, $row->created_at,
                ]);
            });
        });
    }

    private function csv(string $filename, array $headers, callable $writer): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $writer) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            $writer($out);
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
