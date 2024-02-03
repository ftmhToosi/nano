<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part2 extends Model
{
    use HasFactory;

    public function facilities()
    {
        return $this->belongsTo(Facilities::class);
    }
}
