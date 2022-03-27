<?php
declare(strict_types=1);

namespace Kanvas\Inventory;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Models\Companies;
use Canvas\Models\Currencies;
use Exception;
use Kanvas\Inventory\Categories\Category;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Channels\Channel;
use Kanvas\Inventory\Channels\Models\Channels as ChannelsModel;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Regions\Region;
use Kanvas\Inventory\Warehouses\Models\Warehouses;
use Kanvas\Inventory\Warehouses\Warehouse;

class Setup
{
    protected Companies $company;
    protected UserInterface $user;

    /**
     * Construct.
     *
     * @param UserInterface $user
     * @param Companies $company
     */
    public function __construct(UserInterface $user, Companies $company)
    {
        $this->company = $company;
        $this->user = $user;
    }

    /**
     * Setup all the default inventory data for this current company.
     *
     * @return bool
     */
    public function run() : bool
    {
        try {
            $defaultCategory = Category::getBySlug(State::DEFAULT_NAME_SLUG, $this->user);
        } catch (Exception $e) {
            $defaultCategory = Category::create(
                $this->user,
                State::DEFAULT_NAME,
                [
                    'is_published' => State::PUBLISHED,
                    'position' => State::DEFAULT_POSITION,
                    'code' => State::DEFAULT_NAME_SLUG,
                    'slug' => State::DEFAULT_NAME_SLUG,
                    'is_default' => State::DEFAULT,
                ]
            );
        }

        try {
            $defaultChannel = Channel::getBySlug(State::DEFAULT_NAME_SLUG, $this->user);
        } catch (Exception $e) {
            $defaultChannel = Channel::create(
                $this->user,
                State::DEFAULT_NAME,
                [
                    'is_published' => State::PUBLISHED,
                    'position' => State::DEFAULT_POSITION,
                    'code' => State::DEFAULT_NAME_SLUG,
                    'slug' => State::DEFAULT_NAME_SLUG,
                    'is_default' => State::DEFAULT,
                ]
            );
        }

        try {
            $defaultRegion = Region::getBySlug(State::DEFAULT_NAME_SLUG, $this->user);
        } catch (Exception $e) {
            $currency = Currencies::findFirstOrFail('code = "USD"');
            $defaultRegion = Region::create(
                $this->user,
                State::DEFAULT_NAME,
                $currency,
                [
                    'is_published' => State::PUBLISHED,
                    'is_default' => State::DEFAULT,
                ]
            );
        }

        try {
            $defaultWarehouse = Warehouse::getBySlug(State::DEFAULT_NAME_SLUG, $this->user);
        } catch (Exception $e) {
            $defaultWarehouse = Warehouse::create(
                $this->user,
                State::DEFAULT_NAME,
                $defaultRegion,
                [
                    'is_published' => State::PUBLISHED,
                    'is_default' => State::DEFAULT,
                ]
            );
        }

        return $defaultCategory instanceof Categories
                && $defaultChannel instanceof ChannelsModel
                && $defaultRegion instanceof Regions
                && $defaultWarehouse instanceof Warehouses;
    }
}
