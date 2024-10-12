<?php

declare(strict_types=1);

/*
 *    WarpSystem
 *    Api: 5.0.0
 *    Version: 1.0.0
 *    Author: Jorgebyte
 */

namespace Jorgebyte\WarpSystem;

use CortexPE\Commando\PacketHooker;
use Jorgebyte\WarpSystem\command\WarpCommand;
use Jorgebyte\WarpSystem\warp\WarpManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase
{
    use SingletonTrait;

    public WarpManager $warpManager;

    public function onLoad(): void
    {
        self::setInstance($this);
    }

    public function onEnable(): void
    {
        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        $this->warpManager = new WarpManager($this->getDataFolder());
        $this->getServer()->getCommandMap()->register("WarpSystem", new WarpCommand($this));
    }

    public function getWarpManager(): WarpManager
    {
        return $this->warpManager;
    }
}
