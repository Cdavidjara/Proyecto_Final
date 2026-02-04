<?php

class Viaje
{
    public function __construct(
        public ?int $id,
        public int $cliente_id,
        public ?int $conductor_id,
        public string $origen,
        public string $destino,
        public float $tarifa,
        public string $estado
    ) {}
}
