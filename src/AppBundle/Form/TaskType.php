<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('description', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('deadline', DateTimeType::class, array(
                'widget' => 'single_text',
                'attr' => array('class' => 'form-control',
                    'readonly' => 'readonly',)
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Save',
                'attr' => array('class' => 'btn btn-primary')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task',
        ));
    }
}