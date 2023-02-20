<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
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

    protected $appends = ["displayPermission", "permission"];

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

    public function getPermissionAttribute()
    {
        $group = null;
        if (str_contains($this->distinguished_name, "OU=Students")){
            $group = "student";
        } else if($this->can("admin")){
            $group = "admin";
        } else if (str_contains($this->distinguished_name, "OU=Teachers")){
            $group = "teacher";
        }

        return $group;
    }

    public function getDisplayPermissionAttribute()
    {
        $text = "Žádná";
        if($this->permission == "student") $text = "Student";
        else if($this->permission == "teacher") $text = "Učitel";
        else if($this->permission == "admin") $text = "Administrátor";

        return $text;
    }

    public function getPasswordResetAttribute()
    {
        $adUser = \LdapRecord\Models\ActiveDirectory\User::findByGuid($this->guid);
        $pass = $adUser->getAttribute('pwdlastset');
        if (!$pass) return true;
        else return false;
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
