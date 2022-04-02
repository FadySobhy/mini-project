<?php


namespace App\Services\Api\V1\Interfaces;


interface AuthTypeInterface
{
    public function findByEmail($email);

    public function customRules();

    public function create(Array $data);
}
