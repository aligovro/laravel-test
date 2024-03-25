<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'content', 'previous_versions'];

    protected $casts = [
        'previous_versions' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_documents', 'document_id', 'user_id');
    }
}
