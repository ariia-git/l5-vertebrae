<?php

use App\Entities\Role\Role;

class RolesTableSeeder extends AbstractSeeder
{
    protected $truncateTables = [
        CreateRolesTable::TABLENAME
    ];

    protected $itemList = [
        [
            'name' => 'Administrator',
            'key' => 'admin',
            'description' => <<<HTML
<p>Manages everything.</p>
HTML
        ],
        [
            'name' => 'Manager',
            'key' => 'manager',
            'description' => <<<HTML
<p>Manages most aspects of the site.</p>
HTML
        ],
        [
            'name' => 'Moderator',
            'key' => 'moderator',
            'description' => <<<HTML
<p>Presides over user actions.</p>
HTML
        ],
        [
            'name' => 'Content Editor',
            'key' => 'content',
            'description' => <<<HTML
<p>Schedules and manages content.</p>
HTML
        ],
        [
            'name' => 'Assistant',
            'key' => 'assistant',
            'description' => <<<HTML
<p>Helps the company.</p>
HTML
        ],
        [
            'name' => 'Subscriber',
            'key' => 'subscriber',
            'description' => <<<HTML
<p>Donates regularly to the company.</p>
HTML
        ],
        [
            'name' => 'User',
            'key' => 'user',
            'description' => <<<HTML
<p>Average user.</p>
HTML
        ],
    ];

    public function __construct(Role $model)
    {
        $this->model = $model;
    }
}
