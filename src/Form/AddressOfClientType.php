<?php
/**
 * Created by PhpStorm.
 * User: oliva
 * Date: 14.01.19
 * Time: 20:31
 */

namespace App\Form;


use App\Entity\AddressOfClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressOfClientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address1', TextType::class)
            ->add('address2', TextType::class)
            ->add('postCode', TextType::class)
            ->add('city', TextType::class)
            ->add('phone', TelType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AddressOfClient::class
        ]);
    }

}