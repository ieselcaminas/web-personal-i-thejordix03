<?php

namespace App\Form;

use App\Entity\Autor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // ✅ IMPORTANTE

class AutorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre'
            ])
            ->add('pais', TextType::class, [
                'required' => false,
                'label' => 'Nacionalidad', // ✅ Lo que ve el usuario
            ])
            ->add('fechaNacimiento', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Fecha de nacimiento'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Autor::class,
        ]);
    }
}
