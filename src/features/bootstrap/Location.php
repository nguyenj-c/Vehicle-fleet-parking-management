<?php
final class Location
{
    private ?Vehicle $vehicle = null;

    public function __construct(private int $latitude, private int $longitude)
    {
    }

    function registerPark(Vehicle $vehicle): void
    {
        if (null === $this->vehicle) {
            $this->vehicle = $vehicle;
        }
    }

    function removePark(): void
    {
        $this->vehicle = null;
    }

    public function isParked(Vehicle $vehicle): string
    {
        if ($vehicle === $this->vehicle) {
            return new DomainException('My vehicle is already parked here');
        }
        if (null !== $this->vehicle) {
            return new DomainException('Another vehicle is already parked here');
        }
    }

}
