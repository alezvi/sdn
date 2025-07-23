<?php

namespace App\Form;

use App\Entity\CondicionIVA;
use App\Entity\ProductoServicio;
use App\Entity\Rubro;
use App\Entity\UnidadDeMedida;
use App\Enum\TiposDeItems;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductoServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tipo', ChoiceType::class, [
                'choices' => TiposDeItems::getChoices(),
                'attr' => ['class' => 'form-control my-2'],
            ])
            ->add('codigo', options: [
                'attr' => ['class' => 'form-control my-2'],
            ])
            ->add('producto_servicio', options: [
                'attr' => ['class' => 'form-control my-2'],
            ])
            ->add('precio_producto_unitario', options: [
                'attr' => ['class' => 'form-control my-2'],
            ])
            ->add('rubro', EntityType::class, [
                'class' => Rubro::class,
                'choice_label' => 'rubro',
                'attr' => ['class' => 'form-control my-2'],
            ])
            ->add('unidadMedida', EntityType::class, [
                'class' => UnidadDeMedida::class,
                'choice_label' => 'unidad_medida',
                'attr' => ['class' => 'form-control my-2'],
            ])
            ->add('condicionIva', EntityType::class, [
                'class' => CondicionIVA::class,
                'choice_label' => 'condicion_iva',
                'attr' => ['class' => 'form-control my-2'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Guardar',
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductoServicio::class,
        ]);
    }
}
