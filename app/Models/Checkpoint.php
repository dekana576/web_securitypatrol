<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    protected $fillable = ['region_id', 'sales_office_id', 'checkpoint_name', 'checkpoint_code'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function salesOffice()
    {
        return $this->belongsTo(SalesOffice::class);
    }

    public function criterias()
    {
        return $this->hasMany(CheckpointCriteria::class);
    }
    public function data_patrols()
    {
        return $this->hasMany(DataPatrol::class);
    }

}

