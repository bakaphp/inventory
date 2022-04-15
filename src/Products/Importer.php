<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Models\Apps;
use Canvas\Models\Companies;
use Kanvas\Inventory\Contracts\ExportableInterface;

class Importer
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

    public function run(ExportableInterface $exportableEntities) : void
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

            //verify if the product exists by sky if not create it?
            //verify if the attribute exists by name if not create it?
            //verify if the attribute value exists by name if not create it?
            //assign the attributes
            //assign price
        }
    }
}
