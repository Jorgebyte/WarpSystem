<?php

declare(strict_types=1);

/*
 *    WarpSystem
 *    Api: 5.0.0
 *    Version: 1.0.0
 *    Author: Jorgebyte
 */

namespace Jorgebyte\WarpSystem\form\types;

use EasyUI\element\Input;
use EasyUI\utils\FormResponse;
use EasyUI\variant\CustomForm;
use Jorgebyte\WarpSystem\Main;
use Jorgebyte\WarpSystem\util\Sound;
use Jorgebyte\WarpSystem\util\SoundNames;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class WarpCreateForm extends CustomForm
{
    public function __construct()
    {
        parent::__construct("WarpSystem - Create Warp");
    }

    public function onCreation(): void
    {
        $this->addElement("warpName", new Input("Warp Name", null, "E.g. Lobby"));
        $this->addElement("prefix", new Input("Prefix", null, "E.g. [-Lobby-]"));
        $this->addElement("permission", new Input("Permissions (optional)", null, "E.g. lobby.use"));
        $this->addElement("icon", new Input("Icon (optional)", null, "Url"));
    }

    public function onSubmit(Player $player, FormResponse $response): void
    {

        $warpName = $response->getInputSubmittedText("warpName");
        $prefix = $response->getInputSubmittedText("prefix");
        $permission = $response->getInputSubmittedText("permission");
        $icon = $response->getInputSubmittedText("icon");

        if ($warpName === '' || $prefix === '') {
            $player->sendMessage(TextFormat::RED . "ERROR: The name and prefix are REQUIRED");
            Sound::addSound($player, SoundNames::BAD_TONE);
            return;
        }

        $position = $player->getPosition();

        try {
            Main::getInstance()->getWarpManager()->createWarp($warpName, $prefix, $position, $permission, $icon);
            $player->sendMessage(TextFormat::GREEN . "Warp: " . $warpName . " Created successfully");
            Sound::addSound($player, SoundNames::GOOD_TONE);
        } catch (\Exception $e) {
            $player->sendMessage(TextFormat::RED . $e->getMessage());
            Sound::addSound($player, SoundNames::BAD_TONE);
        }
    }
}
