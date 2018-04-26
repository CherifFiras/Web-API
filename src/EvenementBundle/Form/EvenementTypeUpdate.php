<?php

namespace EvenementBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementTypeUpdate extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateEvenement')
            ->add('titre')
            ->add('nbplaces')
            ->add('description',CKEditorType::class)
            ->add('titreCordination',ChoiceType::class ,array(
                'choices'=>array(
                    'Gabes'=>'Gabes',
                    'Tunis'=>'Tunis',
                    'Sousse'=>'Sousse',
                    'Sfax'=>'Sfax',
                    'Ariana'=>'Aariana',
                    'Beja'=>'Beja',
                    'Ben Arous'=>'Ben Arous',
                    'Bizerte'=>'Bizerte',
                    'Gafsa'=>'Gafsa',
                    'Jendouba'=>'Jendouba',
                    'Kairouan'=>'Kairouan',
                    'Kasserine'=>'Kasserine',
                    'Kébili'=>'Kébili',
                    'Kef'=>'Kef',
                    'Mahdia'=>'Mahdia',
                    'Manouba'=>'Manouba',
                    'Madenine'=>'Madenine',
                    'Monastir'=>'Monastir',
                    'Nabeul'=>'Nabeul',
                    'Sidi Bouzid'=>'Sidi Bouzid',
                    'Siliana'=>'Siliana',
                    'Tataouine'=>'Tataouine',
                    'Tozeur'=>'Tozeur',
                    'Zaghouane'=>'Zaghouane'
                ),
            ))
            ->add('Modifier', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'evenement_bundle_evenement_type_update';
    }
}
