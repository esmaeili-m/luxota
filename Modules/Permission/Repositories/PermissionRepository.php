<?php

namespace Modules\Permission\Repositories;

use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    public function get_permissions()
    {
        return Permission::get();
    }
}
