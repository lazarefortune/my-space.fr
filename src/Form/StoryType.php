<?php

namespace App\Form;

use App\Entity\Story;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Donne un titre à ton histoire',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Racontes-nous ton histoire',
                'attr' => [
                    'class' => 'form-control'
                ],
                'config' => [
//                    'uiColor' => '#ffff3f',
                    'required' => true,
                ]
            ])
            ->add('privacy', ChoiceType::class, [
                'label' => 'Visibilité',
                'choices' => [
                    'Publique' => 'public',
                    'Amis uniquement' => 'friends',
                    'Privée' => 'private',
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('isDraft', null, [
                'label' => 'Mettre en brouillon',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Story::class,
        ]);
    }
}
