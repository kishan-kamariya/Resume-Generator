<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';

    protected $fillable = [
        'resume_id', 'institution', 'degree', 'field_of_study',
        'start_date', 'end_date', 'currently_studying', 'gpa', 'description', 'order',
    ];

    protected $casts = ['currently_studying' => 'boolean'];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
