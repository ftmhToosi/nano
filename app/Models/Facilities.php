<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facilities extends Model
{
    use HasFactory;

    public function request()
    {
        return $this->belongsTo(Requests::class);
    }

    public function introduction()
    {
        return $this->hasOne(Introduction::class);
    }

    public function shareholder()
    {
        return $this->hasMany(Shareholder::class);
    }

    public function part2()
    {
        return $this->hasOne(Part2::class);
    }

    public function board()
    {
        return $this->hasMany(Board::class);
    }

    public function residence()
    {
        return $this->hasMany(Residence::class);
    }

    public function manpower()
    {
        return$this->hasMany(Manpower::class);
    }

    public function educational()
    {
        return $this->hasMany(Educational::class);
    }

    public function place()
    {
        return $this->hasMany(Place::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function bank()
    {
        return $this->hasMany(Bank::class);
    }

    public function active_f()
    {
        return $this->hasMany(ActiveF::class);
    }

    public function active_w()
    {
        return $this->hasMany(ActiveW::class);
    }

    public function benefit()
    {
        return $this->hasMany(Benefit::class);
    }

    public function asset()
    {
        return $this->hasMany(Asset::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approvals::class);
    }

    public function contract()
    {
        return $this->hasMany(Contract::class);
    }

    public function pledge()
    {
        return $this->hasMany(Pledge::class);
    }

    public function estate()
    {
        return $this->hasMany(Estate::class);
    }

    public function finish()
    {
        return $this->hasMany(Finish::class);
    }

    public function part7()
    {
        return $this->hasOne(Part7::class);
    }

    public function f_license()
    {
        return $this->hasMany(FLicense::class);
    }

    public function f_registration()
    {
        return $this->hasMany(FRegistration::class);
    }

    public function f_signatory()
    {
        return $this->hasMany(FSignatory::class);
    }

    public function f_knowledge()
    {
        return $this->hasMany(FKnowledge::class);
    }

    public function f_resume()
    {
        return $this->hasMany(FResume::class);
    }

    public function f_loans()
    {
        return $this->hasMany(FLoans::class);
    }

    public function f_statement()
    {
        return $this->hasMany(FStatement::class);
    }

    public function f_balance()
    {
        return $this->hasMany(FBalance::class);
    }

    public function f_catalog()
    {
        return $this->hasMany(FCatalog::class);
    }

    public function f_insurance()
    {
        return $this->hasMany(FInsurance::class);
    }

    public function f_proforma()
    {
        return $this->hasMany(FProforma::class);
    }

    public function f_bills()
    {
        return $this->hasMany(FBills::class);
    }
}
