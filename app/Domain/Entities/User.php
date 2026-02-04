<?php

class User
{
    public function __construct(
        public ?int $id,
        public string $nombre,
        public string $email,
        public string $password,
        public string $rol,
        public ?string $placa = null
    ) {}
}
