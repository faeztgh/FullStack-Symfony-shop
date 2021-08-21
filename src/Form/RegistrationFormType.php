<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
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
            ->add('firstName', TextType::class, [
                "translation_domain" => "forms",
                'label' => "app.registration.first_name",
                'required' => true,
                "label_attr" => [
                    'class' => "font-semibold"
                ],
                "attr" => [
                    "placeholder" => "app.registration.first_name"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('This value should not be blank.', [], 'validators'),
                    ])
                ]
            ])
            ->add('lastName', TextType::class, [
                "translation_domain" => "forms",
                'label' => "app.registration.last_name",
                'required' => true,
                "label_attr" => [
                    'class' => "font-semibold"
                ],
                "attr" => [
                    "placeholder" => "app.registration.last_name"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('This value should not be blank.', [], 'validators'),
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                "translation_domain" => "forms",
                'label' => "app.registration.email",
                'required' => true,
                "label_attr" => [
                    'class' => "font-semibold"
                ],
                "attr" => [
                    "placeholder" => "app.registration.email"
                ],
                'constraints' => [
                    new Email([
                        'message' => $this->translator->trans('Please enter a valid email address.', [], 'validators'),
                    ]),
                    new NotBlank([
                        'message' => $this->translator->trans('This value should not be blank.', [], 'validators'),
                    ])
                ]
            ])
            ->add('username', TextType::class, [
                "translation_domain" => "forms",
                'label' => "app.registration.username",
                'required' => true,
                "label_attr" => [
                    'class' => "font-semibold"
                ],
                "attr" => [
                    "placeholder" => "app.registration.username"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('This value should not be blank.', [], 'validators'),
                    ])
                ]
            ])
//            ->add('agreeTerms', CheckboxType::class, [
//                'mapped' => false,
//                'constraints' => [
//                    new IsTrue([
//                        'message' => 'You should agree to our terms.',
//                    ]),
//                ],
//            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                "translation_domain" => "forms",
                'label' => "app.registration.password",
                'required' => true,
                'mapped' => false,
                "label_attr" => [
                    'class' => "font-semibold"
                ],
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => "app.registration.password"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('This value should not be blank.', [], 'validators'),
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 20,
                    ]),


                ],
            ])
            ->add('address', TextType::class, [
                "translation_domain" => "forms",
                'label' => "app.registration.address",
                'required' => true,
                "label_attr" => [
                    'class' => "font-semibold"
                ],
                "attr" => [
                    "placeholder" => "app.registration.address"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('This value should not be blank.', [], 'validators'),
                    ])
                ]
            ])
            ->add('phoneNo', TelType::class, [
                "translation_domain" => "forms",
                'label' => "app.registration.phone_no",
                'required' => true,
                "label_attr" => [
                    'class' => "font-semibold"
                ],
                "attr" => [
                    "placeholder" => "app.registration.phone_no"
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
            'data_class' => User::class,
        ]);
    }
}
