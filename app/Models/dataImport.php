<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dataImport extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function company()
    {
        return $this->belongsTo(company::class);
    }
//    public function data()
//    {
//        return $this->hasManyThrough(dailyData::class, company::class);
//    }
}
