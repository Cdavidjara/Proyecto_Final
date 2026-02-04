<?php

interface UserRepository
{
    public function save(User $user): void;
    public function findByEmail(string $email): ?User;
    public function findAll(): array;

}
