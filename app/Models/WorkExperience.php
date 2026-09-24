<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume_id', 'company', 'position', 'location',
        'start_date', 'end_date', 'currently_working', 'description', 'order',
    ];

    protected $casts = ['currently_working' => 'boolean'];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
