<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, [
                'translation_domain' => 'forms',
                'label' => 'app.ticket.subject',
            ])
            ->add('message', TextareaType::class, [
                'translation_domain' => 'forms',
                'label' => 'app.ticket.message',
                'attr' => [
                    'rows' => "7"
                ]
            ]);
//            ->add('status', ChoiceType::class, [
//                'choices' => [
//                    "Waiting" => "waiting",
//                    "In Progress" => "in_progress",
//                    "Complete" => "complete"
//                ]
//            ])
//            ->add('answer');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
