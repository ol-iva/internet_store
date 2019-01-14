<?php

namespace App\Entity;

//use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Persistence\ObjectManager;
/**
  * Represents the cart in the session
 */
class CartOfStore implements \Countable
{
    private $session;

    private $objectManager;

    public function __construct(ObjectManager $objectManager = null)
    {
        $this->session = new Session();

        $this->objectManager = $objectManager;
    }

    public function add(Product $product)
    {
        $products = $this->all();
        $id = $product->getId();
        $quantity = 1;

        if ($this->hasProduct($id)){
            $quantity = $products[$product->getId()]['quantity'];
            $quantity++;
        }

        $products[$product->getId()] = [
            'id' => $id,
            'quantity' => $quantity
        ];

        $this->session->set('products', $products);
    }

    public function update(Product $product, ?int $quantity)
    {
        $products = $this->all();

        $products[$product->getId()] = [
            'id' => $product->getId(),
            'quantity' => $quantity
        ];

        $this->session->set('products', $products);
    }

    public function remove(Product $product)
    {
        $products = $this->all();
        unset($products[$product->getId()]);
        $this->session->set('products', $product);
    }

    public function clear()
    {
        $this->session->remove('products');
    }

    /**
     * Getting session products from the database
     *
     * @return Product[]|null
     */
    public function getProducts(): ?array
    {
        if ($this->hasProducts()){
            $ids = array_column($this->all(), 'id');

            $products = $this->objectManager
                ->getRepository(Product::class)
                ->findAllById($ids);

            return $products = array_map(
                function($p) {
                    return $p->setQuantity($this->getQuantity($p));
                },
                $products
            );
        }
        return null;
    }

    public function getQuantity(Product $product): ?int
    {
        return $this->all()[$product->getId()]['quantity'];
    }

    public function hasProduct(?string $key): ?bool
    {
        return isset($this->all()[$key]);
    }

    public function all(): ?array
    {
        return $this->session->get('products');
    }

    public function hasProducts(): ?bool
    {
        return !empty($this->session->get('products'));
    }

    public function count(): ?int
    {
        $quantity = 0;

        if ($products = $this->all()){
            foreach($products as $product){
                $quantity += $product['quantity'];
            }
        }
        return $quantity;
    }

    public function totalPrice(?array $products): ?float
    {
        $totalPrice = 0;

        foreach($products as $product){
            $totalPrice += $product->calcTotalPrice();
        }

        return round($totalPrice, 2);
    }

    public function priceOfVat(float $totalPrice, float $vatRate = 0.2): ?float
    {
        $basePrice = $totalPrice/(1 + $vatRate);
        $priceOfVat = $totalPrice - $basePrice;

        return round($priceOfVat, 2);
    }

    public function addShippingMethod($shippinMethod)
    {
        $this->session->set('shipping', $shippinMethod);
    }

    public function getShippingMethod(): ShippingMethod
    {
        $id = $this->session->get('shipping')->getId();

        return $this->objectManager
            ->getRepository(ShippingMethod::class)
            ->find($id);
    }

    public function getShippingFee(): ?float
    {
        return $this->session->get('shipping')->getFee();
    }

    public function getTotalFee(): ?float
    {
        return $this->totalPrice($this->getProducts()) + $this->getShippingFee();
    }

}