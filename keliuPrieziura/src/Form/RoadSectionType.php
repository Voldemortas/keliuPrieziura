<?php

namespace App\Form;

use App\Entity\RoadSection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoadSectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roadNumber')
            ->add('name')
            ->add('sectionStart')
            ->add('sectionFinish')
            ->add('level')
            ->add('type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoadSection::class,
        ]);
    }
}
