<?php

namespace App\Form;

use App\Entity\Anamnesis;
use App\Entity\Client;
use App\Entity\Hall;
use App\Entity\MedicalStaff;
use App\Entity\Reservation;
use App\Entity\Service;
use App\Entity\Speciality;
use App\Entity\Treatment;
use App\Entity\User;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnamnesisFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client',EntityType::class,[
                'class'=>Client::class,
                'label'=>false
            ])
            ->add('medicalStaff',EntityType::class,[
                'class'=>MedicalStaff::class,
                'label'=>false
            ])
            ->add('diagnosis',TextType::class,[
                'label'=>false,
                'required'=>true
            ])
            ->add('symptoms',TextType::class,[
                'label'=>false,
                'required'=>true,
            ])
            ->add('treatment',EntityType::class,[
                'class'=>Treatment::class,
                'label'=>false,
                'required'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Anamnesis::class,
        ]);
    }
}
