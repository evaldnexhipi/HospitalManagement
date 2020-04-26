<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,[
                'required'=>true,
                'invalid_message'=>'XXXXXXXxx',
                'label'=>false
            ])
            ->add('lastName',TextType::class,[
                'required'=>true,
                'label'=>false,
            ])
            ->add('email',EmailType::class,[
                'required'=>true,
                'label'=>false
            ])
            ->add('password', RepeatedType::class, [
                'type'=>PasswordType::class,
                'invalid_message'=>'Fjalekalimit nuk perputhen',
                'options'=>[
                    'attr'=>['class'=>'input--style-4', 'name'=>'password']
                ],
                'required'=>true,
                'first_options'=>['label'=>false],
                'second_options'=>['label'=>false],
            ])
            ->add('gender',ChoiceType::class,[
                'choices'=>[
                    'Femer'=>'F',
                    'Mashkull'=>'M'
                ],
                'required'=>true,
                'label'=>false
            ])
            ->add('birthday', TextType::class,[
                'required'=>true,
                'label'=>false
            ])
            ->add('telephone',TelType::class,[
                'required'=>false,
                'label'=>false
            ])
            ->add('address',TextType::class,[
                'required'=>false,
                'label'=>false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label_attr'=>[
                  'class'=>'label'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Duhet te pranoni kushtet tona!',
                    ]),
                ],
                'invalid_message'=>'Duhet te pranoni kushtet tona!',
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
