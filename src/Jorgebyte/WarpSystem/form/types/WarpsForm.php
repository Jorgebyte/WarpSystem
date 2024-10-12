<?php

declare(strict_types=1);

/*
 *    WarpSystem
 *    Api: 5.0.0
 *    Version: 1.0.0
 *    Author: Jorgebyte
 */

namespace Jorgebyte\WarpSystem\form\types;

use EasyUI\element\Button;
use EasyUI\icon\ButtonIcon;
use EasyUI\variant\SimpleForm;
use Jorgebyte\WarpSystem\Main;
use Jorgebyte\WarpSystem\util\PlayerUtil;
use pocketmine\player\Player;

class WarpsForm extends SimpleForm
{
    public function __construct()
    {
        parent::__construct("WarpSystem - Warps");
    }

    protected function onCreation(): void
    {
        $warpManager = Main::getInstance()->getWarpManager();
        $warps = $warpManager->getAllWarpNames();

        foreach ($warps as $warpName) {
            $warp = $warpManager->getWarp($warpName);
            if ($warp !== null) {
                $playerCountLabel = PlayerUtil::getPlayerCountInWorld($warp->getWorldName());

                $buttonLabel = $warp->getPrefix() . "\n" . $playerCountLabel;
                $button = new Button($buttonLabel);
                $icon = $warp->getIcon();

                if ($icon !== null) {
                    $button->setIcon(new ButtonIcon($icon));
                }

                $button->setSubmitListener(function (Player $player) use ($warpManager, $warpName) {
                    $warpManager->teleportToWarp($player, $warpName);
                });

                $this->addButton($button);
            }
        }
    }
}
