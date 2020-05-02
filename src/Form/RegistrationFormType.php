<?php

namespace App\Form;

use App\Entity\User;
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

class RegistrationFormType extends AbstractType
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
            ->add('password', RepeatedType::class, [
                'type'=>PasswordType::class,
                'options'=>[
                    'attr'=>['class'=>'input--style-4', 'name'=>'password']
                ],
                'required'=>true,
                'first_options'=>['label'=>false],
                'second_options'=>['label'=>false],
                'invalid_message'=>'Fjalekalimet nuk perputhen'
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
            ->add('telephone',TelType::class,[
                'required'=>false,
                'label'=>false,
                'invalid_message'=>'Format i gabuar'
            ])
            ->add('address',TextType::class,[
                'required'=>false,
                'label'=>false,
            ])
            ->add('imageFilename',FileType::class,[
                'label'=>false,
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
