<?php

namespace App\Form;

use App\Entity\Ressource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RessourceTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TextType::class, [
                'label' => 'Intitulé',
                'attr' => ['class' => 'form-control']
            ])
            ->add('presentation', TextareaType::class, [
                'label' => 'Présentation',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4
                ]
            ])
            ->add('support', ChoiceType::class, [
                'label' => 'Support',
                'choices' => [
                    'PDF' => 'PDF',
                    'Vidéo' => 'video',
                    'HTML' => 'html',
                    'Formulaire' => 'form',
                    'Guide' => 'guide',
                    'QCM' => 'qcm'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('nature', ChoiceType::class, [
                'label' => 'Nature',
                'choices' => [
                    'Formulaire' => 'formulaire',
                    'Guide' => 'guide',
                    'QCM' => 'qcm',
                    'Document' => 'document'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('url', UrlType::class, [
                'label' => 'URL du document',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ressource::class,
        ]);
    }
}