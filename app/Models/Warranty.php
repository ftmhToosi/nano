<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use HasFactory;

    public function license()
    {
        return $this->hasMany(License::class);
    }

    public function registration_doc()
    {
        return $this->hasMany(RegistrationDoc::class);
    }

    public function signatory()
    {
        return $this->hasMany(Signatory::class);
    }

    public function knowledge()
    {
        return $this->hasMany(Knowledge::class);
    }

    public function resume()
    {
        return $this->hasMany(Resume::class);
    }

    public function loans()
    {
        return $this->hasMany(Loans::class);
    }

    public function statement()
    {
        return $this->hasMany(Statement::class);
    }

    public function balance()
    {
        return $this->hasMany(Balance::class);
    }

    public function catalog()
    {
        return $this->hasMany(Catalog::class);
    }

    public function insurance()
    {
        return $this->hasMany(Insurance::class);
    }

    public function proforma()
    {
        return $this->hasMany(Proforma::class);
    }

    public function bills()
    {
        return $this->hasMany(Bills::class);
    }

    public function request()
    {
        return $this->belongsTo(Requests::class);
    }
}
