<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use App\Repository\CategoryOfProductsRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/shop", methods="GET")
*/
class ShopController extends AbstractController
{
/**
* @Route("/", name="shop", methods="GET")
*/
public function showCatalogOfProducts(ProductRepository $productRepository,
                                      CategoryOfProductsRepository $categoryOfProductsRepository){
    $products = $productRepository->findAll();
    $categoriesOfProducts = $categoryOfProductsRepository->findAll();
    return $this->render('shop.html.twig', [
        'products' => $products,
        'categoriesOfProducts' => $categoriesOfProducts
    ]);
}

}