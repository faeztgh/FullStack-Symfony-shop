<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordRequestFormType extends AbstractType
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

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'translation_domain' => 'forms',
                "label" => "app.reset_password.email",
                'attr' => [
                    'autocomplete' => 'email',
                    "placeholder" => "app.reset_password.email"
                ],
                'label_attr' => [
                    'class' => "font-semibold"
                ],

                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('This value should not be blank.', [], 'validators'),
                    ]),
                    new Email([
                        'message' => $this->translator->trans('Please enter a valid email address.', [], 'validators'),
                    ])
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
