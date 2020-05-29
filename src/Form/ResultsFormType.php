<?php

namespace App\Form;

use App\Entity\Results;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
class ResultsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('analysisPDF',FileType::class,[
                'label'=>false,
                'mapped'=>false,
                'required'=>true,
                'constraints'=>[
                    new File([
                        'maxSize'=>'8192k',
                        'mimeTypes'=>[
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Ju lutemi vendosni nje format valid PDF',
                    ])
                ],
            ])
         ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Results::class,
        ]);
    }
}
