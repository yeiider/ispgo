<?php

namespace Ispgo\Siigo\Helpers;

class ColombiaDivipolaCatalog
{
    /**
     * Known Department DIVIPOLA 2-digit codes
     */
    private static array $departments = [
        'bogota' => '11',
        'cundinamarca' => '25',
        'antioquia' => '05',
        'valle' => '76',
        'valle del cauca' => '76',
        'cauca' => '19',
        'atlantico' => '08',
        'bolivar' => '13',
        'santander' => '68',
        'norte de santander' => '54',
        'boyaca' => '15',
        'caldas' => '17',
        'caqueta' => '18',
        'cesar' => '20',
        'choco' => '27',
        'cordoba' => '23',
        'huila' => '41',
        'la guajira' => '44',
        'magdalena' => '47',
        'meta' => '50',
        'narino' => '52',
        'quindio' => '63',
        'risaralda' => '66',
        'sucre' => '70',
        'tolima' => '73',
        'arauca' => '81',
        'casanare' => '85',
        'putumayo' => '86',
        'san andres' => '88',
        'amazonas' => '91',
        'guainia' => '94',
        'guaviare' => '95',
        'vaupes' => '97',
        'vichada' => '99',
    ];

    /**
     * Mapping of City names to official DANE/Siigo 5-digit codes
     */
    private static array $cities = [
        // Bogotá D.C.
        'bogota' => ['state' => '11', 'city' => '11001'],
        'bogota d.c.' => ['state' => '11', 'city' => '11001'],
        'bogota dc' => ['state' => '11', 'city' => '11001'],

        // Cauca (19)
        'popayan' => ['state' => '19', 'city' => '19001'],
        'santander de quilichao' => ['state' => '19', 'city' => '19698'],
        'quilichao' => ['state' => '19', 'city' => '19698'],
        'guachene' => ['state' => '19', 'city' => '19318'],
        'caloto' => ['state' => '19', 'city' => '19142'],
        'villa rica' => ['state' => '19', 'city' => '19845'],
        'villarica' => ['state' => '19', 'city' => '19845'],
        'puerto tejada' => ['state' => '19', 'city' => '19573'],
        'miranda' => ['state' => '19', 'city' => '19455'],
        'corinto' => ['state' => '19', 'city' => '19212'],
        'padilla' => ['state' => '19', 'city' => '19517'],
        'patia' => ['state' => '19', 'city' => '19532'],
        'el tambo' => ['state' => '19', 'city' => '19256'],
        'piendamo' => ['state' => '19', 'city' => '19548'],
        'silvia' => ['state' => '19', 'city' => '19743'],
        'morales' => ['state' => '19', 'city' => '19473'],

        // Valle del Cauca (76)
        'cali' => ['state' => '76', 'city' => '76001'],
        'santiago de cali' => ['state' => '76', 'city' => '76001'],
        'jamundi' => ['state' => '76', 'city' => '76364'],
        'palmira' => ['state' => '76', 'city' => '76520'],
        'yumbo' => ['state' => '76', 'city' => '76892'],
        'buga' => ['state' => '76', 'city' => '76111'],
        'guadalajara de buga' => ['state' => '76', 'city' => '76111'],
        'tulua' => ['state' => '76', 'city' => '76834'],
        'cartago' => ['state' => '76', 'city' => '76147'],
        'buenaventura' => ['state' => '76', 'city' => '76109'],
        'candelaria' => ['state' => '76', 'city' => '76130'],
        'florida' => ['state' => '76', 'city' => '76275'],
        'pradera' => ['state' => '76', 'city' => '76563'],
        'cerrito' => ['state' => '76', 'city' => '76248'],
        'el cerrito' => ['state' => '76', 'city' => '76248'],

        // Antioquia (05)
        'medellin' => ['state' => '05', 'city' => '05001'],
        'envigado' => ['state' => '05', 'city' => '05266'],
        'itagui' => ['state' => '05', 'city' => '05360'],
        'bello' => ['state' => '05', 'city' => '05088'],
        'sabaneta' => ['state' => '05', 'city' => '05631'],
        'rionegro' => ['state' => '05', 'city' => '05615'],

        // Atlántico (08)
        'barranquilla' => ['state' => '08', 'city' => '08001'],
        'soledad' => ['state' => '08', 'city' => '08758'],
        'puerto colombia' => ['state' => '08', 'city' => '08573'],

        // Bolívar (13)
        'cartagena' => ['state' => '13', 'city' => '13001'],
        'cartagena de indias' => ['state' => '13', 'city' => '13001'],

        // Santander (68)
        'bucaramanga' => ['state' => '68', 'city' => '68001'],
        'floridablanca' => ['state' => '68', 'city' => '68276'],
        'giron' => ['state' => '68', 'city' => '68307'],
        'pie de cuesta' => ['state' => '68', 'city' => '68547'],
        'piedecuesta' => ['state' => '68', 'city' => '68547'],

        // Norte de Santander (54)
        'cucuta' => ['state' => '54', 'city' => '54001'],
        'los patios' => ['state' => '54', 'city' => '54405'],
        'villa del rosario' => ['state' => '54', 'city' => '54874'],

        // Cundinamarca (25)
        'soacha' => ['state' => '25', 'city' => '25754'],
        'chia' => ['state' => '25', 'city' => '25175'],
        'zipaquira' => ['state' => '25', 'city' => '25899'],
        'facatativa' => ['state' => '25', 'city' => '25269'],
        'mosquera' => ['state' => '25', 'city' => '25473'],
        'madrid' => ['state' => '25', 'city' => '25430'],
        'funza' => ['state' => '25', 'city' => '25286'],
        'cajica' => ['state' => '25', 'city' => '25126'],

        // Risaralda (66)
        'pereira' => ['state' => '66', 'city' => '66001'],
        'dosquebradas' => ['state' => '66', 'city' => '66170'],

        // Caldas (17)
        'manizales' => ['state' => '17', 'city' => '17001'],
        'villamaria' => ['state' => '17', 'city' => '17873'],

        // Quindío (63)
        'armenia' => ['state' => '63', 'city' => '63001'],
        'calarca' => ['state' => '63', 'city' => '63130'],

        // Huila (41)
        'neiva' => ['state' => '41', 'city' => '41001'],
        'pitalito' => ['state' => '41', 'city' => '41551'],

        // Meta (50)
        'villavicencio' => ['state' => '50', 'city' => '50001'],

        // Nariño (52)
        'pasto' => ['state' => '52', 'city' => '52001'],
        'san juan de pasto' => ['state' => '52', 'city' => '52001'],
        'ipiales' => ['state' => '52', 'city' => '52356'],
        'tumaco' => ['state' => '52', 'city' => '52835'],

        // Tolima (73)
        'ibague' => ['state' => '73', 'city' => '73001'],
        'espinal' => ['state' => '73', 'city' => '73268'],

        // Córdoba (23)
        'monteria' => ['state' => '23', 'city' => '23001'],

        // Cesar (20)
        'valledupar' => ['state' => '20', 'city' => '20001'],

        // Magdalena (47)
        'santa marta' => ['state' => '47', 'city' => '47001'],

        // Boyacá (15)
        'tunja' => ['state' => '15', 'city' => '15001'],
        'sogamoso' => ['state' => '15', 'city' => '15759'],
        'duitama' => ['state' => '15', 'city' => '15238'],

        // Sucre (70)
        'sincelejo' => ['state' => '70', 'city' => '70001'],

        // Caquetá (18)
        'florencia' => ['state' => '18', 'city' => '18001'],

        // Casanare (85)
        'yopal' => ['state' => '85', 'city' => '85001'],

        // La Guajira (44)
        'riohacha' => ['state' => '44', 'city' => '44001'],
        'maicao' => ['state' => '44', 'city' => '44430'],

        // Chocó (27)
        'quibdo' => ['state' => '27', 'city' => '27001'],
    ];

