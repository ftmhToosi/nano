<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertAssignment extends Model
{
    use HasFactory;

    public function request()
    {
        return $this->belongsTo(Requests::class, 'requests_id');
    }

    public function expert()
    {
        return $this->belongsTo(User::class, 'user2_id', 'id');
    }
}
