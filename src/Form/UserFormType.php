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
                'label'=>true,
            ])
            ->add('lastName',TextType::class,[
                'required'=>true,
                'label'=>true,
            ])
            ->add('email',EmailType::class,[
                'required'=>true,
                'label'=>true,
                'invalid_message'=>'Emaili ekziston',
                'csrf_message'=>'Emaili ekziston'
            ])
            ->add('password', RepeatedType::class, [
                'type'=>PasswordType::class,
                'options'=>[
                    'attr'=>['class'=>'input--style-4', 'name'=>'password']
                ],
                'required'=>true,
                'first_options'=>['label'=>true],
                'second_options'=>['label'=>true],
                'invalid_message'=>'Fjalekalimet nuk perputhen'
            ])
            ->add('gender',ChoiceType::class,[
                'choices'=>[
                    'Femer'=>'F',
                    'Mashkull'=>'M'
                ],
                'required'=>true,
                'label'=>true
            ])
            ->add('birthday', DateType::class,[
                'required'=>true,
                'label'=>false,
                'attr'=>[
                    'class'=>'js-datepicker'
                ],
                'html5'=>'false'
            ])
            ->add('telephone',TelType::class,[
                'required'=>true,
                'label'=>true,
                'invalid_message'=>'Format i gabuar'
            ])
            ->add('address',TextType::class,[
                'required'=>false,
                'label'=>true,
            ])
            ->add('imageFilename',FileType::class,[
                'label'=>true,
                'mapped'=>false,
                'required'=>false,
                'constraints'=>[
                    new File([
                        'maxSize'=>'8192k',
                        'mimeTypes'=>[
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Ju lutemi te vendosni nje foto',
                    ])
                ],
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
