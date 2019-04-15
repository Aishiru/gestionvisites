<?php

namespace App\Form;

use App\Entity\Visiteur;
use App\Form\OffrirType;
use App\Entity\Praticien;
use App\Entity\RapportVisite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RapportVisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('rap_date')*/
            ->add('rap_bilan', TextareaType::class, [
                'attr' => [
                    'class' => 'textarea',
                    'placeholder' => 'bilan du rapport'
                ],
                'label' => 'Bilan'
            ])
            ->add('rap_motif', TextType::class,[
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'Motif du rapport'
                ],
                'label' => 'Motif'
            ])
            ->add('visiteur', EntityType::class,[
                'class' => Visiteur::class,
            ])
            ->add('praticien', EntityType::class,[
                'class' => Praticien::class,
                'attr' => [
                    'placeholder' => 'Motif du rapport'
                ]
            ])
            ->add('offrirs', CollectionType::class, array(
                'entry_type' => OffrirType::class,
                'entry_options' => array('label' => false),
                'required' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RapportVisite::class,
        ]);
    }
}
