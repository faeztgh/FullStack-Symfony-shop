<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactFormType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * ResetPasswordRequestFormType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('FullName', TextType::class, [
                "label" => "app.contact.full_name",
                "required" => true,
                "label_attr" => [
                    'class' => "font-semibold"
                ],
                "attr" => [
                    "placeholder" => "app.contact.full_name"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('This value should not be blank.', [], 'validators'),
                    ])
                ]
            ])
            ->add('Email', EmailType::class, [
                "label" => "app.contact.email",
                "required" => true,
                "label_attr" => [
                    'class' => "font-semibold"
                ],
                "attr" => [
                    "placeholder" => "app.contact.email"
                ],
                'constraints' => [
                    new Email([
                        'message' =>  $this->translator->trans('Please enter a valid email address.', [], 'validators'),
                    ]),
                    new NotBlank([
                        'message' => $this->translator->trans('This value should not be blank.', [], 'validators'),
                    ])
                ]
            ])
            ->add('Subject', TextType::class, [
                "label" => "app.contact.subject",
                "required" => true,
                "label_attr" => [
                    'class' => "font-semibold"
                ],
                "attr" => [
                    "placeholder" => "app.contact.subject"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('This value should not be blank.', [], 'validators'),
                    ])
                ]
            ])
            ->add('Message', TextareaType::class, [
                "label" => "app.contact.message",
                "required" => true,
                "label_attr" => [
                    'class' => "font-semibold"
                ],
                "attr" => [
                    'cols' => "50",
                    'class' => "w-full h-48",
                    "placeholder" => "app.contact.message"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('This value should not be blank.', [], 'validators'),
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            "translation_domain" => "forms"
        ]);
    }
}
