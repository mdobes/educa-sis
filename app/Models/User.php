<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements LdapAuthenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function getLdapDomainColumn()
    {
        return 'domain';
    }

    public function getDistinguishednNmeColumn()
    {
        return 'distinguishedname';
    }

    public function getLdapGuidColumn()
    {
        return 'guid';
    }

    public function getLdapDomain()
    {
        // TODO: Implement getLdapDomain() method.
    }

    public function setLdapDomain($domain)
    {
        // TODO: Implement setLdapDomain() method.
    }

    public function getLdapGuid()
    {
        // TODO: Implement getLdapGuid() method.
    }

    public function setLdapGuid($guid)
    {
        // TODO: Implement setLdapGuid() method.
    }
}
