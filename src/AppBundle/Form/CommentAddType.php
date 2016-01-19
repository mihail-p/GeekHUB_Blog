<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', EntityType::class, [
                'class' => 'AppBundle\Entity\Author',
                'choice_label' => 'author'])
            ->add('score', ChoiceType::class, [
                'choices' => [
                    'one' => 1,
                    'two' => 2,
                    'three' => 3,
                    'four' => 4,
                    'five' => 5
                ], 'choices_as_values' =>true
            ])
            ->add('comment', TextareaType::class,[
                'attr' => array('cols' => '57', 'rows' => '7')
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Comment']);
    }
}
