<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'contact_number',
        'contact_number2',
        'contact_number1',
        'address_line1',
        'city',
        'state',
        'pin_code',
        'country',
        'transport_id',
        'aadhaar_number',
        'blood_group',
        'mother_tongue',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'father_name',
        'mother_name',
        'remarks',
        'termination_date',
        'status',
        'email',
        'email_alternate',
        'password',
        'created_by',
        'updated_by',
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
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userInformation() : BelongsTo
    {
        return $this->belongsTo(UserInformation::class);
    }
}
