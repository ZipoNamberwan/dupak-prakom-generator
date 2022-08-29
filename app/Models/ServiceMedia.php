<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceMedia extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'service_media';
    public $timestamps = false;
}
