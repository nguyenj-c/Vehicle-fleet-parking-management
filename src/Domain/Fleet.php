<?php 

namespace App\Domain;

final class Fleet
{
    private array $vehicles = [];

    public function __construct(private string $id)
    {
        if(strlen($id) !== 6){
            throw new IncorrectValueConstructor('This ID don\'t respect the standards');
        }
    }

    public function register(Vehicle $vehicle) : void
    {
        $this->guardAgainstDuplicateVehicle($vehicle);

        $this->vehicles[] = $vehicle;
    }

    public function has(Vehicle $vehicle) : bool
    {
        return in_array($vehicle, $this->vehicles, true);
    }

    public function park(Vehicle $vehicle, Location $location) : Location
    {
        $this->guardAgainstUnknownVehicle($vehicle);

        return $vehicle->parkAt($location);
    }


    public function isParkedAt(Vehicle $vehicle, Location $location) : bool
    {
        $this->guardAgainstUnknownVehicle($vehicle);

        return $vehicle->isParkedAt($location);
    }

    private function guardAgainstUnknownVehicle(Vehicle $vehicle)
    {
        if (!$this->has($vehicle)) {
            throw UnknownVehicle::unknown();
        }     
    }

    private function guardAgainstDuplicateVehicle(Vehicle $vehicle)
    {
        if (true === $this->has($vehicle)) {
            throw DuplicateVehicle::duplicate();
        }    
    }

    public function ID() : string
    {
        return $this->id;    
    }

}
