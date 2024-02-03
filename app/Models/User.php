<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'family',
        'national_code',
        'phone',
        'company_name',
        'national_company',
        'email',
        'password',
//        'password_confirmation',
        'type',
        'is_confirmed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function profilegenuine()
    {
        return $this->hasOne(ProfileGenuine::class);
    }

    public function profilelagal()
    {
        return $this->hasOne(ProfileLagal::class);
    }

    public function confirmation()
    {
        return $this->hasOne(Confirmation::class);
    }

    public function expert()
    {
        return $this->hasMany(ExpertAssignment::class, 'user2_id', 'id');
    }

    public function request()
    {
        return $this->hasMany(Requests::class);
    }


    public function ticket()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticket_agent()
    {
        return $this->hasMany(Ticket::class, 'user2_id', 'id');
    }

    public function message()
    {
        return $this->hasMany(Message::class);
    }

    public function image()
    {
        return $this->hasMany(Image::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

}
