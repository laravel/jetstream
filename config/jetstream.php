<?php

use Laravel\Jetstream\Features;

return [
    'stack' => 'inertia',
    'middleware' => ['web'],
    'features' => [Features::accountDeletion()],
    'profile_photo_disk' => 'public',
];
