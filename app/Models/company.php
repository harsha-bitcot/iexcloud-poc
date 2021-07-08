<?php

namespace App\Models;

use App\Http\Controllers\DailyDataController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $primaryKey = 'symbol';
    protected $keyType = 'string';
    public $incrementing = false;

    public function data()
    {
        return $this->hasMany(dailyData::class);
    }

}
