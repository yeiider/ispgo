<?php

namespace Ispgo\SettingsManager\App\SettingsManager;

class SettingsLoader
{
    /**
     * Load settings from the configuration file.
     *
     * @return array
     */
    public static function loadSettings(): array
    {
        return config('settings');
    }

    /**
     * Get settings organized by sections and groups.
     *
     * @return array
     */
    public static function getOrganizedSettings(): array
    {
        $settings = self::loadSettings();
        $organizedSettings = [];

        foreach ($settings as $section => $sectionConfig) {
            $organizedSettings[$section] = [
                'label' => $sectionConfig['setting']['label'],
                'class' => $sectionConfig['setting']['class'],
                'groups' => []
            ];

            foreach ($sectionConfig as $group => $groupConfig) {
                if ($group !== 'setting') {
                    $organizedSettings[$section]['groups'][$group] = [];

                    foreach ($groupConfig as $field => $fieldConfig) {
                        $organizedSettings[$section]['groups'][$group][$field] = $fieldConfig;
                    }
                }
            }
        }

        return $organizedSettings;
    }

    /**
     * Get settings for a specific section.
     *
     * @param string $section
     * @return array
     */
    public static function getSectionSettings($section)
    {
        $settings = self::loadSettings();
        return $settings[$section] ?? [];
    }

    /**
     * Get the menu structure for settings.
     *
     * @return array
     */
    public static function getSettingsMenu()
    {
        $settings = self::loadSettings();
        $menu = [];

        foreach ($settings as $section => $sectionConfig) {
            $menu[] = [
                'label' => __($sectionConfig['setting']['label']),
                'class' => $sectionConfig['setting']['class'],
                'code' => $sectionConfig['setting']['code'] ?? $section,
            ];
        }

        return $menu;
    }
}
