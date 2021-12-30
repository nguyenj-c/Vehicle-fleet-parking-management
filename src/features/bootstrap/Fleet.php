<?php

final class Fleet
{
    private array $vehicles = [];

    public function __construct(private int $id)
    {
    }

    public function register(Vehicle $vehicle): void
    {
        if (true === $this->has($vehicle)) {
            throw new DomainException('This vehicle has already been registered into your fleet');
        }

        $this->vehicles[] = $vehicle;
    }

    public function has(Vehicle $vehicle): bool
    {
        return in_array($vehicle, $this->vehicles, true);
    }

}
