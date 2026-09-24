<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume_id', 'name', 'role', 'url', 'start_date', 'end_date',
        'description', 'technologies', 'order',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
