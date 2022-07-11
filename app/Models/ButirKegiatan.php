<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ButirKegiatan extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'butir_kegiatan';
    public $timestamps = false;

    public function subUnsurDetail()
    {
        return $this->belongsTo(SubUnsur::class, 'subunsur_id');
    }
}
