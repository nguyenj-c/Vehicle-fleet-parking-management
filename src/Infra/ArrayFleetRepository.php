<?php

namespace App\Infra;
use App\Domain\Fleet;
use App\Domain\FleetRepository;

class ArrayFleetRepository implements FleetRepository
{    
    private $fleets = [];

    public function find(string $ID) : ?Fleet
    {
        return $this->fleets[$ID] ?? null;
    }

    public function save(Fleet $fleet) : void
    {
        $this->fleets[$fleet->ID()] = $fleet;
    }

    public function has(string $ID) : bool
    {
        return array_key_exists($ID, $this->fleets);
    }
} 