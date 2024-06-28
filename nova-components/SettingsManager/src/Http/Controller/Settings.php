<?php

namespace Ispgo\SettingsManager\Http\Controller;

use App\Models\Setting;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Ispgo\SettingsManager\Source\Config\CompanyType;
use Illuminate\Support\Facades\Log;
use Exception;

class Settings extends Resource
{
    public function settings($request): array
    {
        $params = $request->section ?? 'general';
        $key = "system.{$params}";
        $generalConfig = config('system');
        $sectionsConfig = config($key);
        $menusConfig = array_keys($generalConfig);
        $menu = [];
        foreach ($menusConfig as $menuConfig) {
            $menu[$menuConfig] = $generalConfig[$menuConfig]["setting"];
        }
        return compact('sectionsConfig', 'menu', 'params');
    }

    public function fields(NovaRequest $request): array
    {
        $settingsData = $this->settings($request);
        $sectionsConfig = $settingsData['sectionsConfig'];
        $menu = $settingsData['menu'];
        $params = $settingsData['params'];
        $fields = [];

        // Obtener todos los settings de la base de datos para la sección actual
        $existingSettings = Setting::where('section', $params)->get()->keyBy(function ($item) {
            return $item['group'] . '.' . $item['key'];
        });

        foreach ($sectionsConfig as $groupKey => $group) {
            if ($groupKey === 'setting') {
                continue;
            }
            foreach ($group as $fieldKey => $field) {
                if ($fieldKey === 'setting') {
                    continue;
                }

                $fieldLabel = $field['label'] ?? ucfirst($fieldKey);
                $fieldPlaceholder = $field['placeholder'] ?? '';
                $fieldType = $field['field'] ?? 'text-field';

                // Construir la clave compuesta por el grupo y la clave del campo
                $settingKey = $groupKey . '.' . $fieldKey;

                // Obtener el valor del setting existente si está presente
                $fieldValue = $existingSettings[$settingKey]->value ?? null;

                switch ($fieldType) {
                    case 'boolean-field':
                        $fieldInstance = Boolean::make($fieldLabel, $fieldKey);
                        break;
                    case 'select-field':
                        $fieldInstance = Select::make($fieldLabel, $fieldKey)->options(
                            array_column($field['options'], 'value', 'label')
                        );
                        break;
                    case 'textarea-field':
                        $fieldInstance = Textarea::make($fieldLabel, $fieldKey);
                        break;
                    case 'url-field':
                        $fieldInstance = URL::make($fieldLabel, $fieldKey);
                        break;
                    case 'email-field':
                        $fieldInstance = Email::make($fieldLabel, $fieldKey);
                        break;
                    case 'country-field':
                        $fieldInstance = Country::make($fieldLabel, $fieldKey);
                        break;
                    case 'password-field':
                        $fieldInstance = Password::make($fieldLabel, $fieldKey);
                        break;
                    case 'datatime-field':
                        $fieldInstance = DateTime::make($fieldLabel, $fieldKey);
                        break;
                    case 'image-field':
                        $fieldInstance = Image::make($fieldLabel, $fieldKey);
                        break;
                    case 'text-field':
                    default:
                        $fieldInstance = Text::make($fieldLabel, $fieldKey);
                        break;
                }

                if ($fieldPlaceholder) {
                    $fieldInstance->placeholder($fieldPlaceholder);
                }
                $fieldInstance->withMeta(["group" => $group['setting']['code'],"value" =>$fieldValue,  "group_label" => $group['setting']['label']]);
                $fields[] = $fieldInstance;
            }
        }
        return compact('fields', 'sectionsConfig', 'menu');
    }

    public function saveSetting(NovaRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $fields = $request->fields;
            $section = $request->section;
            $parseData = $this->parseData($fields, $section);

            foreach ($parseData as $group) {
                Setting::updateOrCreate(
                    ['section' => $section, 'group' => $group['group'], 'key' => $group['key']],
                    ['value' => $group['value']]
                );
            }

            return response()->json(['success' => true, 'message' => 'Settings saved successfully.']);
        } catch (Exception $e) {
            Log::error('Error saving settings: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while saving the settings.']);
        }
    }

    private function parseData(mixed $fields, string $section): array
    {
        $settings = [];
        foreach ($fields as $field) {
            $settings[] = [
                "section" => $section,
                "group" => $field["group"],
                "key" => $field["attribute"],
                "value" => $field["value"],
            ];
        }
        return $settings;
    }
}
