<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubUnsur extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'subunsur';
    public $timestamps = false;

    public function unsurDetail()
    {
        return $this->belongsTo(Unsur::class, 'unsur_id');
    }

    public function butirkegiatans()
    {
        return $this->hasMany(ButirKegiatan::class, 'subunsur_id', 'id');
    }
}
