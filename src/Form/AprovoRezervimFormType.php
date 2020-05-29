<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\MedicalStaff;
use App\Entity\Reservation;
use App\Entity\Service;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
;

class AprovoRezervimFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client',EntityType::class,[
                'class'=>Client::class,
                'attr'=>[
                    'readonly'=>true
                ],
                'label'=>false
            ])
            ->add('medicalStaff',EntityType::class,[
                'class'=>MedicalStaff::class,
                'attr'=>[
                    'readonly'=>true
                ],
                'label'=>false
            ])
            ->add('service',EntityType::class,[
                'class'=>Service::class,
                'attr'=>[
                    'readonly'=>true
                ],
                'label'=>false
            ])
            ->add('status',null,[
                'attr'=>[
                    'readonly'=>true
                ],
                'label'=>false
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
