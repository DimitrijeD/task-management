<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const MIN_PRIORITY = 1;

    protected $fillable = [
        'name', 'description', 'priority',
        'project_id' // remove from fillable to prevent task migration from one proj to another
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
