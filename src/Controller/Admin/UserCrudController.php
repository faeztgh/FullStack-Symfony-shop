<?php

namespace App\Controller\Admin;


use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('app.entities.user.entity_title')
            ->setEntityLabelInPlural('app.entities.user.entity_title');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'app.entities.common.id')
                ->hideOnForm(),
            TextField::new('firstName', "app.entities.user.first_name"),
            TextField::new('lastName', "app.entities.user.last_name"),
            TextField::new('username', "app.entities.user.username"),
            TextField::new('email', "app.entities.user.email"),
            TextField::new('password', "app.entities.user.password")
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'first_options' => ['label' => 'app.entities.user.password'],
                    'second_options' => ['label' => 'app.entities.user.repeat_password'],
                ])->onlyWhenCreating(),
            ImageField::new('avatar', "app.entities.user.avatar")
                ->setBasePath('/build/avatars')
                ->setCssClass('admin-avatar-preview')
                ->hideOnIndex(),
            AssociationField::new('tickets', "app.entities.user.tickets")
                ->hideOnForm()
            ,
            AssociationField::new('wishlist', "app.entities.user.wishlist")
                ->hideOnForm()
            ,
            NumberField::new('credit', "app.entities.user.credit")
            ,
            DateTimeField::new("createdAt", "app.entities.common.created_at")
                ->hideOnForm(),
            DateTimeField::new("updatedAt", "app.entities.common.updated_at")
                ->hideOnForm(),

            ChoiceField::new('roles', 'app.entities.user.roles')
                ->setChoices([
                    "ROLE_ADMIN" => "ROLE_ADMIN",
                    "ROLE_USER" => "ROLE_USER",
                    "ROLE_SUPER_ADMIN" => "ROLE_SUPER_ADMIN"
                ])->allowMultipleChoices()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addWebpackEncoreEntry('adminStyles')
            ->addCssFile('build/adminStyles.css');
    }
}
