<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IB21Service extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'IB21_service';

    public function activity()
    {
        return $this->belongsTo(IB21::class, 'IB21_id');
    }
    public function serviceTypeDetail()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }
    public function serviceMediaDetail()
    {
        return $this->belongsTo(ServiceMedia::class, 'service_media_id');
    }
}
