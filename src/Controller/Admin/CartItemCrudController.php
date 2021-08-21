<?php

namespace App\Controller\Admin;

use App\Entity\CartItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class CartItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CartItem::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('app.entities.cart_item.entity_title')
            ->setEntityLabelInPlural('app.entities.cart_item.entity_title')
            ;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'app.entities.common.id')
                ->onlyOnIndex(),
            AssociationField::new("product", "app.entities.cart_item.product"),
            AssociationField::new("cart", "app.entities.cart_item.cart"),
            NumberField::new("totalPrice", "app.entities.cart_item.total_price"),
            NumberField::new("quantity", "app.entities.cart_item.quantity"),
            Field::new("status", "app.entities.cart_item.status"),
            DateTimeField::new("createdAt", "app.entities.common.created_at"),
            DateTimeField::new("updatedAt", "app.entities.common.updated_at"),
            Field::new("createdUser", "app.entities.common.created_user"),
            Field::new("updatedUser", "app.entities.common.updated_user"),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }
}
