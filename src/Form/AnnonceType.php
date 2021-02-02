<?php

namespace App\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use App\Form\CreneauType;
use App\Entity\Annonce;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('prix', NumberType::class)
            ->add('dureeEnMin', IntegerType::class, [
                'attr' => [
                'min' => 15,
                'max' => 500
            ]])
            ->add('lieux')
            ->add('matiere')
            ->add('presentiel')
            ->add('niveau')
            ->add('creneaus', CollectionType::class,[
                'entry_type' => CreneauType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class
        ]);
    }
}
