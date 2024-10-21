<?php

namespace App\Form;

use App\Entity\Event;
use Doctrine\ORM\Query\AST\Subselect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
            'label' => 'Title',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Mettez un titre'
            ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                'class' => 'form-control'
                ]
            ])
            ->add('date', null, [
                'widget' => 'single_text',
                'attr' => [
                'class' => 'form-control'
                ]
            ])
            ->add('localisation', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('maxParticipant', IntegerType::class, [
                'label' => 'Nombre Max de participants',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1
                ]
            ])
            ->add('save' , SubmitType::class, [
                'label' => 'Créer',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
