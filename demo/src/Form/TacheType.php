<?php

namespace App\Form;

use App\Entity\Tache;
use App\Entity\ArticleSemaine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('DueDate', DateType::class,[
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour',
                ]
            ])
            ->add('Done')
            ->add('Semaine', EntityType::class, [
                'class' => ArticleSemaine::class,
                'choice_label' => 'title'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
    }
}
