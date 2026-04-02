<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class imagegeneration extends Model
{
    protected $fillable = ['user_id', 'generated_prompt', 'image_path', 'file_size', 'orginal_name', 'mime_type'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
