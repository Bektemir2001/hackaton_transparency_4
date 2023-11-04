<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseStatus extends Model
{
    use HasFactory;

    protected $table = 'purchase_statuses';
    protected $guarded = false;
}
