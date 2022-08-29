<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IB21 extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'IB21';

    public function userDataDetail()
    {
        return $this->belongsTo(UserData::class, 'user_data_id');
    }
    public function locationDetail()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function butirKegiatanDetail()
    {
        return $this->belongsTo(ButirKegiatan::class, 'butir_kegiatan_id');
    }
    public function supervisorDetail()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id');
    }
    public function services()
    {
        return $this->hasMany(IB21Service::class, 'IB21_id', 'id');
    }
}
