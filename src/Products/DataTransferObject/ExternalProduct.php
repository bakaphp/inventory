<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\DataTransferObject;

use Kanvas\Inventory\Enums\State;
use Phalcon\Utils\Slug;
use RuntimeException;

class ExternalProduct
{
    public string $name;
    public ?string $description = null;
    public string $slug;
    public int $isPublished = State::PUBLISHED;
    public int $position = 0;
    public int $isDefault = State::IS_DEFAULT;
    public string $sku;
    public float $price;
    public int $quantity = 0;
    public array $categories = [];
    public array $images = [];
    public array $productAttributes = [];
    public array $variantsAttributes = [];

    /**
     * Create a new ProductImport instance.
     *
     * @param array $data
     *
     * @return self
     */
    public function fromArray(array $data) : self
    {
        $this->validate('handler', $data);
        $this->validate('categories', $data);
        $this->validate('sku', $data);
        $this->validate('price', $data);
        $this->validate('variants_attributes', $data);

        $product = new self();
        $product->name = $data['name'];
        $product->description = $data['description'] ?? '';
        $product->slug = $data['handler'] ?? $data['slug'] ?? Slug::generate($data['name']);
        $product->isPublished = $data['is_published'] ?? State::PUBLISHED;
        $product->position = $data['position'];
        $product->isDefault = $data['is_default'] ?? State::IS_DEFAULT;
        $product->sku = $data['sku'];
        $product->price = $data['price'];
        $product->quantity = $data['quantity'] ?? 0;
        $product->categories = $data['categories'];
        $product->images = $data['images'];
        $product->productAttributes = $data['product_attributes'];
        $product->variantsAttributes = $data['variants_attributes'];

        return $product;
    }

    /**
     * Validate if specific key exist.
     *
     * @param string $key
     * @param array $entity
     *
     * @throws RuntimeException
     *
     * @return void
     */
    protected function validate(string $key, array $entity) : void
    {
        if (!isset($entity[$key])) {
            throw new RuntimeException('Product Import must have a ' . $key);
        }
    }
}
