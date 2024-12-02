<?php

namespace App\Domain\Ventilation\Enum;

use App\Domain\Common\Enum\Enum;

enum TypeSysteme: string implements Enum
{
    case VENTILATION_NATURELLE = 'VENTILATION_NATURELLE';
    case VMC_SIMPLE_FLUX = 'VMC_SIMPLE_FLUX';
    case VMC_SIMPLE_FLUX_GAZ = 'VMC_SIMPLE_FLUX_GAZ';
    case VMC_BASSE_PRESSION = 'VMC_BASSE_PRESSION';
    case VMC_DOUBLE_FLUX = 'VMC_DOUBLE_FLUX';
    case VENTILATION_HYBRIDE = 'VENTILATION_HYBRIDE';
    case VENTILATION_MECANIQUE = 'VENTILATION_MECANIQUE';
    case PUIT_CLIMATIQUE = 'PUIT_CLIMATIQUE';
    case VMI = 'VMI';
    case VMR = 'VMR';

    public static function from_enum_type_ventilation_id(int $id): self
    {
        return match ($id) {
            1, 2, 25, 34 => self::VENTILATION_NATURELLE,
            3, 4, 5, 6, 7, 8, 9, 13, 14, 15 => self::VMC_SIMPLE_FLUX,
            10, 11, 12 => self::VMC_SIMPLE_FLUX_GAZ,
            16, 17, 18 => self::VMC_BASSE_PRESSION,
            19, 20, 21, 22, 23, 24 => self::VMC_DOUBLE_FLUX,
            26, 27, 28, 29, 30, 31 => self::VENTILATION_HYBRIDE,
            32, 33 => self::VENTILATION_MECANIQUE,
            35, 36, 37, 38 => self::PUIT_CLIMATIQUE,
        };
    }

    public static function from_type_generateur(TypeGenerateur $type_generateur): self
    {
        return self::from($type_generateur->value);
    }

    public function id(): string
    {
        return $this->value;
    }

    public function lib(): string
    {
        return match ($this) {
            self::VENTILATION_NATURELLE => "Ventilation naturelle",
            self::VMC_SIMPLE_FLUX => "VMC Simple fluxe",
            self::VMC_SIMPLE_FLUX_GAZ => "VMC Simple flux Gaz",
            self::VMC_BASSE_PRESSION => "VMC Basse pression",
            self::VMC_DOUBLE_FLUX => "VMC Double flux",
            self::VENTILATION_HYBRIDE => "Ventilation hybride",
            self::PUIT_CLIMATIQUE => "Puits climatique",
            self::VMI => "Ventilation par insufflation",
            self::VMR => "Ventilation répartie",
        };
    }
}
