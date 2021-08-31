<?php

class Product
{
    public int $id;
    public string $name;
    public float $price;
    public string $madeBy;
    public int $amount;

    function __construct(int $id, string $name , float $price, string $madeBy, int $amount)
    {
        $this->amount = $amount;
        $this->madeBy = $madeBy;
        $this->price = $price;
        $this->name = $name;
        $this->id = $id;
    }
}
class Storage
{
    public array $productList = [];

    public function insertProduct(Product $product)
    {
        $this->productList[] = $product;
    }
    public function insertProductList(array $products)
    {
        foreach ($products as $product)
        {
            $this->productList[] = $product;
        }
    }
    public function takeProductFromStorage(Product $product,int $amount) : Product
    {
        $product->amount -= $amount;
        $basketProduct = clone $product;
        $basketProduct->amount = $amount;
        return $basketProduct;
    }
    public function validateProductAmount(Product $product,int $amount):bool
    {
        return $amount <= $product->amount ;
    }
}
class Narvesen
{
    public Storage $storage ;
    public array $basket = [];
    public float $basketTotalPriceSum = 0;
    public float $cashRegister = 0;
    private string $checkBasketInput = 'basket';
    private string $exitShopInput = 'exit';
    private string $buyBasket = 'buy';

    function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }
    public function processCustomerAction(string $action, Customer $customer)
    {
        foreach ($this->storage->productList as $product)
            {
                if($action == $this->checkBasketInput){
                    $this->showBasket();
                    $this->validateBuy($customer);
                }
                elseif($action == $this->exitShopInput){
                    exit;
                }
                elseif($product->id == (int)$action )
                {
                    $this->showProductAmount($product);
                    $this->validateAmountRequest($customer,$product);
                }
            }
    }
    private function buyProducts(Customer $customer):bool
    {
        if($customer->balance >= $this->basketTotalPriceSum)
        {
            $customer->balance -= $this->basketTotalPriceSum;
            $this->cashRegister += $this->basketTotalPriceSum;
            return true;
        }else{
            return false;
        }
    }
    private function validateBuy(Customer $customer){

        while(true)
        {
            $action = $customer->giveAction();
            if ($action == $this->buyBasket){
                $this->displayPurchaseValidation($customer);
                exit;
            }elseif ($action == $this->exitShopInput){
                echo 'Left shop without Products!'.PHP_EOL;
                exit;
            }
        }
    }
    private function validateAmountRequest(Customer $customer,Product $product)
    {
        $validate = true;
        while($validate) {
            $amount = $customer->giveAction();
            $amount = (int)$amount;
            if ($this->storage->validateProductAmount($product, $amount)) {
                $this->basket[] = $this->storage->takeProductFromStorage($product, $amount, $this->basket);
                echo "$amount of $product->name added to basket" . PHP_EOL;
                $validate = false;
            } else {
                echo "Invalid amount Input, Available : $amount ".PHP_EOL;
            }
        }
    }
    private function displayPurchaseValidation(Customer $customer)
    {
        if($this->buyProducts($customer))
        {
            echo "Payment made successfully!".PHP_EOL;
            echo "Thank you for coming.".PHP_EOL;
        }else{
            echo "Not enough money in balance".PHP_EOL;
        }
    }
    public function showProductsAndActions()
    {
        echo " All products listed below : ".PHP_EOL;
        echo "---------------------------------------------------".PHP_EOL;
        foreach ($this->storage->productList as $product)
        {
            echo "ID($product->id) $product->name | made in $product->madeBy |price $$product->price | available amount:$product->amount".PHP_EOL;
        }
        echo "---------------------------------------------------".PHP_EOL;
        echo 'Enter product ID to put it in basket.'.PHP_EOL;
        echo "Enter '$this->checkBasketInput' to check Basket ".PHP_EOL;
        echo "Enter '$this->exitShopInput' to exit shop!".PHP_EOL;
    }
    private function showProductAmount(Product $product)
    {
        echo "Enter $product->name amount to buy (available : $product->amount) ".PHP_EOL;
    }
    private function showBasket()
    {
        echo 'Products in Basket : '.PHP_EOL;
        foreach ($this->basket as $product)
        {
            $this->basketTotalPriceSum += $product->price*$product->amount;
            echo "$product->name -- $product->amount = $".$product->price*$product->amount.PHP_EOL;
        }
        echo "Total : ".$this->basketTotalPriceSum.PHP_EOL;
        echo "Enter '$this->buyBasket' to make payment or '$this->exitShopInput' to leave : ".PHP_EOL;
    }
}
class Customer
{
    public array $basket ;
    public string $name;
    public int $balance;
    function __construct(string $name , int $balance)
    {
        $this->balance = $balance;
        $this->name = $name;
    }
    public function giveAction():string
    {
        return readline('> ');
    }
}
$apple = new Product(1,'apple',2,'Poland',100);
$bread = new Product(2,'bread',1.2,'Rigas Maiznieks',30);
$milk = new Product(3,'Milk',0.99,'Talsu Kombināts',18);
$cheese = new Product(4,'cheese',2.2,'Smiltenes Kombināts',12);

$NarvesenStorage = new Storage();
$NarvesenStorage->insertProduct($apple);
$NarvesenStorage->insertProduct($bread);
$NarvesenStorage->insertProduct($milk);
$NarvesenStorage->insertProduct($cheese);

$Narvesen = new Narvesen($NarvesenStorage);
$Reinis = new Customer('Reinis',200);
while (true)
{
    $Narvesen->showProductsAndActions();
    $Narvesen->processCustomerAction($Reinis->giveAction(), $Reinis);
}
