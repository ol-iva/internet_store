<?php

namespace App\Controller;

use App\Repository\CategoryOfProductsRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\CategoryOfProducts;


class InternetStoreController extends AbstractController
{
    /**
     * @Route("/", name="internet_store_home", methods="GET")
     */
    public function index(ProductRepository $productRepository,
                          CategoryOfProductsRepository $categoryOfProductsRepository): Response
    {
        $products = $productRepository->findAllWithDeleted();
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
