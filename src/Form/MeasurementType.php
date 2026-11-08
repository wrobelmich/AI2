<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Measurement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => fn (Location $l) => $l->getCity() . ' (' . $l->getCountry() . ')',
                'label' => 'Lokalizacja',
                'placeholder' => 'Wybierz lokalizację',
            ])
            ->add('date', DateType::class, [
                'label' => 'Data',
                'widget' => 'single_text',
            ])
            ->add('celsius', NumberType::class, [
                'label' => 'Temperatura (°C)',
                'input' => 'string',
            ])
            ->add('pressure', NumberType::class, [
                'label' => 'Ciśnienie (hPa)',
                'scale' => 2,
                'input' => 'string',
            ])
            ->add('humidity', NumberType::class, [
                'label' => 'Wilgotność (%)',
                'scale' => 2,
                'input' => 'string',
                'required' => false,
            ])
            ->add('wind_speed', NumberType::class, [
                'label' => 'Prędkość wiatru (km/h)',
                'scale' => 2,
                'input' => 'string',
                'required' => false,
            ])
            ->add('precipitation', NumberType::class, [
                'label' => 'Opady (mm)',
                'input' => 'string',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
