<?php
final class Vehicle
{
    private ?Location $location = null;

    public function __construct(private string $plateNumber)
    {
    }

    public function isParkedAt(Location $location): bool
    {
        return $this->location == $location;
    }

    public function parkAt(Location $location): void
    {
        $this->location = $location;
    }
}
