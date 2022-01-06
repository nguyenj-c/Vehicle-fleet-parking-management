<?php

namespace App\Domain;

interface FleetRepository
{    
    public function find(string $ID) : ?Fleet;

    public function save(Fleet $fleet) : void;

} 