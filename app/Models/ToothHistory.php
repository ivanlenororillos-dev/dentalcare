<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToothHistory extends Model
{
    use HasFactory;

    protected $table = 'tooth_history';

    protected $fillable = [
        'client_id',
        'tooth_number',
        'procedure_type',
        'status',
        'surface',
        'detailed_notes',
        'dentist_id',
        'date_of_procedure',
    ];

    protected $casts = [
        'date_of_procedure' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class);
    }

    public const PROCEDURE_TYPES = [
        'examination' => 'Examination',
        'cleaning' => 'Cleaning',
        'filling' => 'Filling',
        'crown' => 'Crown',
        'extraction' => 'Extraction',
        'root_canal' => 'Root Canal',
        'implant' => 'Implant',
        'veneer' => 'Veneer',
        'whitening' => 'Whitening',
        'sealant' => 'Sealant',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'healthy' => ['label' => 'Healthy', 'color' => '#4CAF50', 'symbol' => ''],
        'cavity' => ['label' => 'Cavity', 'color' => '#FFC107', 'symbol' => 'C'],
        'filled' => ['label' => 'Filled', 'color' => '#2196F3', 'symbol' => 'F'],
        'crowned' => ['label' => 'Crowned', 'color' => '#9E9E9E', 'symbol' => 'Cr'],
        'extracted' => ['label' => 'Extracted', 'color' => '#F44336', 'symbol' => 'X'],
        'root_canal' => ['label' => 'Root Canal', 'color' => '#FF9800', 'symbol' => 'RC'],
        'implant' => ['label' => 'Implant', 'color' => '#9C27B0', 'symbol' => 'Im'],
    ];

    public const SURFACES = [
        'mesial' => 'Mesial',
        'distal' => 'Distal',
        'occlusal' => 'Occlusal',
        'buccal' => 'Buccal',
        'lingual' => 'Lingual',
    ];
}
