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
use Exception;
use Jorgebyte\WarpSystem\form\FormManager;
use Jorgebyte\WarpSystem\Main;
use Jorgebyte\WarpSystem\util\Sound;
use Jorgebyte\WarpSystem\util\SoundNames;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class DeleteWarpCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct("delete", "WarpSystem - Delete a warp");
        $this->setPermission("warpsystem.command.delete");
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
            try {
                Main::getInstance()->getWarpManager()->deleteWarp($warpName);
                $sender->sendMessage(TextFormat::GREEN . "Warp successfully deleted");
                /** @var Player $sender */
                Sound::addSound($sender, SoundNames::GOOD_TONE);
            } catch (Exception $e) {
                $sender->sendMessage(TextFormat::RED . $e->getMessage());
                /** @var Player $sender */
                Sound::addSound($sender, SoundNames::BAD_TONE);
            }
        } else {
            /** @var Player $sender */
            FormManager::sendForm($sender, 'warpdelete');
        }
    }
}
