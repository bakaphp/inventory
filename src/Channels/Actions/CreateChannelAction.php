<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Channels\Actions;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Enums\App;
use Kanvas\Inventory\Channels\Models\Channels as ModelsChannels;
use Kanvas\Inventory\Enums\State;

class CreateChannelActions
{
    /**
     * Create new Channel.
     *
     * @param UserInterface $user
     * @param string $name
     * @param array $options
     *
     * @return ModelsChannels
     */
    public static function execute(UserInterface $user, string $name, array $options) : ModelsChannels
    {
        $channel = new ModelsChannels();
        $channel->name = $name;
        $channel->users_id = $user->getId();
        $channel->apps_id = App::GLOBAL_APP_ID;
        $channel->companies_id = $user->currentCompanyId();
        $channel->is_published = $options['is_published'] ?? State::PUBLISHED;
        $channel->is_default = $options['is_default'] ?? State::IS_DEFAULT;
        $channel->slug = $options['slug'] ?? '';
        $channel->saveOrFail();

        return $channel;
    }
}
