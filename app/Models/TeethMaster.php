<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeethMaster extends Model
{
    public $timestamps = false;

    protected $table = 'teeth_master';

    protected $fillable = [
        'tooth_number',
        'quadrant',
        'standard_name',
        'alternate_name',
        'tooth_type',
    ];

    public function getDisplayNameAttribute(): string
    {
        $name = "#{$this->tooth_number} - {$this->standard_name}";
        if ($this->alternate_name) {
            $name .= " ({$this->alternate_name})";
        }
        return $name;
    }
}
