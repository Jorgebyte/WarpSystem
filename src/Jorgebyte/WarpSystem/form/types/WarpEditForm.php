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
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;

class WarpEditForm extends CustomForm
{
    private string $warpName;

    public function __construct(string $warpName)
    {
        $this->warpName = $warpName;
        parent::__construct("WarpSystem - Edit Warp");
    }

    public function onCreation(): void
    {
        $warp = Main::getInstance()->getWarpManager()->getWarp($this->warpName);
        if ($warp === null) {
            return;
        }

        $this->addElement("warpName", new Input("Warp Name", $warp->getName()));
        $this->addElement("prefix", new Input("Prefix", $warp->getPrefix()));
        $this->addElement("permission", new Input("Permissions (optional)", $warp->getPermission() ?? "", "Current: " . ($warp->getPermission() ?? "None")));
        $this->addElement("icon", new Input("Icon (optional)", $warp->getIcon() ?? "", "Current: " . ($warp->getIcon() ?? "None")));
        $position = $warp->getPosition();
        $world = $position->getWorld()->getFolderName();
        $this->addElement("x", new Input("X Position", (string) $position->getX(), (string) $position->getX()));
        $this->addElement("y", new Input("Y Position", (string) $position->getY(), (string) $position->getY()));
        $this->addElement("z", new Input("Z Position", (string) $position->getZ(), (string) $position->getZ()));
        $this->addElement("world", new Input("World", $world, $world));
    }

    public function onSubmit(Player $player, FormResponse $response): void
    {
        $warpName = $response->getInputSubmittedText("warpName");
        $prefix = $response->getInputSubmittedText("prefix");
        $permission = $response->getInputSubmittedText("permission");
        $icon = $response->getInputSubmittedText("icon");
        $x = (float) $response->getInputSubmittedText("x");
        $y = (float) $response->getInputSubmittedText("y");
        $z = (float) $response->getInputSubmittedText("z");
        $worldName = $response->getInputSubmittedText("world");
        $world = Server::getInstance()->getWorldManager()->getWorldByName($worldName);

        if ($world === null) {
            $player->sendMessage(TextFormat::RED . "ERROR: The specified world does not exist.");
            return;
        }

        $position = new Position($x, $y, $z, $world);

        if ($warpName === '' || $prefix === '') {
            $player->sendMessage(TextFormat::RED . "ERROR: The name and prefix are REQUIRED");
            Sound::addSound($player, SoundNames::BAD_TONE);
            return;
        }

        try {
            Main::getInstance()->getWarpManager()->updateWarp(
                $this->warpName,
                $warpName, // new name
                $prefix,
                $position, // new position
                $permission,
                $icon
            );
            $player->sendMessage(TextFormat::GREEN . "Warp: " . $warpName . " Updated successfully");
            Sound::addSound($player, SoundNames::GOOD_TONE);
        } catch (\Exception $e) {
            $player->sendMessage(TextFormat::RED . $e->getMessage());
            Sound::addSound($player, SoundNames::BAD_TONE);
        }
    }
}
