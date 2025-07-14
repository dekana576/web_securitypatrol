<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecuritySchedule extends Model
{
    use HasFactory;

    protected $table = 'patrol_schedules';

    protected $fillable = [
        'region_id',
        'sales_office_id',
        'tanggal',
        'shift',
        'jam_mulai',
        'jam_selesai',
        'security_1_id',
        'security_2_id',
    ];

    protected $dates = ['tanggal'];

    // Relasi ke region
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    // Relasi ke sales office
    public function salesOffice()
    {
        return $this->belongsTo(SalesOffice::class);
    }

    // Relasi ke user (security 1)
    public function securityOne()
    {
        return $this->belongsTo(User::class, 'security_1_id');
    }

    // Relasi ke user (security 2)
    public function securityTwo()
    {
        return $this->belongsTo(User::class, 'security_2_id');
    }
}
