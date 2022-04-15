<?php
declare(strict_types=1);

namespace Kanvas\Inventory;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Models\Companies;
use Canvas\Models\Currencies;
use Exception;
use Kanvas\Inventory\Categories\Actions\CreateCategoryAction;
use Kanvas\Inventory\Categories\CategoryRepository;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Channels\Actions\CreateChannelAction;
use Kanvas\Inventory\Channels\ChannelRepository;
use Kanvas\Inventory\Channels\Models\Channels as ChannelsModel;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Regions\Actions\CreateRegionAction;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Regions\RegionRepository;
use Kanvas\Inventory\Warehouses\Actions\CreateWarehouseAction;
use Kanvas\Inventory\Warehouses\Models\Warehouses;
use Kanvas\Inventory\Warehouses\WarehouseRepository;

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
            $defaultCategory = CategoryRepository::getBySlug(State::DEFAULT_NAME_SLUG, $this->user);
        } catch (Exception $e) {
            $defaultCategory = CreateCategoryAction::execute(
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
            $defaultChannel = ChannelRepository::getBySlug(State::DEFAULT_NAME_SLUG, $this->user);
        } catch (Exception $e) {
            $defaultChannel = CreateChannelAction::execute(
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
            $defaultRegion = RegionRepository::getBySlug(State::DEFAULT_NAME_SLUG, $this->user);
        } catch (Exception $e) {
            $currency = Currencies::findFirstOrFail('code = "USD"');
            $defaultRegion = CreateRegionAction::execute(
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
            $defaultWarehouse = WarehouseRepository::getBySlug(State::DEFAULT_NAME_SLUG, $this->user);
        } catch (Exception $e) {
            $defaultWarehouse = CreateWarehouseAction::execute(
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
