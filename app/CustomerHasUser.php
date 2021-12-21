<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerHasUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'user_id'
    ];

    /**
     * Customers Has User belongs to user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Entities\Users\User');
    }
}
