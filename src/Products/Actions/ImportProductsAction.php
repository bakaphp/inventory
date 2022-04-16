<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Actions;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Models\Apps;
use Canvas\Models\Companies;
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
     *  'images' => [
     *     'image_url' => '',
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

            try {
                $productVariant = ProductVariantRepository::getBySku($externalProduct->sku, $this->user);
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
                    ]
                );
            }

            $productVariant->attribute()->addMultiple($variantsAttributes);
            $productVariant->warehouse()->add(
                $warehouse,
                $externalProduct->quantity,
                $externalProduct->price,
                $externalProduct->sku,
                [
                    'is_published' => $externalProduct->isPublished,
                ]
            );

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
}
