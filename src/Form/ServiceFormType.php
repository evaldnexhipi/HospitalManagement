<?php

namespace App\Form;

use App\Entity\Departament;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class ServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,['label'=>false, 'required'=>true])
            ->add('description',TextType::class,['label'=>false, 'required'=>true])
            ->add('departament',EntityType::class,['class'=>Departament::class, 'label'=>false])
            ->add('cost',NumberType::class,['label'=>false,'required'=>true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class
        ]);
    }
}
