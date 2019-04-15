<?php

namespace App\Form;

use App\Entity\Offrir;
use App\Entity\Medicament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffrirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('off_qte', null, [
                'label' => 'Quantité'
            ])
            //->add('rapportVisite')
            ->add('medicament', EntityType::class,[
                'class' => Medicament::class,
                'attr' => [
                    'placeholder' => 'Medicament'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offrir::class,
        ]);
    }
}
