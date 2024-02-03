<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }
}
