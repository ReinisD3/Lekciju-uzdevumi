<?php

class Car
{
    private string $name;
    private string $madeIn;
    private int $year;
    private string $brand;

    public function __construct(string $name, string $madeIn, int $year, string $brand)
    {
        $this->name = $name;
        $this->madeIn = $madeIn;
        $this->year = $year;
        $this->brand = $brand;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getMadeIn(): string
    {
        return $this->madeIn;
    }
    public function getYear(): int
    {
        return $this->year;
    }
    public function getBrand(): string
    {
        return $this->brand;
    }
}
class ParkingLotCar
{
    private Car $car;
    private int $price;
    private string $sellingInfo;

    public function __construct(Car $car , int $price , string $sellingInfo )
    {
        $this->car = $car;
        $this->price = $price;
        $this->sellingInfo = $sellingInfo;
    }
    public function getPrice(): int
    {
        return $this->price;
    }
    public function getSellingInfo(): string
    {
        return $this->sellingInfo;
    }
    public function getCar(): Car
    {
        return $this->car;
    }
}

class ParkingLot
{
    private array $cars;
    private int $cashRegister;

    public function __construct(array $cars, int $cashRegister = 0)
    {
        foreach ($cars as $car)
        {
            $this->addCar($car);
        }
        $this->cashRegister = $cashRegister;
    }
    private function addCar (ParkingLotCar $car):void
    {
        $this->cars[] = $car;
    }
    public function getCars(): array
    {
        return $this->cars;
    }
    public function addToCashRegister(int $cashRegister): void
    {
        $this->cashRegister += $cashRegister;
    }
}

class ParkingLotService
{
    private ParkingLot $parkingLot;

    public function __construct(ParkingLot $parkingLot)
    {
        $this->parkingLot = $parkingLot;
    }
    public function showCars():void
    {
        echo 'Cars available in our Parking lot : ' .PHP_EOL;
        foreach ($this->parkingLot->getCars() as $carInfo)
        {
            echo $carInfo->getCar()->getBrand().' '.$carInfo->getCar()->getName().'('.$carInfo->getCar()->getYear().')'.' price : '.$carInfo->getPrice().' | info: '.$carInfo->getSellingInfo() . PHP_EOL;
        }
    }
    public function tryToSellCar()
    {
        echo PHP_EOL . 'Enter car name to buy it ' .PHP_EOL;
        $input = readline('>');
        foreach ($this->parkingLot->getCars() as $storedCar)
        {
            $car = $storedCar->getCar();
            if(strtoupper($car->getName())== strtoupper($input))
            {
                echo 'You have bought '.$car->getBrand().' '.$car->getName().PHP_EOL;
                $this->parkingLot->addToCashRegister($storedCar->getPrice());
                exit;
            }
        }
        echo 'No such Cars in our parking Lot. Bye Bye! '.PHP_EOL;
    }
}

$lot = new ParkingLot([
    new ParkingLotCar(new Car('Passat','Germany',2000,'VW'),4567,'Imported from Germany, owned by lady'),
    new ParkingLotCar(new Car('Golf','Germany',2010,'VW'),9999,'Perfect technical condition')
]);

$parkingService = new ParkingLotService($lot);
$parkingService->showCars();
$parkingService->tryToSellCar();