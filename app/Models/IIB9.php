<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IIB9 extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'IIB9';

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
    public function infraTypeDetail()
    {
        return $this->belongsTo(InfraType::class, 'infra_type_id');
    }
    public function supervisorDetail()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id');
    }
}
