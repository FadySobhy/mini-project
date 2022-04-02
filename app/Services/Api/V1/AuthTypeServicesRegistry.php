<?php


namespace App\Services\Api\V1;

use App\Services\Api\V1\Interfaces\AuthTypeInterface;

class AuthTypeServicesRegistry
{
    protected $authTypes = [];

    function register ($type, AuthTypeInterface $instance) {
        $this->authTypes[$type] = $instance;
        return $this;
    }

    function get($type) {
        if (array_key_exists($type, $this->authTypes)) {
            return $this->authTypes[$type];
        } else {
            throw new \Exception("Invalid Auth Type");
        }
    }
}
