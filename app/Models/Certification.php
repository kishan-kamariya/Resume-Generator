<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume_id', 'name', 'issuer', 'issue_date', 'expiry_date',
        'credential_id', 'credential_url', 'order',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
