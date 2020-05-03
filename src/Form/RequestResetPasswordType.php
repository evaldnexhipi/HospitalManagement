<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

class RequestResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'required'=>true,
                'label'=>false,
                'invalid_message'=>'Emaili ekziston',
                'csrf_message'=>'Emaili ekziston'
            ]);
    }
}