<?php


namespace App\Model;


interface ModifiedUserInterface
{
    public function getCreatedUser();

    public function getUpdatedUser();

    public function setCreatedUser(string $username);

    public function setUpdatedUser(string $username);
}