<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IIB8 extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'IIB8';

    public function userDataDetail()
    {
        return $this->belongsTo(UserData::class, 'user_data_id');
    }
    public function locationDetail()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function roomDetail()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function butirKegiatanDetail()
    {
        return $this->belongsTo(ButirKegiatan::class, 'butir_kegiatan_id');
    }
    public function infras()
    {
        return $this->hasMany(IIB8InfraType::class, 'IIB8_id', 'id');
    }
    public function supervisorDetail()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id');
    }
}
