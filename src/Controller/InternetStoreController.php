<?php

namespace App\Controller;

use App\Repository\CategoryOfProductsRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\CategoryOfProducts;


class InternetStoreController extends Controller
{
    /**
     * @Route("/", name="internet_store_home", methods="GET")
     */
    public function index(Request $request, ProductRepository $productRepository,
                          CategoryOfProductsRepository $categoryOfProductsRepository): Response
    {
//        $em = $this->getDoctrine()->getManager();
//
//        $appointmentsRepository = $em->getRepository(Appointments::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allProductsQuery = $productRepository->createQueryBuilder('p')
            ->where('p.stock != :stock')
            ->setParameter('stock', '0')
            ->getQuery();

        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');

        // Paginate the results of the query
        $products = $paginator->paginate($allProductsQuery, $request->query->getInt('page', 1),3);

        $categoriesOfProducts = $categoryOfProductsRepository->findAll();
        $productsNewest = $productRepository->findNewest(4);

        return $this->render('index.html.twig', [
            'products' => $products,
            'productsNewest' => $productsNewest,
            'categoriesOfProducts' => $categoriesOfProducts
        ]);
    }

    /**
     * @Route("/all-products-in-category/{category}", name="products_in_category", methods={"GET"})
     */
    public function showProductsInCategory(CategoryOfProducts $category, ProductRepository $productRepository,
            CategoryOfProductsRepository $categoryOfProductsRepository)
    {
        $productsInCategory = $productRepository->findAllProductsInCategory($category);
        $nameOfCurrentCategory = $category->getName();
        $categoriesOfProducts = $categoryOfProductsRepository->findAll();
        $productsNewest = $productRepository->findNewest(4);

        return $this->render('products_in_category.html.twig', [
            'productsInCategory' => $productsInCategory,
            'productsNewest' => $productsNewest,
            'categoriesOfProducts' => $categoriesOfProducts,
            'nameOfCurrentCategory' => $nameOfCurrentCategory
        ]);

    }


}
