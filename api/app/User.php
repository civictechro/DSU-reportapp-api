<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements
    Authenticatable,
    CanResetPasswordContract
{
    use AuthenticableTrait, SoftDeletes, CanResetPassword;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
  protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
  protected $fillable = [
        'nume', 'prenume', 'email', 'telefon_s', 'telefon_p', 'adresa', 'cod_postal', 'localitate', 'judet', 'parola', 'role_id', 'unitate_id', 'remember_token', 'active',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
  protected $hidden = [
        'parola', 'remember_token', 'api_key', 'api_key_expire'
    ];

    /**
     * Get the unit that user belongs to.
     */
  public function unitate()
  {
      return $this->belongsTo(Unitate::class);
  }

    /**
     * Get the role that user belongs to.
     */
  public function rol()
  {
      return $this->belongsTo(Role::class, 'role_id');
  }

    /**
     * Check if user has a permission, based on role.
     *
     * @param string $permission - Slug of permission
     * @return boolean
     */
  public function hasPermission($permission){
    if (!empty(Permission::where('key', $permission)->first()->id)) {
      if (!empty(DB::table('permission_role')->where(['role_id' => $this->role_id, 'permission_id' => Permission::where('key', $permission)->first()->id])->first())) {
        return true;
      }
    }
      return false;
  }
}
