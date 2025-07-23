<?php

namespace App\Enum;

enum TiposDeItems: string
{
    case PRODUCTO = 'P';
    case SERVICIO = 'S';

    public function getLabel(): string
    {
        return match($this) {
            self::PRODUCTO => 'Producto',
            self::SERVICIO => 'Servicio',
        };
    }

    public function getDescripcion(): string
    {
        return match($this) {
            self::PRODUCTO => 'Producto',
            self::SERVICIO => 'Servicio',
        };
    }

    public function getIcono(): string
    {
        return match($this) {
            self::PRODUCTO => 'fas fa-box',
            self::SERVICIO => 'fas fa-handshake',
        };
    }

    public function getCssClass(): string
    {
        return match($this) {
            self::PRODUCTO => 'badge bg-primary',
            self::SERVICIO => 'badge bg-success',
        };
    }

    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->getLabel(),
            'descripcion' => $case->getDescripcion(),
            'icono' => $case->getIcono(),
            'css_class' => $case->getCssClass()
        ], self::cases());
    }

    public static function getChoices(): array
    {
        $choices = [];

        foreach (self::cases() as $case) {
            $choices[$case->getLabel()] = $case->value;
        }

        return $choices;
    }

    public static function fromValue(?string $value): ?self
    {
        if ($value === null) {
            return null;
        }

        return array_find(self::cases(), fn($case) => $case->value === $value);
    }
}
