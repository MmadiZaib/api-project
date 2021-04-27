<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProductCrudController extends AbstractCrudController
{
    private string $productUploadImageDir;

    public function __construct(string $productUploadImageDir)
    {

        $this->productUploadImageDir = $productUploadImageDir;
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextEditorField::new('description'),
            Field::new('imageFile')->setFormType(VichFileType::class)->onlyOnForms()
                ->setTranslationParameters(['form.label.delete'=>'Delete']),
            ImageField::new('image')->onlyOnIndex()->setBasePath($this->productUploadImageDir),
            ImageField::new('image')->onlyOnDetail()->setBasePath($this->productUploadImageDir),
            AssociationField::new('offers')->setFormTypeOptions([
                'by_reference' => false,
            ])->autocomplete()
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', '%entity_label_plural% listing')
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ;
    }
}
