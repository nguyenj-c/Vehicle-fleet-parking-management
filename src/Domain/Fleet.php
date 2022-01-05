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
        $this->guardAgainstDuplicateVehicle($vehicle->plateNumber());

        $this->vehicles[$vehicle->plateNumber()] = $vehicle;
    }


    public function park(Vehicle $vehicle, Location $location): void
    {
        $this->guardAgainstUnknownVehicle($vehicle->plateNumber());

        $this->verifyLocation($vehicle->plateNumber(),$location);
        $this->vehicles[$vehicle->plateNumber()] = $vehicle->parkAt($location);
    }

    public function isParkedAt(string $plateNumber, Location $location) : bool
    {
        $vehicle = $this->find($plateNumber);
        $this->guardAgainstUnknownVehicle($plateNumber);

        return $this->verifyLocation($plateNumber,$location);
    }

    public function has(string $plateNumber) : bool
    {
        return array_key_exists($plateNumber, $this->vehicles);
    }

    private function find(string $plateNumber) : Vehicle
    {
        return $this->vehicles[$plateNumber];
    }

    private function verifyLocation(string $plateNumber, Location $location) : bool
    {
        $vehicle = $this->find($plateNumber);
        $bool = $vehicle->verify($location);

        if (!$bool) {
            throw InvalidPark::duplicate();
        } 

        return $bool;
    }

    private function guardAgainstUnknownVehicle(string $plateNumber)
    {
        if (!$this->has($plateNumber)) {
            throw UnknownVehicle::unknown();
        }     
    }

    private function guardAgainstDuplicateVehicle(string $plateNumber)
    {
        if (true === $this->has($plateNumber)) {
            throw DuplicateVehicle::duplicate();
        }    
    }

    public function ID() : string
    {
        return $this->id;    
    }

}
