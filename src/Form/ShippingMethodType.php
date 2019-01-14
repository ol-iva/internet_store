<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\ShippingMethod;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class ShippingMethodType extends AbstractType
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shippingMethod', EntityType::class, [
                'class' => ShippingMethod::class,
                'expanded' => true,
                'choices' => $this->objectManager
                    ->getRepository(ShippingMethod::class)
                    ->findAll(),
                'choice_label' => function (ShippingMethod $method) {
                    return $method->getNameOfShippingMethod();
                },
                'constraints' => [
                    new NotNull(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}