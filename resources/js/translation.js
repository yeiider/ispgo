/**
 * Translate the given key.
 */

import {usePage} from "@inertiajs/inertia-vue3";


export function __(key, replace = {}) {
    const {translate: translations} = usePage().props.value;
    const DEFAULT_LANGUAGE = {};

    if (translations) {
        const language = translations.language || DEFAULT_LANGUAGE;
        const translation = language[key];

        return translation !== undefined ? translation : key;
    }

    return key;
}




