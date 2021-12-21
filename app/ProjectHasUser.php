<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectHasUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Entities\Users\User');
    }
}
