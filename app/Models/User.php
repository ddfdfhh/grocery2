<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use \Illuminate\Support\Facades\Hash;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email', 'phone', 'state_id', 'city_id', 'pincode', 'address', 'image', 'status',
        'password', 'plain_password','uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token','token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // protected static function boot()
    // {
    //     parent::boot();
    //     User::creating(function ($model) {

    //             $model->password = Hash::make($model->password);

    //     });
    // }
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
    public function setPasswordAttribute($value)
    {

        $this->attributes['password'] = Hash::make($value);
        $this->attributes['plain_password'] = $value;

    }
    public function state()
    {
        return $this->belongsTo(\App\Models\State::class, 'state_id', 'id')->withDefault();
    }
    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'city_id', 'id')->withDefault();
    }

}
