<?php

declare(strict_types=1);

/*
 *    WarpSystem
 *    Api: 5.0.0
 *    Version: 1.0.0
 *    Author: Jorgebyte
 */

namespace Jorgebyte\WarpSystem\warp;

use Exception;
use Jorgebyte\WarpSystem\util\Sound;
use Jorgebyte\WarpSystem\util\SoundNames;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;

class WarpManager
{
    /** @var array<string, Warp> */
    private array $warps = [];
    private string $dataFolder;

    public function __construct(string $dataFolder)
    {
        $this->dataFolder = $dataFolder . "warps/";
        if (!is_dir($this->dataFolder)) {
            mkdir($this->dataFolder, 0777, true);
        }
        $this->loadWarps();
    }

    public function loadWarps(): void
    {
        $warpFiles = glob($this->dataFolder . "*.json");
        if (is_array($warpFiles)) {
            foreach ($warpFiles as $warpFile) {
                $jsonContent = file_get_contents($warpFile);
                if ($jsonContent === false) {
                    continue;
                }
                $warpData = json_decode($jsonContent, true);
                if (!is_array($warpData)) {
                    continue;
                }

                $warp = Warp::fromArray($warpData);
                $this->warps[$warp->getName()] = $warp;
            }
        }
    }

    public function saveWarp(Warp $warp, string $oldName = null): void
    {
        if ($oldName !== null && $oldName !== $warp->getName()) {
            $oldFilePath = $this->dataFolder . $oldName . ".json";
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
        file_put_contents(
            $this->dataFolder . $warp->getName() . ".json",
            json_encode($warp->toArray(), JSON_PRETTY_PRINT)
        );
    }

    /**
     * @throws Exception
     */
    public function deleteWarp(string $name): void
    {
        if (!isset($this->warps[$name])) {
            throw new Exception("ERROR: The warp does not exist");
        }
        $warpFile = $this->dataFolder . $name . '.json';
        if (file_exists($warpFile)) {
            unlink($warpFile);
        }

        unset($this->warps[$name]);
    }

    /**
     * @throws Exception
     */
    public function createWarp(string $name, string $prefix, Position $position, ?string $permission, ?string $icon): void
    {
        if (isset($this->warps[$name])) {
            throw new Exception("ERROR: The warp already exists");
        }

        $warp = new Warp($name, $prefix, $position, $permission, $icon);
        $this->warps[$name] = $warp;
        $this->saveWarp($warp);
    }

    public function getWarp(string $name): ?Warp
    {
        return $this->warps[$name] ?? null;
    }

    /**
     * @throws Exception
     */
    public function updateWarp(string $originalName, string $newName, string $prefix, Position $position, ?string $permission, ?string $icon): void
    {
        if (!isset($this->warps[$originalName])) {
            throw new \Exception("ERROR: The warp does not exist");
        }

        $warp = $this->warps[$originalName];

        $warp->setName($newName);
        $warp->setPrefix($prefix);
        $warp->setPosition($position);
        $warp->setPermission($permission);
        $warp->setIcon($icon);

        if ($originalName !== $newName) {
            unset($this->warps[$originalName]);
            $this->warps[$newName] = $warp;
        }
        $this->saveWarp($warp, $originalName);
    }

    public function teleportToWarp(Player $player, string $name): void
    {
        $warp = $this->getWarp($name);

        if ($warp === null) {
            $player->sendMessage(TextFormat::RED . "ERROR: The warp " . $name . " does not exist");
            Sound::addSound($player, SoundNames::BAD_TONE);
            return;
        }

        if (!$warp->canUseWarp($player)) {
            $player->sendMessage(TextFormat::RED . "ERROR: You do not have permission to use this warp");
            Sound::addSound($player, SoundNames::BAD_TONE);
            return;
        }

        $world = $warp->getPosition()->getWorld();

        if (!$world->isLoaded()) {
            Server::getInstance()->getWorldManager()->loadWorld($world->getFolderName());
            $player->sendMessage(TextFormat::GREEN . "The world " . $world->getFolderName() . " has been loaded.");
        }

        $player->teleport($warp->getPosition());
        $player->sendMessage(TextFormat::GREEN . "You have teleported to the warp: " . $warp->getName());
        Sound::addSound($player, SoundNames::GOOD_TONE);
    }

    /**
     * @return string[]
     */
    public function getAllWarpNames(): array
    {
        return array_keys($this->warps);
    }
}
