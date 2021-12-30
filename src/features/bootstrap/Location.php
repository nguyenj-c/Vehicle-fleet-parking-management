<?php
final class Location
{
    private $vehicle = null;
    private int $lattitude;
    private int $longitude;
    
    
    public function __construct($lattitude, $longitude)
    {
        $this->lattitude = $lattitude;
        $this->longitude = $longitude;
    }

    function registerPark()
    {

    }

    function removePark()
    {

    } 

    public function isParked(Vehicle $vehicle) : bool
    { 
        if(in_array($vehicle,$this->vehicles) == true){
            return true;
        }
        return false;
    }

}