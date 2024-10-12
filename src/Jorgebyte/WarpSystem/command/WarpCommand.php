<?php

declare(strict_types=1);

/*
 *    WarpSystem
 *    Api: 5.0.0
 *    Version: 1.0.0
 *    Author: Jorgebyte
 */

namespace Jorgebyte\WarpSystem\command;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use CortexPE\Commando\exception\ArgumentOrderException;
use Jorgebyte\WarpSystem\command\subcommands\CreateWarpCommand;
use Jorgebyte\WarpSystem\command\subcommands\DeleteWarpCommand;
use Jorgebyte\WarpSystem\command\subcommands\EditWarpCommand;
use Jorgebyte\WarpSystem\command\subcommands\ListWarpCommand;
use Jorgebyte\WarpSystem\form\FormManager;
use Jorgebyte\WarpSystem\Main;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class WarpCommand extends BaseCommand
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct($this->plugin, "warpsystem", "WarpSystem", ["warp"]);
        $this->setPermission("warpsystem.command");
    }

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->registerSubCommand(new CreateWarpCommand());
        $this->registerSubCommand(new DeleteWarpCommand());
        $this->registerSubCommand(new EditWarpCommand());
        $this->registerSubCommand(new ListWarpCommand());
        $this->registerArgument(0, new RawStringArgument("warpName", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (isset($args['warpName'])) {
            $warpName = $args['warpName'];
            /** @var Player $sender */
            $this->plugin->getWarpManager()->teleportToWarp($sender, $warpName);
        } else {
            /** @var Player $sender */
            FormManager::sendForm($sender, 'warps');
        }
    }
}
