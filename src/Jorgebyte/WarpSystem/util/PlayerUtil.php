<?php

declare(strict_types=1);

/*
 *    WarpSystem
 *    Api: 5.0.0
 *    Version: 1.0.0
 *    Author: Jorgebyte
 */

namespace Jorgebyte\WarpSystem\util;

use Jorgebyte\WarpSystem\Main;
use pocketmine\Server;

class PlayerUtil
{
    public static function getPlayerCountInWorld(string $worldName): string
    {
        $world = Server::getInstance()->getWorldManager()->getWorldByName($worldName);

        if ($world === null) {
            return "0";
        }

        $playerCount = count($world->getPlayers());
        return self::formatPlayerCount($playerCount);
    }

    private static function formatPlayerCount(int $count): string
    {
        $popularThreshold = Main::getInstance()->getConfig()->get("warp-popular-threshold", 10);

        if ($count >= $popularThreshold) {
            return $count . " " . Main::getInstance()->getConfig()->get("warp-popular-players", "(Popular)");
        }

        return "Players: " . $count;
    }
}
