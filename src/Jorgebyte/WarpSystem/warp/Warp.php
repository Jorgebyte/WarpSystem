<?php

declare(strict_types=1);

/*
 *    WarpSystem
 *    Api: 5.0.0
 *    Version: 1.0.0
 *    Author: Jorgebyte
 */

namespace Jorgebyte\WarpSystem\warp;

use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

class Warp
{
    private string $name;
    private string $prefix;
    private ?string $permission;
    private ?string $icon;
    private Position $position;

    public function __construct(
        string $name,
        string $prefix,
        Position $position,
        ?string $permission = null,
        ?string $icon = null
    ) {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->position = $position;
        $this->permission = $permission;
        $this->icon = $icon;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getWorldName(): string
    {
        return $this->position->getWorld()->getFolderName();
    }

    public function getPermission(): ?string
    {
        return $this->permission;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function setPosition(Position $position): void
    {
        $this->position = $position;
    }

    public function setPermission(?string $permission): void
    {
        $this->permission = $permission;
    }

    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'prefix' => $this->prefix,
            'position' => [
                'x' => $this->position->getX(),
                'y' => $this->position->getY(),
                'z' => $this->position->getZ(),
                'world' => $this->position->getWorld()->getFolderName(),
            ],
            'permission' => $this->permission,
            'icon' => $this->icon,
        ];
    }

    public static function fromArray(array $data): self
    {
        $world = Server::getInstance()->getWorldManager()->getWorldByName($data['position']['world']);
        return new self(
            $data['name'],
            $data['prefix'],
            new Position($data['position']['x'], $data['position']['y'], $data['position']['z'], $world),
            $data['permission'] ?? null,
            $data['icon'] ?? null
        );
    }

    public function canUseWarp(Player $player): bool
    {
        return $this->permission === null ||
            $player->hasPermission($this->permission) ||
            $player->hasPermission(DefaultPermissions::ROOT_OPERATOR);
    }
}
