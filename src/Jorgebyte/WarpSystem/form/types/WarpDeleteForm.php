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
use EasyUI\variant\SimpleForm;
use Jorgebyte\WarpSystem\form\FormManager;
use Jorgebyte\WarpSystem\Main;
use pocketmine\player\Player;

class WarpDeleteForm extends SimpleForm
{
    public function __construct()
    {
        parent::__construct("Select Warp to Delete");
    }

    protected function onCreation(): void
    {
        $warpManager = Main::getInstance()->getWarpManager();
        $warps = $warpManager->getAllWarpNames();

        foreach ($warps as $warpName) {
            $warp = $warpManager->getWarp($warpName);
            if ($warp !== null) {
                $button = new Button($warp->getName());
                $button->setSubmitListener(function (Player $player) use ($warpName) {
                    FormManager::sendForm($player, 'confirmdelete', [$warpName]);
                });
                $this->addButton($button);
            }
        }
    }
}
