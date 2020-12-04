<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEwallet extends Model
{
    use HasFactory;

    const TYPE_DEBIT = 'DB';
    const TYPE_CREDIT = 'CR';
}
