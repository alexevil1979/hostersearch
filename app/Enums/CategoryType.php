<?php

namespace App\Enums;

enum CategoryType: string
{
    case Hosting = 'hosting';
    case Domain = 'domain';
    case Builder = 'builder';
    case Ai = 'ai';
    case Other = 'other';
}
