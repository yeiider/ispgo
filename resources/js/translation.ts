/**
 * Translate the given key.
 */

import {usePage} from "@inertiajs/react";

export function __(key: string) {
    // @ts-ignore
    const {translate: translations} = usePage().props;
    const DEFAULT_LANGUAGE = {};

    if (translations) {
        // @ts-ignore
        const language = translations.language || DEFAULT_LANGUAGE;
        const translation = language[key];

        return translation !== undefined ? translation : key;
    }

    return key;
}




