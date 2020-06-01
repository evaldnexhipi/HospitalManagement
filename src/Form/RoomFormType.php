<?php

namespace App\Form;

use App\Entity\Departament;
use App\Entity\Room;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class RoomFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,['label'=>false, 'required'=>true])
            ->add('capacity',NumberType::class,['label'=>false, 'required'=>true])
            ->add('departament',EntityType::class,['class'=>Departament::class, 'label'=>false])
            ->add('status',ChoiceType::class,[
                'label'=>false,
                'required'=>true,
                'choices'=>[
                    'E Lire'=>true,
                    'E Zene'=>false
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Room::class
        ]);
    }
}
