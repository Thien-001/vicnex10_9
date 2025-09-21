<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'Role_ID';

    public $timestamps = false;

    protected $fillable = [
        'Name',
        'Description',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'Role_ID');
    }
    // Định nghĩa hằng số role
    const ADMIN = 1;
    const STAFF = 2;
    const USER  = 3;
}
