<?php 

namespace App\Domain;
use App\Domain\Exceptions\IncorrectValueConstructor;

final class Location
{
    private const PRECISION = 2;

    public function __construct(private float $latitude, private float $longitude)
    {
        $latitude = round($latitude, self::PRECISION);
        $longitude = round($longitude, self::PRECISION);

        if (-90 >= $latitude && 90 >= $latitude || -180 >= $latitude && 180 >= $longitude){
            throw IncorrectValueConstructor::location();
        }     
    }

    public function equalsTo(Location $location) : bool
    {
        return $this->latitude === $location->latitude
            && $this->longitude === $location->longitude;
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
