<?php

namespace App\Controller;

use App\Entity\CartOfStore;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;

class CartOfStoreController extends AbstractController
{
    private $cartOfStore;

    public function __construct(ObjectManager $objectManager)
    {
        $this->cartOfStore = new CartOfStore($objectManager);
    }

    /**
     * @Route("/cart", name="show_cart", methods="GET")
     */
     public function showCart()
    {
        $products = [];

        $totalPrice = 0;

        if ($this->cartOfStore->hasProducts()){
            $products = $this->cartOfStore->getProducts();
            $totalPrice = $this->cartOfStore->totalPrice($products);
        }

        return $this->render('cart_of_store.html.twig', [
            'products' => $products,
            'totalPrice' => $totalPrice
        ]);

    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart", methods="GET")
     */
    public function add($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product){
            throw $this->createNotFoundException();
        }

        if ($product->hasStock()){
            $this->cartOfStore->add($product);
        } else{
            $this->addFlash('warning', 'The product is absent in stock');
        }

        $id = $product->getId();

        return $this->redirectToRoute('show_product', [
            'id' => $id
        ]);
    }

    /**
     * @Route("cart/remove/{id}", name="remove_from_cart")
     */
    public function remove($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product){
            throw $this->createNotFoundException();
        }

        $this->cartOfStore->remove($product);

        return $this->redirectToRoute('show_cart');
    }

    /**
     * @Route("/cart/update", name="update_cart")
     */
    public function update(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $quantity = $data['quantity'];
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product){
            throw $this->createNotFoundException();
        }

        $this->cartOfStore->update($product, $quantity);

        $products = $this->cartOfStore->getProducts();

        $totalPrice = $this->cartOfStore->totalPrice($products);

        return new JsonResponse([
            'price' => $product->calcTotalPrice(),
            'totalPrice' => $totalPrice
        ]);
    }

    public function productCount()
    {
        return new Response(count($this->cartOfStore));
    }

}