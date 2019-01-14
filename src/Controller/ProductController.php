<?php

namespace App\Controller;
//
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;

use App\Entity\Product;
use App\Entity\CategoryOfProducts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/show/{id}", name="show_product", methods="GET")
     */
    public function showProduct($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)
            ->find($id);
        $categoriesOfProducts = $this->getDoctrine()->getRepository(CategoryOfProducts::class)
        ->findAll();

        if (!$product){
            throw $this->createNotFoundException();
        }

        return $this->render('show_product.html.twig', [
            'product' => $product,
            'categoriesOfProducts' => $categoriesOfProducts
        ]);
    }

}