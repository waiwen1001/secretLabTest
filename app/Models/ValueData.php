<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValueData extends Model
{
  use HasFactory;
  
  protected $table = 'values_data';
  protected $fillable = ['key', 'value', 'created_at', 'updated_at'];

  protected $casts = [
    'created_at' => 'timestamp',
    'updated_at' => 'timestamp',
    'value' => 'array',
  ];
}
