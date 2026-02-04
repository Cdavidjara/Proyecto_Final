<?php

interface ViajeRepository
{
    public function crear(Viaje $viaje): void;
    public function listar(): array;

}
