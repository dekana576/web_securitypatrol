<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPatrol extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'region_id',
        'sales_office_id',
        'checkpoint_id',
        'user_id',
        'description',
        'kriteria_result',
        'status',
        'image',
        'lokasi',
        'feedback_admin',
    ];

    protected $casts = [
        'kriteria_result' => 'array',
    ];

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function salesOffice() {
        return $this->belongsTo(SalesOffice::class);
    }

    public function checkpoint() {
        return $this->belongsTo(Checkpoint::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

}

