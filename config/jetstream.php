<?php

use Laravel\Jetstream\Features;

return [
    'stack' => 'inertia',
    'entity_group' => 'teams',
    'middleware' => ['web'],
    'features' => [Features::accountDeletion()],
    'profile_photo_disk' => 'public',
];
