<?php

namespace Database\Seeders;

use App\Models\CoreConfigData;
use Illuminate\Database\Seeder;

class YaneCatalogSeeder extends Seeder
{
    /**
     * Seed the initial catalog values for the Yane AI assistant.
     * These are editable afterwards from Nova → Asistente Yane (IA).
     */
    public function run()
    {
        $coberturaCiudades = <<<'TXT'
Cali: Ciudad Pacífica, Kachipay, Bochalema, Tierra Linda
Jamundí: Pangola, El Castillo
Santander de Quilichao
Puerto Tejada
Guachené
Padilla
Caloto
TXT;

        $coberturaSinonimos = <<<'TXT'
Cali: ciudad pacifica, tierra linda, bochalema, kachipay
Guachené: El llano, Llano de taula, El Guabal, San Jacinto, San Jose, Cinco y Seis, Cabañita, Obando
Caloto: San Nicolas, Santa Rosa, El Crucero
Puerto Tejada: Emanuel, El triunfo, Betel, Santa Elena, Ciudad del Sur, Villa Clarita, Ciudad Amiga, Bocas del palo
Jamundí: Pangola, El castillo
TXT;

        $oficinas = <<<'TXT'
Cali (Ciudad Pacífica): Carrera 121 # 42-93
Santander de Quilichao: Calle 4 # 14-37
Puerto Tejada (Ciudad del Sur): Calle 86A # 22-03 esquina
Guachené: Calle 8 # 6-52 B/Jorge E. Gaitán
Padilla: Calle 9 # 9-05 esquina
Caloto: Calle 18 # 4-30 B/La Unión
TXT;

        $costosInstalacion = <<<'TXT'
Ciudad Pacífica y Jamundí: Gratis
Puerto Tejada, Ciudad Amiga: $50.000
Ciudad del Sur, Santander de Quilichao, Guachené, Caloto: $100.000
TXT;

        $faqs = <<<'TXT'
¿Cuánto tarda la instalación? => Menos de 48 horas después de la solicitud
¿Hay cláusula de permanencia? => No, no hay cláusula de permanencia
¿Cómo puedo pagar? => Transferencias y pagos en línea. Las opciones exactas se muestran al consultar el sistema.
¿Hay meses gratis o promociones de 30 días? => No ofrecemos meses gratis ni periodos de prueba de 30 días.
TXT;

        $canalesTv = 'A&E, AMC HD, DISCOVERY H&H, DISCOVERY, DISCOVERY KIDS HD, FOOD NETWORK HD, FOX SPORTS 3, HISTORY CHANNEL HS, MTV, MTV LIVE HD, PASIONES, STAR CHANNEL HD, ANIMAL PLANET, AXN, BABY TV, BETTEL, TRECE EN VIVO, CANAL CAPITAL, CANAL CONGRESO, CANAL DE LAS ESTRELLAS, CANAL DE PRUEBAS, CANAL INSTITUCIONAL, CANAL UNO, CARACOL TV HD, CARTOON NETWORK, CINE FAMILIAR, CINE PREMIUM, CINE CANAL, CINEMAX, CITY TV, COMEDY CENTRAL, CRISTO VISION, DE PELICULA, DHE, DISCOVERY WORLD, DISNEY CHANNEL, ENLACE, ESPN, ESPN 2, ESPN 3, ESPN EXTRA, EURO CHANNEL, EWTN, FOX SPORTS, FOX SPORTS 2, FX, GLITZ, GOLDEN, GOLDEN EDGE, GOLDEN PLUS, HISTORY 2, HOGAR TV, ID HD, INFANTIL, LA KALLE, LOVE NATURE, MORBIDO TV (SIN SEÑAL), MTV (CHANNEL 230), NATIONAL GEOGRAPHIC, NICK HD, NICK JR, PARAMOUNT, RAICES TV, RCN HD, RCN HD 2, RUMBA TV, SEÑAL COLOMBIA, SONY, SPACE, STUDIO UNIVERSAL, SUN CHANNEL, TBS, TELE ANTILLAS, TELEMUNDO, TELE NOSTALGIA, TELE ANTIOQUIA, TELE CAFÉ ®, TELE CARIBE ®, TELEPACIFICO, TELE SUR, TELENOVELAS, TNT, TNT SERIES, TOONCASK, TRO ®, TV AGRO, TV MAX (SIN SEÑAL), TVE, UNIVERSAL CHANNEL, UNIVISION, WARNER CHANNEL, WIN SPORT, ZOO MOOD, ZOOM';

        $planes = <<<'TXT'
Plan Ultra | 200 | 200 | $65.000 | regular | Ideal para navegar, correos y redes sociales |
Plan Premium | 500 | 500 | $85.000 | regular | Ideal para streaming y videollamadas | Soporte prioritario
Plan Platino | 900 | 900 | $105.000 | regular | Ideal para teletrabajo y estudio | Soporte prioritario 24/7. Entre los más vendidos
Plan Mundialista 200 | 200 | 200 | $99.900 | temporada | 200 MB + Dgo Flex + plantilla del mundial | Oferta exclusiva por temporada
Plan Mundialista 500 | 500 | 500 | $149.900 | temporada | 500 MB + Dgo Full + Win + plantilla del mundial | Oferta exclusiva por temporada
TXT;

        $data = [
            'asistente_yane/contactos/web_url' => 'https://raicesc.net',
            'asistente_yane/contactos/email' => 'contacto@raicesc.net',
            'asistente_yane/contactos/payment_url' => 'https://www.raicesc.net/pagos',
            'asistente_yane/planes/listado' => $planes,
            'asistente_yane/cobertura/ciudades' => $coberturaCiudades,
            'asistente_yane/cobertura/sinonimos' => $coberturaSinonimos,
            'asistente_yane/oficinas/listado' => $oficinas,
            'asistente_yane/costos_instalacion/listado' => $costosInstalacion,
            'asistente_yane/faqs/listado' => $faqs,
            'asistente_yane/canales_tv/listado' => $canalesTv,
        ];

        foreach ($data as $path => $value) {
            CoreConfigData::updateOrCreate(
                ['scope_id' => 0, 'path' => $path],
                ['value' => $value]
            );
        }
    }
}
