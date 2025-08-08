<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absen extends Model
{
    use HasFactory;

    protected $table = 'absen_tenaga_ahli';

    protected $fillable = [
        'tanggal',
        'user_id',
        'project_id',
        'kegiatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
    
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

