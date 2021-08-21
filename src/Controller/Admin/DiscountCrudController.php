<?php

namespace App\Controller\Admin;

use App\Entity\Discount;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DiscountCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Discount::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('app.entities.discount.entity_title')
            ->setEntityLabelInPlural('app.entities.discount.entity_title')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'app.entities.common.id')
                ->onlyOnIndex(),
            TextField::new("name","app.entities.discount.name"),
            TextareaField::new("description","app.entities.discount.description")
                ->hideOnIndex(),
            TextField::new("discountPercent","app.entities.discount.discount_percent"),
            DateTimeField::new("createdAt", "app.entities.common.created_at"),
            DateTimeField::new("updatedAt", "app.entities.common.updated_at"),
            Field::new("createdUser", "app.entities.common.created_user"),
            Field::new("updatedUser", "app.entities.common.updated_user"),
        ];
    }

}
