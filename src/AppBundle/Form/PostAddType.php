<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', EntityType::class, [
                'class' => 'AppBundle:Author',
                'choice_label' => 'author'])
            ->add('title', TextType::class)
            ->add('post', TextareaType::class,[
                'attr' => array('cols' => '68', 'rows' => '10')
            ])
            ->add('tags', EntityType::class, [
              'class' => 'AppBundle\Entity\Tag',
              'choice_label' => 'tag',
              'multiple' => 'true',

            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Post']);
    }
}