<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dailyData extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function company()
    {
        return $this->belongsTo(company::class);
    }
    public function scopeChronological($query)
    {
        return $query->orderBy('date', 'asc');
    }
    public function scopePreviousWeek($query)
    {
        return $query->whereBetween('date', [Carbon::now()->subDays(7)->toDateString(), Carbon::now()->toDateString()]);
    }
    public function scopePreviousMonth($query)
    {
        return $query->whereBetween('date', [Carbon::now()->subMonth()->toDateString(), Carbon::now()->toDateString()]);
    }
    public function scopePrevious52Weeks($query)
    {
        return $query->whereBetween('date', [Carbon::now()->subWeeks(52)->toDateString(), Carbon::now()->toDateString()]);
    }
    public function scopeYtd($query)
    {
        return $query->whereBetween('date', [Carbon::now()->startOfYear()->toDateString(), Carbon::now()->toDateString()]);
    }
}
