<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new("name", 'app.entities.product.name'))
            ->add(TextFilter::new("brand", 'app.entities.product.brand'))
            ->add(TextFilter::new("model", 'app.entities.product.model'))
            ->add(TextFilter::new("price", 'app.entities.product.price'))
            ->add(TextFilter::new("size", 'app.entities.product.size'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('app.entities.product.entity_title')
            ->setEntityLabelInPlural('app.entities.product.entity_title');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'app.entities.common.id')
                ->onlyOnIndex(),
            TextField::new("name", 'app.entities.product.name'),
            TextField::new("brand", 'app.entities.product.brand'),
            TextField::new("model", 'app.entities.product.model'),
            AssociationField::new("discount", 'app.entities.product.discount'),
            NumberField::new("price", 'app.entities.product.price'),
            ChoiceField::new("color", 'app.entities.product.color')
                ->setChoices([
                    "Blue" => "blue",
                    "Black" => "black",
                    "Red" => "red",
                    "Green" => "green",
                    "White" => "white",
                    "Purple" => "purple",
                ])
            ,
            Field::new("rate", 'app.entities.product.rate')
                ->setFormTypeOptions([
                    'attr' => [
                        "max" => 5
                    ]
                ])
            ,
            Field::new("size", 'app.entities.product.size')
                ->setFormTypeOptions([
                    'attr' => [
                        "max" => 10
                    ]
                ])
            ,
            Field::new('quantity', "app.entities.product.quantity"),

            TextareaField::new("briefDescription", 'app.entities.product.brief_description'),
            TextareaField::new("description", 'app.entities.product.description')
                ->hideOnIndex(),
            TextareaField::new("imageFile", 'app.entities.product.image_file')
                ->hideOnIndex()
                ->setFormType(VichImageType::class)
                ->setTranslationParameters(['form.label.delete' => 'Delete'])
                ->setFormTypeOption('allow_delete', false)
                ->hideOnDetail()
                ->hideOnIndex()
            ,
            Field::new('views', "app.entities.product.views")
                ->setFormTypeOptions(["disabled" => true])
            ,
            ImageField::new('image', "app.entities.product.image_preview")
                ->setBasePath('/uploads/products/')
                ->setCssClass('admin-product-preview')
                ->hideOnForm()
            ,
            Field::new("image", 'app.entities.product.image')
                ->hideOnIndex()
                ->setFormTypeOptions(["disabled" => true]),
            DateTimeField::new("createdAt", "app.entities.common.created_at")
                ->setFormTypeOptions(["disabled" => true]),

            DateTimeField::new("updatedAt", "app.entities.common.updated_at")
                ->setFormTypeOptions(["disabled" => true]),

            Field::new("createdUser", "app.entities.common.created_user")
                ->setFormTypeOptions(["disabled" => true]),
            Field::new("updatedUser", "app.entities.common.updated_user")
                ->setFormTypeOptions(["disabled" => true]),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addWebpackEncoreEntry('adminStyles')
            ->addCssFile('build/adminStyles.css');
    }
}
