<?php

namespace App\Form;

use App\Entity\Resume;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditResumeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //Le champ où définir le nom du résumé de séance
            ->add('name', TextType::class)

            //le Champ où définir la description du résumé de séance
            ->add('description', TextareaType::class, [
                'purify_html' => true,
                'attr' => ['style' => 'height: 300px']
            ])

            //le Champ où définir le secteur d'activité du résumé de séance
            ->add('sector', TextType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resume::class,
        ]);
    }
}
