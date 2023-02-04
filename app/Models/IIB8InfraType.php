<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IIB8InfraType extends Model
{
    use HasFactory;
    use HasFactory;
    protected $guarded = [];
    protected $table = 'iib8_infra';

    public function infraTypeDetail()
    {
        return $this->belongsTo(InfraType::class, 'infra_type_id');
    }
}
