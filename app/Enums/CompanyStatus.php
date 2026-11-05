<?php

namespace App\Enums;

enum CompanyStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Claimed = 'claimed';
    case Rejected = 'rejected';
    case Suspended = 'suspended';
}
