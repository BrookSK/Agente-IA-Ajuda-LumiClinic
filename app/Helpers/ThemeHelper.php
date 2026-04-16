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
                    'surface' => Setting::get('theme_color_surface', '#111118'),
                    'text' => Setting::get('theme_color_text', '#f5f5f5'),
                    'text_secondary' => Setting::get('theme_color_text_secondary', '#b0b0b0'),
                    'button_background' => Setting::get('theme_button_background', '#e53935'),
                    'button_text' => Setting::get('theme_button_text', '#ffffff'),
                    'button_border' => Setting::get('theme_button_border', 'transparent'),
                    'headline' => Setting::get('theme_headline_color', '#ffffff'),
                ];
            } catch (\Exception $e) {
                // Fallback para valores padrão em caso de erro
                self::$colors = [
                    'primary' => '#e53935',
                    'secondary' => '#ff6f60',
                    'accent' => '#2ecc71',
                    'background' => '#050509',
                    'surface' => '#111118',
                    'text' => '#f5f5f5',
                    'text_secondary' => '#b0b0b0',
                    'button_background' => '#e53935',
                    'button_text' => '#ffffff',
                    'button_border' => 'transparent',
                    'headline' => '#ffffff',
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
     * Retorna o gradiente do botão baseado na configuração
     */
    public static function getButtonGradient(): string
    {
        self::loadColors();
        $buttonType = Setting::get('theme_button_background_type', 'gradient');
        
        if ($buttonType === 'solid') {
            return self::$colors['button_background'];
        }
        
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
     * Retorna cores específicas para uso comum
     */
    public static function getPrimary(): string
    {
        return self::getColor('primary');
    }

    public static function getSecondary(): string
    {
        return self::getColor('secondary');
    }

    public static function getAccent(): string
    {
        return self::getColor('accent');
    }

    public static function getBackground(): string
    {
        return self::getColor('background');
    }

    public static function getSurface(): string
    {
        return self::getColor('surface');
    }

    public static function getText(): string
    {
        return self::getColor('text');
    }

    public static function getTextSecondary(): string
    {
        return self::getColor('text_secondary');
    }

    /**
     * Limpa o cache das cores
     */
    public static function clearCache(): void
    {
        self::$colors = null;
    }
}