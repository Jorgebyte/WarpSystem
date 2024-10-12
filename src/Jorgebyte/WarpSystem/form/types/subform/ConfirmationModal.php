<?php

declare(strict_types=1);

/*
 *    WarpSystem
 *    Api: 5.0.0
 *    Version: 1.0.0
 *    Author: Jorgebyte
 */

namespace Jorgebyte\WarpSystem\form\types\subform;

use EasyUI\variant\ModalForm;
use Exception;
use Jorgebyte\WarpSystem\Main;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class ConfirmationModal extends ModalForm
{
    private string $warpName;

    public function __construct(string $warpName)
    {
        $this->warpName = $warpName;
        parent::__construct("Confirm Deletion", "Are you sure you want to delete this Warp?");
    }

    protected function onAccept(Player $player): void
    {
        try {
            Main::getInstance()->getWarpManager()->deleteWarp($this->warpName);
        } catch (Exception $e) {
            $player->sendMessage(TextFormat::RED . $e->getMessage());
        }
        $player->sendMessage(TextFormat::GREEN . "The Warp has been removed");
    }

    protected function onDeny(Player $player): void
    {
        $player->sendMessage(TextFormat::RED . "Deletion canceled");
    }
}
