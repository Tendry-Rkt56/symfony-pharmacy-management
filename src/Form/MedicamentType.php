<?php

namespace App\Form;

use App\Entity\Medicament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedicamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'required' => true,
                    'placeholder' => 'Nom...',
                    'id' => 'name',
                ],
                'label_attr' => [
                    'class' => 'fw-bolder'
                ]
            ])
            ->add('prix', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'required' => true,
                    'placeholder' => 'Prix...',
                    'id' => 'prix',
                ],
                'label_attr' => [
                    'class' => 'fw-bolder',
                ],
            ])
            ->add('nombre', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'required' => true,
                    'placeholder' => 'Nombre...',
                    'id' => 'nombre',
                ],
                'label_attr' => [
                    'class' => 'fw-bolder',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medicament::class,
        ]);
    }
}
