<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unsur extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'unsur';
    public $timestamps = false;

    public function subunsurs()
    {
        return $this->hasMany(SubUnsur::class, 'unsur_id', 'id');
    }
}
