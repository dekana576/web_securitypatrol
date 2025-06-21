<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecuritySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id', 'sales_office_id', 'shift', 'jam_mulai', 'jam_berakhir',
        'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'
    ];

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function salesOffice() {
        return $this->belongsTo(SalesOffice::class);
    }

    public function seninUser()  { return $this->belongsTo(User::class, 'senin'); }
    public function selasaUser() { return $this->belongsTo(User::class, 'selasa'); }
    public function rabuUser()   { return $this->belongsTo(User::class, 'rabu'); }
    public function kamisUser()  { return $this->belongsTo(User::class, 'kamis'); }
    public function jumatUser()  { return $this->belongsTo(User::class, 'jumat'); }
    public function sabtuUser()  { return $this->belongsTo(User::class, 'sabtu'); }
    public function mingguUser() { return $this->belongsTo(User::class, 'minggu'); }

}