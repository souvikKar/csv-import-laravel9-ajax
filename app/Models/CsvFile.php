<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'email',
    ];

    protected $table = 'csv_files';

    protected $dates = [
        'created_at', 'updated_at'
    ];
}
