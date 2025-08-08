<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Worklog extends Model
{
    protected $table = 'worklog';
    
    protected $fillable = [
        'tanggal',
        'project_id',
        'sebelum',
        'sesudah',
        'keterangan',
        'file',
    ];
    
    protected $casts = [
        'tanggal' => 'date',
    ];
    
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
