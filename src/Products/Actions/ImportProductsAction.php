<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Actions;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Models\Apps;
use Canvas\Models\Companies;
use Kanvas\Inventory\Contracts\ExportableInterface;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Products\Repositories\ProductRepository;
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

    public function execute(ExportableInterface $exportableEntities) : void
    {
        /**
         * [
         *  'name' => '',
         *  'description' => '',
         *  'handle/slug' => '', //how we group products
         *  'is_published' => '',
         *  'position' => 0,
         *  'is_default' => '',
         *  'sku' => '',
         *  'price' => '',
         *  'images' => [
         *     'image_url' => '',
         *  ],
         *  'product_attributes' => [
         *    'name' => 'value',
         *   ],
         *  'variants_attributes' => [
         *    'name' => 'value',
         *   ]
         * ].
         */
        foreach ($exportableEntities->getAllEntities() as $entity) {
            $this->validateEntity('handle', $entity);
            $this->validateEntity('sku', $entity);
            $this->validateEntity('price', $entity);
            $this->validateEntity('variants_attributes', $entity);

            //create category

            //verify if the product exists by sku if not create it?
            $product = ProductRepository::getBySlug($entity['handle'], $this->user);

            if (!$product) {
                //$product = CreateProductAction::execute($this->user, $entity['name']);
            }

            //verify if the attribute exists by name if not create it?
            //verify if the attribute value exists by name if not create it?
            //assign the attributes
            //assign price
        }
    }

    protected function validateEntity(string $key, array $entity)
    {
        if (!isset($entity['sku'])) {
            throw new RuntimeException('Product Import must have a ' . $key);
        }
    }
}
