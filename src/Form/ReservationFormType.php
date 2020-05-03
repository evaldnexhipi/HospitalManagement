<?php

namespace App\Form;

use App\Entity\MedicalStaff;
use App\Entity\Reservation;
use App\Entity\Service;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medicalStaff',EntityType::class,[
                'class'=>MedicalStaff::class
            ])
            ->add('day',DateType::class)
            ->add('status',ChoiceType::class,[
                'choices'=>[
                    'paguaj'=>'paguaj',
                    'pritje'=>'pritje'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
