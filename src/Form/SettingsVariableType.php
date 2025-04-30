<?php
// /src/Form/SettingsVariableType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsVariableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name der Variable'
            ])
            ->add('pre', ChoiceType::class, [
                'label' => 'Präfix der Variable',
                'choices' => [
                    'Allgemein (global_)' => 'global_',
                    'Benutzer (user_)' => 'user_',
                    'Seite (site_)' => 'site_',
                    'Rollen (roles_)' => 'roles_',
                    'Hierarchie (hierarchy_)' => 'hierarchy_',
                    // Weitere Präfixe nach Bedarf ergänzen
                ],
                'placeholder' => 'Bitte wählen',
            ])
            ->add('value', TextType::class, [
                'label' => 'Wert der Variable'
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (!empty($data['pre']) && !empty($data['name'])) {
                $data['name'] = $data['pre'] . $data['name'];
                $event->setData($data);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Settings::class,
        ]);
    }
}