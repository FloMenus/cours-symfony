<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\CancellationPolicy;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('propertyType', ChoiceType::class, [
                'choices' => [
                    'Maison'      => 'house',
                    'Appartement' => 'apartment',
                    'Loft'        => 'loft',
                    'Chalet'      => 'chalet',
                    'Studio'      => 'studio',
                    'Villa'       => 'villa',
                ],
            ])
            ->add('maxGuests', IntegerType::class)
            ->add('bedrooms', IntegerType::class)
            ->add('beds', IntegerType::class)
            ->add('bathrooms', IntegerType::class)
            ->add('pricePerNight', NumberType::class, [
                'scale' => 2,
            ])
            ->add('cleaningFee', NumberType::class, [
                'scale'    => 2,
                'required' => false,
            ])
            ->add('securityDeposit', NumberType::class, [
                'scale'    => 2,
                'required' => false,
            ])
            ->add('instantBooking', CheckboxType::class, [
                'required' => false,
            ])
            ->add('cancellationPolicy', EntityType::class, [
                'class'        => CancellationPolicy::class,
                'choice_label' => 'label',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
