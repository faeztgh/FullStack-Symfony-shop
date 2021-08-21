<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TicketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ticket::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('app.entities.ticket.entity_title')
            ->setEntityLabelInPlural('app.entities.ticket.entity_title');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'app.entities.common.id')
                ->hideOnForm(),
            AssociationField::new('owner', "app.entities.ticket.owner")
                ->setFormTypeOption('disabled', true)
            ,
            TextField::new('subject', 'app.entities.ticket.subject')
                ->setFormTypeOption('disabled', true)
            ,
            TextareaField::new('message', 'app.entities.ticket.message')
                ->setFormTypeOption('disabled', true)
                ->hideOnIndex()
            ,
            TextareaField::new('answer', 'app.entities.ticket.answer')
                ->hideOnIndex()
            ,
            ChoiceField::new('status', 'app.entities.ticket.status')
                ->setChoices([
                    "SUBMITTED_STATUS" => "submitted",
                    "EDITED_STATUS" => "edited",
                    "IN_PROGRESS_STATUS" => "in_progress",
                    "DONE_STATUS" => "done",
                    "WAITING_STATUS" => "waiting"
                ]),

            DateTimeField::new("createdAt", "app.entities.common.created_at")
                ->setFormTypeOption('disabled', true),
            DateTimeField::new("updatedAt", "app.entities.common.updated_at")
                ->setFormTypeOption('disabled', true),
            Field::new("createdUser", "app.entities.common.created_user")
                ->setFormTypeOption('disabled', true),
            Field::new("updatedUser", "app.entities.common.updated_user")
                ->setFormTypeOption('disabled', true),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW);

    }
}
