<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use App\Models\Motif;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasApiTokens, HasFactory, Notifiable, MustVerifyEmail;
    
    protected $table = 'userss';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'number',
        'numero',
        'pictures',
        'verified',
        'verification_token',
        'email_verified_at'  // Added this
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'verification_token', // Hide token from serialization
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'verified' => 'boolean',  // Cast verified as boolean
    ];

    /**
     * Check if user is verified
     */
    public function isVerified()
    {
        return $this->verified && !is_null($this->email_verified_at);
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified()
    {
        $this->verified = true;
        $this->email_verified_at = $this->freshTimestamp();
        $this->verification_token = null;
        $this->save();
    }

    /**
     * Send email verification notification
     */
    public function sendEmailVerificationNotification()
    {
        // Custom logic if needed, otherwise Laravel will use default
        // You can override this to use your custom mailing logic
    }

    public function motifs(){
        return $this->hasMany(Motif::class);
    }
}