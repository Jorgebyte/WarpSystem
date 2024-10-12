<?php

declare(strict_types=1);

/*
 *    WarpSystem
 *    Api: 5.0.0
 *    Version: 1.0.0
 *    Author: Jorgebyte
 */

namespace Jorgebyte\WarpSystem\form;

use EasyUI\Form;
use InvalidArgumentException;
use Jorgebyte\WarpSystem\form\types\{
    WarpCreateForm,
    WarpDeleteForm,
    WarpEditForm,
    WarpsForm,
    subform\ConfirmationModal
};
use Jorgebyte\WarpSystem\util\Sound;
use Jorgebyte\WarpSystem\util\SoundNames;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Throwable;

class FormManager
{
    private static array $formMap = [
        'warps' => WarpsForm::class,
        'warpcreate' => WarpCreateForm::class,
        'warpdelete' => WarpDeleteForm::class,
        'warpedit' => WarpEditForm::class,
        // subforms
        'confirmdelete' => ConfirmationModal::class,
    ];

    private static function sendFormWithSound(Player $player, Form $form): void
    {
        Sound::addSound($player, SoundNames::OPEN_FORM);
        $player->sendForm($form);
    }

    public static function sendForm(Player $player, string $formType, array $args = []): void
    {
        if (!isset(self::$formMap[$formType])) {
            throw new InvalidArgumentException("ERROR: Form type " . $formType . " is not recognized");
        }

        $formClass = self::$formMap[$formType];
        if (!is_subclass_of($formClass, Form::class)) {
            throw new InvalidArgumentException("ERROR: The class " . $formClass . " is not a valid form type");
        }

        try {
            $form = new $formClass(...$args);
            self::sendFormWithSound($player, $form);

        } catch (Throwable $e) {
            $player->sendMessage(TextFormat::RED . "ERROR: creating form: " . $e->getMessage());
        }
    }
}
