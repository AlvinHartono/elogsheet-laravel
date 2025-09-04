<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MRoleMenu extends Model
{
  use HasFactory;

  protected $table = 'm_role_menu';
  protected $primaryKey = 'id';

  protected $fillable = [
    'role_code',
    'menu_id',
  ];

  public $timestamps = false;

  // Relasi ke menu
  public function menu()
  {
    return $this->belongsTo(MMenu::class, 'menu_id', 'menu_id');
  }
}
