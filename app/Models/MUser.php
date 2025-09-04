<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MUser extends Authenticatable
{
  use HasApiTokens, Notifiable;

  protected $table = 'm_user'; // Ganti nama tabel

  protected $primaryKey = 'userid'; // Primary key kamu adalah UUID, bukan `id`

  public $incrementing = false; // Karena UUID bukan auto increment
  protected $keyType = 'string'; // UUID adalah string

  protected $fillable = [
    'userid',
    'username',
    'password',
    'roles',
    'isactive',
  ];

  protected $hidden = [
    'password',
  ];

  public $timestamps = false; // Jika tidak ada kolom created_at dan updated_at

  // app/Models/User.php
  public function getDisplayNameAttribute()
  {
    return $this->name ?? $this->username ?? $this->email ?? 'User';
  }
  public function roleMenus()
  {
    return $this->hasMany(\App\Models\MRoleMenu::class, 'role_code', 'roles');
  }
}
