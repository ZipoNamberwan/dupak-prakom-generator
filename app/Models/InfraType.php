<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfraType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'infra_type';
    public $timestamps = false;
}
