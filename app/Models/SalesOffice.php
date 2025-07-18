<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'sales_office_name',
        'sales_office_address'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    
    public function checkpoint()
    {
        return $this->hasMany(Checkpoint::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function security_schedule()
    {
        return $this->hasMany(SecuritySchedule::class);
    }
    public function data_patrols()
    {
        return $this->hasMany(DataPatrol::class);
    }
}
