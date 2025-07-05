<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function salesOffices()
    {
        return $this->hasMany(SalesOffice::class);
    }
    public function checkpoint()
    {
        return $this->hasMany(Checkpoint::class);
    }
    public function security_schedule()
    {
        return $this->hasMany(SecuritySchedule::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}



