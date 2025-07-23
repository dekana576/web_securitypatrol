<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nik',
        'phone_number',
        'gender',
        'email',
        'password',
        'sales_office_id',
        'region_id',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function salesOffice()
    {
        return $this->belongsTo(SalesOffice::class, 'sales_office_id');
    }
    public function security_schedule()
    {
        return $this->hasMany(SecuritySchedule::class);
    }
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
    public function data_patrols()
    {
        return $this->hasMany(DataPatrol::class);
    }

}
