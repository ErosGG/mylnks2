<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "user_id",
        "url",
        "title",
        "status",
        "published_at",
    ];

    protected $nullable = [
        "published_at",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
