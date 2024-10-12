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
use Jorgebyte\WarpSystem\form\FormManager;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class CreateWarpCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct("create", "WarpSystem - Create warps");
        $this->setPermission("warpsystem.command.create");
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        /** @var Player $sender */
        FormManager::sendForm($sender, 'warpcreate');
    }
}
