<?php

namespace App\Actions\Passport;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\UpdatesOAuthClients;
use Laravel\Jetstream\Rules\Uri;
use Laravel\Passport\Client;

class UpdateClient implements UpdatesOAuthClients
{
    /**
     * Validate and create a new client.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, Client $client, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'redirect_uris' => ['required', 'list'],
            'redirect_uris.*' => ['required', 'string', new Uri],
        ])->validateWithBag('updateClient');

        $client->forceFill([
            'name' => $input['name'],
            'redirect_uris' => $input['redirect_uris'],
        ])->save();
    }
}
