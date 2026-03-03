<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address',
        'medical_notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function toothHistories(): HasMany
    {
        return $this->hasMany(ToothHistory::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getLatestToothStatuses(): array
    {
        return $this->toothHistories()
            ->select('tooth_number', 'status', 'date_of_procedure')
            ->orderBy('date_of_procedure', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('tooth_number')
            ->pluck('status', 'tooth_number')
            ->toArray();
    }
}
