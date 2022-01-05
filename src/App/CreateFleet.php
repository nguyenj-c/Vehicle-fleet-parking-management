<?php 

namespace App\App;

class CreateFleet
{    
    function __construct(private string $idFleet)
    {
    }

    public function getIdFleet() : string
    {
        return $this->idFleet;
    }
} 