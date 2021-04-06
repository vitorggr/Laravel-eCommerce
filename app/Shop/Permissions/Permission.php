<?php

namespace App\Shop\Permissions;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    protected $table = 'tblpermissaoacessomenu';

    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];
}
