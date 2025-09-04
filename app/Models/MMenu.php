<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MMenu extends Model
{
    use HasFactory;

    protected $table = 'm_menu';
    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'menu_name',
        'route_name',
        'icon',
        'parent_id',
        'sort_order',
        'isactive',
    ];

    public $timestamps = false; // kalau tabelmu tidak pakai created_at / updated_at

    // Relasi ke child menu
    public function children()
    {
        return $this->hasMany(MMenu::class, 'parent_id', 'menu_id')
            ->orderBy('sort_order');
    }

    // Relasi ke parent menu
    public function parent()
    {
        return $this->belongsTo(MMenu::class, 'parent_id', 'menu_id');
    }

    // Relasi ke role-menu
    public function roleMenus()
    {
        return $this->hasMany(MRoleMenu::class, 'menu_id', 'menu_id');
    }
}
