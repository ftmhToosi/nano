<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FProforma extends Model
{
    use HasFactory;

    public function facilities()
    {
        return $this->belongsTo(Facilities::class);
    }
}
