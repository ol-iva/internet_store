<?php

namespace App\Controller;


use App\Entity\CartOfStore;
use App\Repository\AddressOfClientRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


class CheckoutController extends AbstractController
{
//    private $config;

    private $cartOfStore;

    private $session;

    public function __construct(ObjectManager $objectManager)
    {
        $this->cartOfStore = new CartOfStore($objectManager);
        $this->session = new Session();
    }

    /**
     * @Route("/checkout/address", name="checkout_address")
     */
    public function address(Request $request, AddressOfClientRepository $addressOfClientRepository)
    {
        if ($this->cartOfStore->hasProducts()){
            return $this->redirectToRoute('show_cart');
        }

        $billingAddress = $addressOfClientRepository->findCurrentAddressWithType(
            $this->getUser()
                ->getId(), 'billing');

        if ($billingAddress === null) {
            $this->addFlash('primary', 'Please enter a billing address before continuing');

            //return $this->redirectToRoute('user_account');

            return $this->redirectToRoute('show_cart');
        }

        $addressOfClient = $addressOfClientRepository
            ->findCurrentAddressWithType($this->getUser()->getId(), 'shipping');

        $form = $this->createForm(AddressType::class, $addressOfClient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $addressOfClient = $form->getData();

            $uow = $this->getDoctrine()
                ->getManager()
                ->getUnitOfWork();

            if ($uow->isEntityScheduled($addressOfClient)){
                $addressOfClient = clone $addressOfClient;
                $addressOfClient =setDateCreatedAt(new \DateTime());
            }

            $addressOfClient->setType('shipping')
                ->setCountry('Ukraine')
                ->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();

            $em->persist($addressOfClient);
            $em->flush();

            $this->session->set('checkout/address', true);

            return $this->redirectToRoute('checkout_shipping');
        }

        return $this->render('checkout/address.html.twig', [
            'address_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/checkout/shipping", name="checkout_shipping")
     */
    public function shipping(Request $request)
    {
        if (!$this->session->get('checkout/address')){
            return $this->redirectToRoute('show_cart');
        }

        $form = $this->createForm(\App\Form\ShippingMethod::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $shippingMethod = $form->getData()['shippingMethod'];

            $this->cartOfStore->addShippingMethod($shippingMethod);

            $this->session->set('checkout/shipping', true);

            return $this->redirectToRoute('checkout_summary');
        }

        return $this->render('checkout/shipping.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/checkout/summary", name="checkout_summary")
     */
    public function summary()
    {
        if (!$this->session->get('checkout/shipping')){
            return $this->redirectToRoute('show_cart');
        }

        $this->session->set('checkout/summary', true);

        $products = $this->cartOfStore->getProducts();
        $totalPrice = $this->cartOfStore->totalPrice($poducts);

        $priceOfVat = $this->cartOfStore->priceOfVat($this->cartOfStore->getTotalFee());

        $shippingFee = $this->cartOfStore->getShippingMethod()->getShippingFee();

        $totalFee = $this->cartOfStore->getTotalFee();

        return $this->render('checkout/summary.html.twig', [
            'products' => $products,
            'total_price' => $totalPrice,
            'shipping_fee' => $shippingFee,
            'price_of_vat' => $priceOfVat,
            'total_fee' => $totalFee
        ]);
    }
    /**
     * @Route("/checkout/payment", name="checkout_payment")
     */
    public function payment()
    {
        if (!$this->session->get('checkout/summary')){
            return $this->redirectToRoute('show_cart');
        }
        $grandTotal = $this->cartOfStore->getTotalFee();

        return $this->render('checkout/payment.html.twig', [
            'total_price' => $grandTotal
    ]);
    }

}