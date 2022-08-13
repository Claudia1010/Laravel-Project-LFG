<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public function channels()
    {
        return $this->belongsTo(Channel::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
