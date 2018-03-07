<?php

use App\Entities\Permission\Permission;

class PermissionsTableSeeder extends AbstractSeeder
{
    protected $truncateTables = [
        CreatePermissionsTable::TABLENAME,
        CreatePermissionRoleTable::TABLENAME
    ];

    protected $itemList = [
        [
            'name' => 'Admin Panel',
            'key' => 'admin',
            'description' => <<<HTML
<p>Grants access to the admin dashboard.</p>
HTML
        ],
        [
            'name' => 'Countries',
            'key' => 'countries',
            'description' => <<<HTML
<p>Grants access to manage countries.</p>
HTML
        ],
        [
            'name' => 'Add Countries',
            'key' => 'countries.create',
            'description' => <<<HTML
<p>Permits ability to create new countries.</p>
HTML
        ],
        [
            'name' => 'Edit Countries',
            'key' => 'countries.update',
            'description' => <<<HTML
<p>Permits ability to update countries.</p>
HTML
        ],
        [
            'name' => 'Delete Countries',
            'key' => 'countries.destroy',
            'description' => <<<HTML
<p>Permits ability to delete countries.</p>
HTML
        ],
        [
            'name' => 'Currencies',
            'key' => 'currencies',
            'description' => <<<HTML
<p>Grants access to manage currencies.</p>
HTML
        ],
        [
            'name' => 'Add Currencies',
            'key' => 'currencies.create',
            'description' => <<<HTML
<p>Permits ability to create new currencies.</p>
HTML
        ],
        [
            'name' => 'Edit Currencies',
            'key' => 'currencies.update',
            'description' => <<<HTML
<p>Permits ability to update currencies.</p>
HTML
        ],
        [
            'name' => 'Delete Currencies',
            'key' => 'currencies.destroy',
            'description' => <<<HTML
<p>Permits ability to delete currencies.</p>
HTML
        ],
        [
            'name' => 'Languages',
            'key' => 'languages',
            'description' => <<<HTML
<p>Grants access to manage langauges.</p>
HTML
        ],
        [
            'name' => 'Add Languages',
            'key' => 'languages.create',
            'description' => <<<HTML
<p>Permits ability to create new languages.</p>
HTML
        ],
        [
            'name' => 'Edit Languages',
            'key' => 'languages.update',
            'description' => <<<HTML
<p>Permits ability to update languages.</p>
HTML
        ],
        [
            'name' => 'Delete Languages',
            'key' => 'languages.destroy',
            'description' => <<<HTML
<p>Permits ability to delete languages.</p>
HTML
        ],
        [
            'name' => 'Locales',
            'key' => 'locales',
            'description' => <<<HTML
<p>Grants access to manage locales.</p>
HTML
        ],
        [
            'name' => 'Add Locales',
            'key' => 'locales.create',
            'description' => <<<HTML
<p>Permits ability to create new locales.</p>
HTML
        ],
        [
            'name' => 'Edit Locales',
            'key' => 'locales.update',
            'description' => <<<HTML
<p>Permits ability to update locales.</p>
HTML
        ],
        [
            'name' => 'Delete Locales',
            'key' => 'locales.destroy',
            'description' => <<<HTML
<p>Permits ability to delete locales.</p>
HTML
        ],
        [
            'name' => 'Enable/Disable Locales',
            'key' => 'locales.toggle',
            'description' => <<<HTML
<p>Permits ability to enable/disable locales.</p>
HTML
        ],
        [
            'name' => 'Users',
            'key' => 'users',
            'description' => <<<HTML
<p>Grants access to manage users.</p>
HTML
        ],
        [
            'name' => 'Edit Users',
            'key' => 'users.edit',
            'description' => <<<HTML
<p>Permit ability to update users.</p>
HTML
        ],
        [
            'name' => 'Ban/Unban Users',
            'key' => 'users.toggle',
            'description' => <<<HTML
<p>Permit ability to ban/unban users.</p>
HTML
        ],
    ];

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function complete()
    {
        foreach ($this->model->all() as $permission) {
            $permission->roles()->attach(1);
        }
    }
}
