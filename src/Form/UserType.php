<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                "label" => "app.profile.first_name"
            ])
            ->add('lastName', TextType::class, [
                "label" => "app.profile.last_name"
            ])
            ->add('address', TextType::class, [
                "label" => "app.profile.address"
            ])
            ->add('phoneNo', TelType::class, [
                "label" => "app.profile.phone_no"
            ])
            ->add('username', TextType::class, [
                "label" => "app.profile.username"
            ])
            ->add('email', EmailType::class, [
                "label" => "app.profile.email"
            ])
            ->add('avatar', FileType::class, [
                'required' => false,
                'label' => "app.profile.upload_avatar_btn",
                'label_attr' => [
                    'class' => 'bg-gray-300 cursor-pointer text-black font-semibold mt-5 px-4 py-2 hover:bg-gray-400 ',
                    'id' => 'upload_avatar',
                    'name' => 'avatar'
                ],
                'mapped' => false,
                'data_class' => null,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => "forms"
        ]);
    }
}
