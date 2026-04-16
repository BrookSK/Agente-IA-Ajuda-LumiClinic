<?php

namespace App\Helpers;

use App\Models\Setting;

class ThemeHelper
{
    private static ?array $colors = null;

    /**
     * Carrega as cores do tema das configurações
     */
    private static function loadColors(): void
    {
        if (self::$colors === null) {
            try {
                self::$colors = [
                    'primary' => Setting::get('theme_color_primary', '#e53935'),
                    'secondary' => Setting::get('theme_color_secondary', '#ff6f60'),
                    'accent' => Setting::get('theme_color_accent', '#2ecc71'),
                    'background' => Setting::get('theme_color_background', '#050509'),
                ];
            } catch (\Exception $e) {
                // Fallback para valores padrão em caso de erro
                self::$colors = [
                    'primary' => '#e53935',
                    'secondary' => '#ff6f60',
                    'accent' => '#2ecc71',
                    'background' => '#050509',
                ];
            }
        }
    }

    /**
     * Retorna uma cor específica do tema
     */
    public static function getColor(string $key): string
    {
        self::loadColors();
        return self::$colors[$key] ?? '#e53935';
    }

    /**
     * Retorna o gradiente principal do tema
     */
    public static function getGradient(): string
    {
        self::loadColors();
        return "linear-gradient(135deg, " . self::$colors['primary'] . ", " . self::$colors['secondary'] . ")";
    }

    /**
     * Retorna todas as cores do tema
     */
    public static function getAllColors(): array
    {
        self::loadColors();
        return self::$colors;
    }

    /**
     * Limpa o cache das cores
     */
    public static function clearCache(): void
    {
        self::$colors = null;
    }
}