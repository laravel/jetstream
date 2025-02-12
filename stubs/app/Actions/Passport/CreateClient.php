<?php

namespace App\Actions\Passport;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesOAuthClients;
use Laravel\Jetstream\Rules\Uri;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

class CreateClient implements CreatesOAuthClients
{
    /**
     * Create a new action instance.
     */
    public function __construct(protected ClientRepository $clients)
    {
    }

    /**
     * Validate and create a new client.
     *
     * @param  array<string, mixed>  $input
     */
    public function create(User $user, array $input): Client
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'redirect_uris' => ['required', 'list'],
            'redirect_uris.*' => ['required', 'string', new Uri],
            'confidential' => 'boolean',
        ])->validateWithBag('createClient');

        return $this->clients->createAuthorizationCodeGrantClient(
            $input['name'],
            $input['redirect_uris'],
            (bool) ($input['confidential'] ?? false),
            $user
        );
    }
}
