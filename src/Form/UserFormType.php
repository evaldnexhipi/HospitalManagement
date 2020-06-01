<?php

namespace App\Form;

use App\Entity\User;
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
use Symfony\Component\Validator\Constraints\File;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,[
                'required'=>true,
                'label'=>false,
            ])
            ->add('lastName',TextType::class,[
                'required'=>true,
                'label'=>false,
            ])
            ->add('email',EmailType::class,[
                'required'=>true,
                'label'=>false,
                'invalid_message'=>'Emaili ekziston',
                'csrf_message'=>'Emaili ekziston'
            ])
            ->add('telephone',TelType::class,[
                'required'=>true,
                'label'=>false,
                'invalid_message'=>'Format i gabuar'
            ])
            ->add('address',TextType::class,[
                'required'=>false,
                'label'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
