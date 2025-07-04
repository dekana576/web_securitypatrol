<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckpointCriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'sales_office_id',
        'checkpoint_id',
        'nama_kriteria',
        'positive_answer',
        'negative_answer',
    ];

    // Relasi ke Region
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    // Relasi ke SalesOffice
    public function salesOffice()
    {
        return $this->belongsTo(SalesOffice::class);
    }

    // Relasi ke Checkpoint
    public function checkpoint()
    {
        return $this->belongsTo(Checkpoint::class);
    }
}
    