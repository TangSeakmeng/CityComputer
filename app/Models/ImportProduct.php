<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportProduct extends Model
{
    use HasFactory;

    protected $table = 'ImportProducts';
    protected $fillable = ['id', 'import_date', 'invoice_number', 'import_total', 'supplier_id', 'user_id', 'createdAt', 'updatedAt'];
}
