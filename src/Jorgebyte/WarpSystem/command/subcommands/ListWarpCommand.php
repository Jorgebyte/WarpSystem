<?php

declare(strict_types=1);

/*
 *    WarpSystem
 *    Api: 5.0.0
 *    Version: 1.0.0
 *    Author: Jorgebyte
 */

namespace Jorgebyte\WarpSystem\command\subcommands;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\WarpSystem\Main;
use Jorgebyte\WarpSystem\util\Sound;
use Jorgebyte\WarpSystem\util\SoundNames;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class ListWarpCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct("list", "WarpSystem - Displays the names of the warps");
        $this->setPermission("warpsystem.command");
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $warpNames = Main::getInstance()->getWarpManager()->getAllWarpNames();

        if (count($warpNames) === 0) {
            $sender->sendMessage(TextFormat::RED . "No warps found");
            /** @var Player $sender */
            Sound::addSound($sender, SoundNames::BAD_TONE);
            return;
        }
        $message = TextFormat::YELLOW . "Warp List:\n";

        foreach ($warpNames as $index => $warpName) {
            $message .= TextFormat::GOLD . ($index + 1) . ". " . TextFormat::WHITE . $warpName . "\n";
        }

        $sender->sendMessage($message);
        /** @var Player $sender */
        Sound::addSound($sender, SoundNames::GOOD_TONE);
    }
}
