<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Hall;
use App\Entity\MedicalStaff;
use App\Entity\Patient;
use App\Entity\Room;
use App\Entity\Speciality;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'required'=>true,
                'label'=>false,
            ])
            ->add('surname',TextType::class,[
                'required'=>true,
                'label'=>false,
            ])
            ->add('email',EmailType::class,[
                'required'=>true,
                'label'=>false,
            ])
            ->add('gender',ChoiceType::class,[
                'choices'=>[
                    'Femer'=>'F',
                    'Mashkull'=>'M'
                ],
                'required'=>true,
                'label'=>false
            ])
            ->add('birthday', BirthdayType::class,[
                'required'=>true,
                'label'=>false,
                'attr'=>[
                    'class'=>'nice-select'
                ],
                'html5'=>'false'
            ])
            ->add('tel',TelType::class,[
                'required'=>true,
                'label'=>false,
                'invalid_message'=>'Format i gabuar'
            ])
            ->add('diagnosis',TextType::class,[
                'required'=>false,
                'label'=>false,
            ])
            ->add('room',EntityType::class,[
                'required'=>true,
                'label'=>false,
                'class'=>Room::class
            ])
            ->add('address',TextType::class,[
                'required'=>true,
                'label'=>false
            ])
            ->add('cost',NumberType::class,[
                'required'=>true,
                'label'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class
        ]);
    }
}
