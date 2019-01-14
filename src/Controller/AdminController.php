<?php
namespace App\Controller;

use App\Entity\ImageOfProduct;
use App\Entity\Order;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\OrderStatusType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;


class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin_home")
     * @param ProductRepository $productRepository
     * @param TransactionRepository $transactionRepository
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function adminIndex(ProductRepository $productRepository,
                          TransactionRepository $transactionRepository)
    {
        $productRows = $productRepository->countCurrentlySold();

        $totalRevenue = $transactionRepository->sumAllTransactions();
        return $this->render('admin/admin_index.html.twig', [
            'product_rows' => $productRows,
            'total_revenue' => $totalRevenue,
        ]);
    }

    /**
     * @Route("/admin/orders", name="admin_order")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminOrderIndex()
    {
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findAll();

        return $this->render('admin/admin_orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/admin/order-details/{id}", name="admin_show_order")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminShowOrder(Request $request, $id)
    {
        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->find($id);
        if (!$order) {
            throw $this->createNotFoundException('This command does not exist');
        }
        $form = $this->createForm(OrderStatusType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
        }
        return $this->render('admin/order_details.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/all-products/{page}", name="admin_all_products")
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminProductsIndex(Request $request, $page)
    {
        $form = $this->createFormBuilder()
            ->add('search', SearchType::class)
            ->getForm();

        $form->handleRequest($request);

        $maxResults = 10;
        $firstResult = $maxResults * ($page - 1);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->getData();

            $products = $this->getDoctrine()
                ->getRepository(Product::class)
                ->search($query['search'], $firstResult, $maxResults);
        } else {
            $products = $this->getDoctrine()
                ->getRepository(Product::class)
                ->getPaginated($firstResult, $maxResults);
        }

        $totalResults = count($products);
        $totalPages = 1;
        if ($totalResults > 0) {
            $totalPages = ceil($totalResults / $maxResults);
        }
        return $this->render('admin/all_products.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'total_pages' => $totalPages,
            'current_page' => $page,
        ]);
    }

    /**
     * @Route("admin/product-editor/{id}", name="admin_product_editor")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminProductsEditor(Request $request, $id)
    {
        $product = new Product();
        $title = 'New product';

        if ($id) {
            $product = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($id);

            if (!$product) {
                throw $this->createNotFoundException('This product does not exist');
            }

            $title = 'Editing a product';
        } else {
            $product->addImagesOfProduct(new ImageOfProduct());
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($product->getImagesOfProduct() as $image) {
                if ($file = $image->getFile()) {
                    $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $filesize = filesize($file);
                    $image->setSize($filesize);
                    $image->setName($filename);
                    $file->move($this->getParameter('images_directory'), $filename);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product added');

            return $this->redirect($this->generateUrl('admin_product_editor', [
                'id' => $product->getId(),
            ]));
        }
        return $this->render('admin/product_editor.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
        ]);
    }

    /**
     * @Route("\admin\product-delete\{id}", name="admin_product_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository(Product::class)->find($id);
        $product->setDeletedAt(new \Datetime());

        $em->persist($product);
        $em->flush();
        $this->addFlash('success', 'Product deleted');
        return $this->redirectToRoute('admin_home');
    }
}