    /**
     * Resolve state and city codes dynamically
     */
    public static function resolve(?string $stateName, ?string $cityName, string $defaultCityCode = '11001'): array
    {
        $rawCity = trim((string)$cityName);
        $rawState = trim((string)$stateName);

        // 1. Check if city is explicitly provided as a 5-digit DIVIPOLA/Siigo code (e.g. "19698" or "11001")
        if (preg_match('/^\d{5}$/', $rawCity)) {
            return [
                'state_code' => substr($rawCity, 0, 2),
                'city_code' => $rawCity,
            ];
        }

        // 2. Normalize text inputs (strip accents and lower-case)
        $cleanCity = self::normalizeStr($rawCity);
        $cleanState = self::normalizeStr($rawState);

        // 3. Search exact match in city catalog
        if (!empty($cleanCity) && isset(self::$cities[$cleanCity])) {
            return [
                'state_code' => self::$cities[$cleanCity]['state'],
                'city_code' => self::$cities[$cleanCity]['city'],
            ];
        }

        // 4. Fuzzy search in city catalog (partial match)
        if (!empty($cleanCity)) {
            foreach (self::$cities as $cityNameKey => $codes) {
                if (strpos($cleanCity, $cityNameKey) !== false || strpos($cityNameKey, $cleanCity) !== false) {
                    return [
                        'state_code' => $codes['state'],
                        'city_code' => $codes['city'],
                    ];
                }
            }
        }

        // 5. Try to resolve department code if state name provided
        $stateCode = null;
        if (!empty($cleanState)) {
            if (preg_match('/^\d{2}$/', $cleanState)) {
                $stateCode = $cleanState;
            } else {
                foreach (self::$departments as $deptKey => $code) {
                    if (strpos($cleanState, $deptKey) !== false || strpos($deptKey, $cleanState) !== false) {
                        $stateCode = $code;
                        break;
                    }
                }
            }
        }

        // 6. Use fallback default city code (e.g. Configured per Zone/Router or Global Default)
        $defaultState = strlen($defaultCityCode) >= 2 ? substr($defaultCityCode, 0, 2) : '11';
        $finalStateCode = $stateCode ?: $defaultState;
        $finalCityCode = (strlen($defaultCityCode) === 5) ? $defaultCityCode : ($finalStateCode . '001');

        return [
            'state_code' => $finalStateCode,
            'city_code' => $finalCityCode,
        ];
    }

    /**
     * Normalize strings: remove accents, diacritics, punctuation and lowercase
     */
    public static function normalizeStr(string $str): string
    {
        $str = mb_strtolower(trim($str), 'UTF-8');
        $unwanted = [
            'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u', 'ü'=>'u', 'ñ'=>'n',
            'à'=>'a', 'è'=>'e', 'ì'=>'i', 'ò'=>'o', 'ù'=>'u',
            'â'=>'a', 'ê'=>'e', 'î'=>'i', 'ô'=>'o', 'û'=>'u',
            'ä'=>'a', 'ë'=>'e', 'ï'=>'i', 'ö'=>'o', 'ü'=>'u',
            '.'=>'', ','=>'', '-'=>' ', '_'=>' '
        ];
        $str = strtr($str, $unwanted);
        return preg_replace('/\s+/', ' ', $str);
    }
}
