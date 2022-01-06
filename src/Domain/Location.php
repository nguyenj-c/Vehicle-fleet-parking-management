<?php 

namespace App\Domain;

final class Location
{
    private const PRECISION = 2;

    public function __construct(private float $latitude, private float $longitude)
    {
        $correctLatitude = range(-90,90);
        $correctLongitude = range(-180,180);
        // if (-90 > $latitude || 90 > $lattitude )
        if(!in_array(ceil($latitude), $correctLatitude) || !in_array(ceil($longitude), $correctLongitude)){
            throw IncorrectValueConstructor::location();
        }     
    }

    public function equalsTo(Location $location) : bool
    {
        return round(
            round($this->latitude, self::PRECISION) === round($location->latitude, self::PRECISION) 
            && round($this->longitude, self::PRECISION) === round($location->longitude, self::PRECISION)
        );

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
