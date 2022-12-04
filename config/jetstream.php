<?php

use Laravel\Jetstream\Features;

return [
    'stack' => 'inertia',
    'entity_group' => 'none',
    'middleware' => ['web'],
    'features' => [Features::accountDeletion()],
    'profile_photo_disk' => 'public',
];
