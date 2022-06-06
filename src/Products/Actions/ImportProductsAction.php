<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Actions;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\Database\ModelInterface;
use Canvas\Models\Apps;
use Canvas\Models\Companies;
use Canvas\Models\FileSystem;
use Exception;
use Kanvas\Inventory\Attributes\Actions\CreateAttributeAction;
use Kanvas\Inventory\Attributes\Repositories\AttributeRepository;
use Kanvas\Inventory\Categories\Actions\CreateCategoryAction;
use Kanvas\Inventory\Categories\Repositories\CategoryRepository;
use Kanvas\Inventory\Contracts\ExportableInterface;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\DataTransferObject\ExternalProduct;
use Kanvas\Inventory\Products\Repositories\ProductRepository;
use Kanvas\Inventory\Variants\Actions\CreateProductVariantAction;
use Kanvas\Inventory\Variants\Repositories\ProductVariantRepository;
use Kanvas\Inventory\Warehouses\Models\Warehouses;
use RuntimeException;

class ImportProductsAction
{
    protected UserInterface $user;
    protected Companies $company;
    protected Apps $app;

    /**
     * Constructor.
     *
     * @param UserInterface $user
     * @param Companies $company
     * @param Apps $app
     *
     * @return void
     */
    public function __construct(UserInterface $user, Companies $company, Apps $app)
    {
        $this->user = $user;
        $this->company = $company;
        $this->app = $app;
    }

    /**
     * Given a import structure array , fill the database.
     *
     * [
     *  'name' => '',
     *  'description' => '',
     *  'handle/slug' => '', //how we group products
     *  'is_published' => '',
     *  'position' => 0,
     *  'is_default' => '',
     *  'sku' => '',
     *  'price' => '',
     *  'categories' => [
     *     'name' => '',
     *  ],
     *  'product_images' => [
     *     [
     *      'name' => '',
     *      'url' => '',
     *      'field_name' => '',
     *    ],
     *   ],
     *  'images' => [
     *     [
     *      'name' => '',
     *      'url' => '',
     *      'field_name' => '',
     *    ],
     *  ],
     *  'product_attributes' => [
     *    [
     *      'name' => 'value',
     *      'value' => 'value',
     *    ],
     *   ],
     *  'variants_attributes' => [
     *    'name' => 'value',
     *   ]
     * ].
     *
     * @param ExportableInterface $exportableEntities
     *
     * @return array<int, ProductsVariants>
     */
    public function execute(ExportableInterface $exportableEntities) : array
    {
        $warehouse = Warehouses::findFirstOrFail([
            'conditions' => 'is_default = :is_default:
                            AND companies_id = :companies_id:',
            'bind' => [
                'is_default' => State::DEFAULT,
                'companies_id' => $this->company->getId(),
            ],
        ]);

        $importedProducts = [];

        foreach ($exportableEntities->getAllEntities() as $externalProduct) {
            if (!$externalProduct instanceof ExternalProduct) {
                throw new RuntimeException('The product must be an instance of ExternalProduct');
            }

            $categories = $this->handleCategories($externalProduct->categories);
            $variantsAttributes = $this->handleAttributes($externalProduct->variantsAttributes);

            if (!empty($externalProduct->productAttributes)) {
                $productAttributes = $this->handleAttributes($externalProduct->productAttributes);
            }

            //verify if the product exists by sku if not create it?
            try {
                $product = ProductRepository::getBySlug($externalProduct->slug, $this->user);
            } catch (Exception $e) {
                $product = CreateProductAction::execute(
                    $this->user,
                    $externalProduct->name,
                    current($categories),
                    [
                        'slug' => $externalProduct->slug,
                        'is_published' => $externalProduct->isPublished,
                    ]
                );
            }

            if (count($categories) > 1) {
                $product->categories()->addMultiple($categories);
            }

            if (count($productAttributes) > 1) {
                $product->attributes()->addMultiple($productAttributes);
            }

            if (count($externalProduct->productImages)) {
                $this->handleImages($product, $externalProduct->productImages);
            }

            try {
                $productVariant = ProductVariantRepository::getBySku($externalProduct->sku, $product);
            } catch (Exception $e) {
                $productVariant = CreateProductVariantAction::execute(
                    $product,
                    $this->user,
                    $externalProduct->name,
                    $externalProduct->sku,
                    $externalProduct->description,
                    [
                        'is_default' => $externalProduct->isDefault,
                        'is_published' => $externalProduct->isPublished,
                        'slug' => $externalProduct->variantSlug,
                    ]
                );
            }

            if (count($externalProduct->variantImages)) {
                $this->handleImages($productVariant, $externalProduct->variantImages);
            }

            $productVariant->attribute()->addMultiple($variantsAttributes);

            try {
                $productVariant->warehouse()->add(
                    $warehouse,
                    $externalProduct->quantity,
                    $externalProduct->price,
                    $externalProduct->sku,
                    [
                        'is_published' => $externalProduct->isPublished,
                        'is_new' => $externalProduct->isNew,
                    ]
                );
            } catch (Exception $e) {
                //product already in warehouse
            }

            $importedProducts[] = $productVariant;
        }

        return $importedProducts;
    }

    /**
     * manage categories.
     *
     * @param array $categories
     *
     * @return array <int, ModelInterface>
     */
    protected function handleCategories(array $categories) : array
    {
        $categoriesModels = [];
        foreach ($categories as $name) {
            try {
                $categoriesModels[] = CategoryRepository::getByName($name, $this->user);
            } catch (Exception $e) {
                $categoriesModels[] = CreateCategoryAction::execute($this->user, $name);
            }
        }

        return $categoriesModels;
    }

    /**
     * Handle attributes.
     *
     * @param array $attributes
     *
     * @return array
     */
    protected function handleAttributes(array $attributes) : array
    {
        $attributesModels = [];
        foreach ($attributes as $attribute) {
            try {
                $attributesModels[] = [
                    'attribute' => AttributeRepository::getByName($attribute['name'], $this->user),
                    'value' => $attribute['value'],
                ];
            } catch (Exception $e) {
                $attributesModels[] = [
                    'attribute' => CreateAttributeAction::execute($this->user, $attribute['name']),
                    'value' => $attribute['value'],
                ];
            }
        }

        return $attributesModels;
    }

    /**
     * Handle product images.
     *
     * @param ModelInterface $entity
     * @param array $files
     *
     * @return void
     */
    protected function handleImages(ModelInterface $entity, array $files) : void
    {
        $i = 0;
        foreach ($files as $file) {
            $fileUrl = trim($file['url']);
            $fileName = trim($file['name']);
            $fieldName = $file['field_name'] ?? 'imported_image_' . $i;

            $fileSystem = FileSystem::findFirstOrCreate([
                'conditions' => 'url = :url:',
                'bind' => [
                    'url' => $fileUrl,
                ],
            ], [
                'companies_id' => $this->company->getId(),
                'apps_id' => $this->app->getId(),
                'url' => $fileUrl,
                'name' => $fileName,
                'path' => $fileUrl,
                'users_id' => $this->user->getId(),
                'file_type' => 'png',
                'size' => 0
            ]);

            $images = $entity->getAttachmentsByName($fileName);
            $fileSystemImage = $images->getLast();
            if (!isset($fileSystemImage->file)) {
                $entity->attach([
                    [
                        'id' => 0,
                        'field_name' => $fieldName,
                        'file' => $fileSystem
                    ]
                ]);
            }

            $i++;
        }
    }
}
