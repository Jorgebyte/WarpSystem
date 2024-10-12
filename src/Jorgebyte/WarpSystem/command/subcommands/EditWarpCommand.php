<?php

declare(strict_types=1);

/*
 *    WarpSystem
 *    Api: 5.0.0
 *    Version: 1.0.0
 *    Author: Jorgebyte
 */

namespace Jorgebyte\WarpSystem\command\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use CortexPE\Commando\exception\ArgumentOrderException;
use Jorgebyte\WarpSystem\form\FormManager;
use Jorgebyte\WarpSystem\Main;
use Jorgebyte\WarpSystem\util\Sound;
use Jorgebyte\WarpSystem\util\SoundNames;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class EditWarpCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct("edit", "WarpSystem - Edit your warp");
        $this->setPermission("warpsystem.command.edit");
    }

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("warpName", true));
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (isset($args['warpName']) && $args['warpName'] !== '') {
            $warpName = $args['warpName'];
            if (Main::getInstance()->getWarpManager()->getWarp($warpName) === null) {
                $sender->sendMessage(TextFormat::RED . "ERROR: The warp does not exist");
                /** @var Player $sender */
                Sound::addSound($sender, SoundNames::BAD_TONE);
                return;
            }
            /** @var Player $sender */
            FormManager::sendForm($sender, 'warpedit', [$warpName]);
        } else {
            $sender->sendMessage(TextFormat::RED . "ERROR: You must provide a warp name");
            /** @var Player $sender */
            Sound::addSound($sender, SoundNames::BAD_TONE);
        }
    }
}
