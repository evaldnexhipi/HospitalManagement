<?php

namespace App\Form;

use App\Entity\MedicalStaff;
use App\Entity\Reservation;
use App\Entity\Service;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medicalStaff',EntityType::class,[
                'class'=>MedicalStaff::class,
                'label'=>false,
                'required'=>true
            ])
            ->add('day', DateType::class, [
                'widget'=>'single_text',
                'required'=>true,
                'label'=>false
            ])
            ->add('availableTimes', ChoiceType::class,[
                'label'=>false
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {

                // get the form from the event
                $form = $event->getForm();
                $formOptions = $form->getConfig()->getOptions();

                //helpers
                //$availableTimeHandler = $formOptions['availableTimes'];

                // get the form data, that got submitted by the user with this request / event
                $data = $event->getData();

                //get date
                $preferredDate = $data['day'];

                // get the availableTimes element and its options
                $fieldConfig = $form->get('availableTimes')->getConfig();
                $fieldOptions = $fieldConfig->getOptions();




                 $times =
                 [
                        ['time' => '10:00', 'disabled' => false],
                        ['time' => '11:00', 'disabled' => false],
                        ['time' => '12:00', 'disabled' => false],
                        ['time' => '13:00', 'disabled' => false],
                        ['time' => '14:00', 'disabled' => false],
                        ['time' => '15:00', 'disabled' => true],
                 ];


                $choices = [];
                foreach ($times as $time) {
                    $choices[] = [$time['time'] => $time['time']];
                }

                //update choices
                $form->add('availableTimes', ChoiceType::class,
                    array_replace(
                        $fieldOptions, [
                            'choices' => $choices
                        ]
                    )
                );
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
