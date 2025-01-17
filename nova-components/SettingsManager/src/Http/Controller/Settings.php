<?php

namespace Ispgo\SettingsManager\Http\Controller;

use App\Models\CoreConfigData;
use App\Models\Router;
use Ispgo\Ckeditor\Ckeditor;
use Ispgo\SettingsManager\App\SettingsManager\SettingsLoader;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\Models\Scope;

class Settings extends Resource
{
    public function fields(NovaRequest $request): array
    {
        $params = $request->section ?? 'general';
        $sectionsConfig = SettingsLoader::getSectionSettings($params);

        // Obtener el menú de configuraciones
        $settingMenu = SettingsLoader::getSettingsMenu();

        // Obtener las configuraciones existentes
        $existingSettings = CoreConfigData::where('scope_id', $request->scope)->get()->keyBy(function ($item) {
            return $item['path'];
        });

        $groups = [];

        foreach ($sectionsConfig as $groupKey => $group) {
            if ($groupKey === 'setting') {
                continue;
            }

            $groupFields = [];

            foreach ($group as $fieldKey => $field) {
                if ($fieldKey === 'setting') {
                    continue;
                }

                $fieldLabel = $field['label'] ?? ucfirst($fieldKey);
                $fieldPlaceholder = $field['placeholder'] ?? '';
                $fieldType = $field['field'] ?? 'text-field';

                // Construir la clave completa: section/group/field
                $settingKey = "{$params}/{$groupKey}/{$fieldKey}";

                // Obtener el valor del setting existente si está presente
                $fieldValue = $existingSettings[$settingKey]->value ?? null;

                $fieldLabel = __($fieldLabel);

                switch ($fieldType) {
                    case 'boolean-field':
                        $fieldInstance = Boolean::make($fieldLabel, $fieldKey);
                        break;
                    case 'select-field':
                        $fieldInstance = Select::make($fieldLabel, $fieldKey)->options(
                            array_column($field['options']::getConfig(), 'label', 'value')
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
                    case 'datetime-field':
                        $fieldInstance = DateTime::make($fieldLabel, $fieldKey);
                        break;
                    case 'date-field':
                        $fieldInstance = Date::make($fieldLabel, $fieldKey);
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

                // Asignar el valor existente al campo
                if ($fieldValue !== null) {
                    $fieldInstance->withMeta(["value" => $fieldValue]);
                }

                $fieldInstance->withMeta([
                    'group' => $group['setting']['code'],
                    'group_label' => __($group['setting']['label'])
                ]);

                $groupFields[] = $fieldInstance;
            }

            $groups[] = [
                'label' => __($group['setting']['label']),
                'code' => $group['setting']['code'],
                'class' => $group['setting']['class'] ?? "",
                'fields' => $groupFields,
            ];
        }

        $actionsTitles = [
            'cancel' => __('Cancel'),
            'update_continue_editing' => __('Update & Continue Editing'),
            'update_setting' => __('Update Setting'),
        ];
        $heading = __('Settings Manager');
        $scopes = $this->getScopes();

        return compact('groups', 'settingMenu', 'actionsTitles', 'heading', 'scopes');
    }

    public function saveSetting(NovaRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $fields = $request->fields;
            $section = $request->section;
            $parseData = $this->parseData($fields, $section);

            foreach ($parseData as $group) {
                CoreConfigData::updateOrCreate(
                    ['path' => "{$section}/{$group['group']}/{$group['key']}", "scope_id"=> $request->scope],
                    ['value' => $group['value']],
                );
            }

            return response()->json(['success' => true, 'message' => __('Settings saved successfully.')]);
        } catch (Exception $e) {
            Log::error('Error saving settings: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => __('An error occurred while saving the settings.')]);
        }
    }

    private function parseData($fields, $section): array
    {
        $settings = [];

        foreach ($fields as $field) {
            $settings[] = [
                'section' => $section,
                'group' => $field['group'],
                'key' => $field['attribute'],
                'value' => $field['value'],
            ];
        }

        return $settings;
    }

    /**
     * Upload files to the server.
     *
     * @param NovaRequest $request The request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function uploadFiles(NovaRequest $request): \Illuminate\Http\JsonResponse
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');
            return response()->json([
                'success' => true,
                'url' => Storage::url($path)
            ]);
        }
        return response()->json([
            'success' => false,
            'error' => __('No se ha podido cargar la imagen.')
        ], 400);
    }

    /**
     * Deletes a file from the public/uploads directory.
     *
     * @param NovaRequest $request The NovaRequest instance.
     * @param string $file The name of the file to be deleted.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the result of the operation.
     */
    public function deleteFiles(NovaRequest $request, string $file): \Illuminate\Http\JsonResponse
    {
        $disk = Storage::disk('public');
        if (!$disk->exists('uploads/' . $file)) {
            return response()->json(['success' => false, 'error' => __('File not found.')]);
        }

        if (!$disk->delete('uploads/' . $file)) {
            return response()->json(['success' => false, 'error' => __('File could not be deleted.')]);
        }

        return response()->json(['success' => true, 'message' => __('File deleted successfully.')]);
    }


    private function getScopes()
    {
        try {
            $default = [
                'code' => 0,
                'label' => __('Default Config'),
            ];

            $scopes = Router::where('status', 'enabled')
                ->orderBy('id')
                ->get()
                ->map(function ($scope) {
                    return [
                        'code' => $scope->id,
                        'label' => $scope->name,
                    ];
                })->toArray();

            $scopes[] = $default;
            sort($scopes);
            return $scopes;

        } catch (Exception $e) {
            return [];
        }
    }
}
