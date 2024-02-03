<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expert_assignment()
    {
        return $this->hasOne(ExpertAssignment::class);
    }

    public function checkdoc()
    {
        return $this->hasMany(CheckDoc::class, 'request_id');
    }

    public function assessment()
    {
        return $this->hasMany(Assessment::class, 'request_id');
    }

    public function report()
    {
        return $this->hasMany(Report::class, 'request_id');
    }

    public function committee()
    {
        return $this->hasMany(Committee::class, 'request_id');
    }

    public function credit()
    {
        return $this->hasMany(Credit::class, 'request_id');
    }

    public function facilities()
    {
        return $this->hasMany(Facilities::class, 'request_id');
    }

    public function warranty()
    {
        return $this->hasMany(Warranty::class, 'request_id');
    }

    public function request_delete()
    {
        return $this->hasMany(RequestDelete::class, 'request_id');
    }

}
