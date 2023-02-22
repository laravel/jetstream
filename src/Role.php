<?php

namespace Laravel\Jetstream;

use JsonSerializable;

class Role implements JsonSerializable
{
    /**
     * The key identifier for the role.
     */
    public string $key;

    /**
     * The name of the role.
     */
    public string $name;

    /**
     * The role's permissions.
     */
    public array $permissions;

    /**
     * The role's description.
     */
    public string $description;

    /**
     * Create a new role instance.
     *
     * @return void
     */
    public function __construct(string $key, string $name, array $permissions)
    {
        $this->key = $key;
        $this->name = $name;
        $this->permissions = $permissions;
    }

    /**
     * Describe the role.
     */
    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the JSON serializable representation of the object.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key,
            'name' => __($this->name),
            'description' => __($this->description),
            'permissions' => $this->permissions,
        ];
    }
}
