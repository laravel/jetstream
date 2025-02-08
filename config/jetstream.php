<?php

use Laravel\Jetstream\Features;

return [
    'stack' => 'inertia',
    'middleware' => ['web'],
    'features' => [Features::accountDeletion()],
    'page_prefix' => '',
    'profile_photo_disk' => 'public',
];
