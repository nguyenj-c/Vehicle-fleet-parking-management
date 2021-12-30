<?php
final class Location
{
    private $vehicle = null;
    private int $lattitude;
    private int $longitude;
    
    
    public function __construct(int $lattitude, int $longitude)
    {
        $this->lattitude = $lattitude;
        $this->longitude = $longitude;
    }

    function registerPark(Vehicle $vehicle) : void
    {
        if ($this->vehicle == null) {
            $this->vehicle = $vehicle;
        }
    }

    function removePark()
    {
        $this->vehicle = null;
    }    

    public function isParked(Vehicle $vehicle) : string
    { 
        if ($this->vehicle == $vehicle) {
            return new DomainException('My vehicle is already parked here');
        }
        if (!$this->vehicle == null) {
            return new DomainException('Another vehicle is already parked here');
        }
    }

}