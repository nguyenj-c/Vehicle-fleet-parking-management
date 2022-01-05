<?php 

namespace App\Domain;

final class Location
{
    public function __construct(private float $latitude, private float $longitude)
    {
        $correctLatitude = range(-90,90);
        $correctLongitude = range(-180,180);
        // if (-90 > $latitude || 90 > $lattitude )
        if(!in_array(ceil($latitude), $correctLatitude) || !in_array(ceil($longitude), $correctLongitude)){
            throw new IncorrectValueConstructor('The value for latitude and longitude don\'t respect the standards');
        }     
    }

    public function equalsTo(Location $location) : bool
    {
        if(round($this->latitude, 2) != round($location->latitude, 2) || round($this->longitude, 2) != round($location->longitude, 2)){
            return false;
        }
        return true;
    }

    public function latitude() : string
    {
        return $this->latitude;    
    }

    public function longitude() : string
    {
        return $this->longitude;    
    }

}
