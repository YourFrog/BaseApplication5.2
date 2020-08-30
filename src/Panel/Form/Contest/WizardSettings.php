<?php

namespace YourFrog\Panel\Form\Contest;

use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints;
use YourFrog\Panel\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *  Formularz obsługujący tworzenie konfiguracji dla konkursu
 *
 * @package YourFrog\Panel\Form\Contest
 */
class WizardSettings extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('title', Type\TextType::class, [
            'label' => 'Nazwa konkursu',
            'required' => true,
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
            ],
            'attr' => [
                'placeholder' => 'Tytuł konkursu'
            ]
        ]);

        $builder->add('description', Type\TextareaType::class, [
            'label' => 'Opis',
            'required' => true,
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
            ],
            'attr' => [
                'placeholder' => 'Wprowadź opis konkursu'
            ]
        ]);

        $builder->add('application_start_time', Type\DateTimeType::class, [
            'label' => 'Data rozpoczęcia przyjmowania zgłoszeń',
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
            ],
        ]);

        $builder->add('application_finish_time', Type\DateTimeType::class, [
            'label' => 'Data zakończenia przyjmowania zgłoszeń',
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
            ],
        ]);

        $builder->add('vote_start_time', Type\DateTimeType::class, [
            'label' => 'Data rozpoczęcia możliwości głosów',
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
            ],
        ]);

        $builder->add('vote_finish_time', Type\DateTimeType::class, [
            'label' => 'Data zakończenia przyjmowania głosów',
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
            ],
        ]);

        $builder->add('vote_on_ip', Type\TextType::class, [
            'label' => 'Maksymalna ilość głosów z 1 adresu IP',
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
            ],
            'attr' => [
                'placeholder' => 'Wprowadź liczbę'
            ]
        ]);

        $builder->add('vote_confirmation_time', Type\TextType::class, [
            'label' => 'Czas ważności głosu',
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
            ],
            'attr' => [
                'placeholder' => 'podaj w sekundach 3600sek = 1h'
            ]
        ]);

        $builder->add('accept_application', Type\ChoiceType::class, [
            'label' => 'Sposób akceptacji zgłoszeń',
            'required' => true,
            'choices' => [
                'manualna' => Entity\Contest\Settings::ACCEPT_APPLICATION_MANUAL,
                'automatyczna' => Entity\Contest\Settings::ACCEPT_APPLICATION_AUTOMATIC,
                'potwierdzenie przez e-mail' => Entity\Contest\Settings::ACCEPT_APPLICATION_CONFIRMATION,
                'manualna z potwierdzeniem przez e-mail' => Entity\Contest\Settings::ACCEPT_APPLICATION_MANUAL_WITH_CONFIRMATION,
                'automatyczne z potwierdzeniem przez e-mail' => Entity\Contest\Settings::ACCEPT_APPLICATION_MANUAL_WITH_CONFIRMATION
            ],
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
            ],
        ]);

        $builder->add('accept_vote', Type\ChoiceType::class, [
            'label' => 'Sposób akceptacji głosów',
            'required' => true,
            'choices' => [
                'manualna' => Entity\Contest\Settings::ACCEPT_APPLICATION_MANUAL,
                'automatyczna' => Entity\Contest\Settings::ACCEPT_APPLICATION_AUTOMATIC,
                'potwierdzenie przez e-mail' => Entity\Contest\Settings::ACCEPT_APPLICATION_CONFIRMATION,
                'manualna z potwierdzeniem przez e-mail' => Entity\Contest\Settings::ACCEPT_APPLICATION_MANUAL_WITH_CONFIRMATION,
                'automatyczne z potwierdzeniem przez e-mail' => Entity\Contest\Settings::ACCEPT_APPLICATION_MANUAL_WITH_CONFIRMATION
            ],
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
            ],
        ]);

        $builder->add('regulations', Type\FileType::class, [
            'label' => 'Plik z regulaminem (pdf)',
            'required' => true,
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
                new Constraints\File([
                    'maxSize' => '1m',
                    'mimeTypes' => [
                        'application/pdf',
                    ]
                ]),
            ],
        ]);

        $builder->add('rodo', Type\FileType::class, [
            'label' => 'Plik z rodo (pdf)',
            'required' => true,
            'constraints' => [
                new Constraints\Required(),
                new Constraints\NotBlank(),
                new Constraints\File([
                    'maxSize' => '1m',
                    'mimeTypes' => [
                        'application/pdf',
                    ]
                ])
            ]
        ]);

        $builder->add('notify_admins', Type\CheckboxType::class, [
            'label' => 'Wysyłaj powiadomienia administratorom o nowych zgłoszeniach'
        ]);

        $builder->add('submit', Type\SubmitType::class, [
            'label' => 'Zapisz'
        ]);
    }

}