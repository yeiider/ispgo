Documentación de Integración: API de

Administración XUI.ONE

Esta documentación técnica detalla los endpoints y las estructuras de datos necesarios para
implementar la gestión de servicios IPTV (XUI.ONE) desde un sistema centralizado de
administración ISP. El alcance de este documento incluye el ciclo de vida completo de las
líneas de usuario (operaciones CRUD), la gestión de estados para corte/reconexión y la lógica
de asignación de canales y paquetes (Bouquets/Packages).

1. Autenticación y Punto de Enlace (Endpoint) Base

La API de administración de XUI.ONE utiliza una arquitectura que se comunica mediante
parámetros de consulta a través de un código de acceso único y una clave de API. Las
respuestas se proporcionan estandarizadas en formato JSON.
● Endpoint Base: http://[DOMINIO_XUI_O_IP]:[PUERTO]/[ACCESS_CODE]/
● Método HTTP: GET o POST (se recomienda encarecidamente POST para proteger las
credenciales en los payloads).
● Autenticación: El parámetro api_key debe adjuntarse en todas las peticiones para
validar la autorización.

2. Operaciones CRUD para Líneas (Line Users)

2.1. Crear una Línea de Usuario (Create)
Este endpoint permite registrar una nueva línea para un cliente. Aquí se definen sus
credenciales de acceso, el límite de conexiones concurrentes y los paquetes de canales a los
que tiene derecho.
● Acción a invocar: ?api_key=TU_API_KEY&action=create_line
Parámetro Tipo Obligatorio Descripción
user String Sí Nombre de usuario
exclusivo para la línea
(formato alfanumérico).
pass String Sí Contraseña de

Parámetro Tipo Obligatorio Descripción
autenticación de la
línea.

member_id Integer No ID del reseller o del
usuario administrador
que es propietario de la
línea.

max_connections Integer Sí Límite máximo de
conexiones
simultáneas o pantallas
permitidas (ej. 1, 2, 4).
expire_date String/Int Sí Fecha de expiración
(puede requerir
Timestamp UNIX o un
formato de fecha
específico según la
configuración).
bouquets Array/String Sí Lista de IDs de
paquetes (Bouquets)
separados por comas.
Define los canales
asignados.

Estructura de Respuesta Esperada (JSON):
{
"status": true,
"message": "Line created successfully",
"data": {
"line_id": 1405
}
}

2.2. Leer Líneas (Read / Consultar Información)
Utilizado para sincronización o verificación de auditoría. Existen dos niveles de consulta
disponibles.
● Consultar Directorio: action=get_lines (Extrae todo el listado de líneas. Considerar
paginación si el volumen de clientes es alto).
● Detalle Específico: action=get_line&line_id=[ID_LINEA] (Extrae estado, fecha de

vencimiento y paquetes del usuario individual).

2.3. Actualizar o Editar Línea (Update)
Esencial para las modificaciones post-creación: cambiar contraseñas, extender la fecha de
expiración tras la renovación de la mensualidad o realizar upsellings y modificar la parrilla de
canales (parámetro bouquets).
● Acción a invocar: ?api_key=TU_API_KEY&action=edit_line
● Parámetro Condicional: El envío del line_id es de carácter obligatorio. Enviar
únicamente los campos que requieran sobreescritura.

2.4. Eliminar Línea (Delete)
Borra permanentemente la línea y todo su registro de consumo de la base de datos de
XUI.ONE. No es una operación reversible.
● Acción a invocar: action=delete_line&line_id=[ID_LINEA]

3. Gestión de Estados Operativos (Suspensiones por
   Falta de Pago)

Para automatizar el ciclo de facturación y morosidad de un proveedor de servicios de internet,
no se debe eliminar la cuenta. Se debe transicionar el estado del usuario para inhabilitar el
inicio de sesión y el consumo de streaming temporalmente.
Comando API Entrada Mínima Aplicación Típica
disable_line line_id Suspensión por corte de cartera
(factura impaga). Rechaza
peticiones de streaming
instantáneamente.

enable_line line_id Reactivación o provisionamiento
automático una vez conciliado
el pago en el sistema.
ban_line / unban_line line_id Restricción por abuso de la
política de red (conexiones no
autorizadas o piratería de la
línea).

4. Asignación y Descubrimiento de Canales
   (Bouquets)

En XUI.ONE, las señales Live TV, el contenido VOD y las series no se asignan por separado,
sino que se empaquetan en Bouquets o Packages. Para integrarlo correctamente:
1. Extracción del Catálogo: Utilizar el endpoint action=get_packages o
   action=get_bouquets para extraer la lista maestra de paquetes y sus respectivos IDs
   creados en el panel de XUI.ONE.
2. Mapeo del Backend: Almacenar estos IDs (ej. Paquete Básico ID: 1, Paquete Premium
   ID: 2) como planes homologados.
3. Asignación: Pasar el arreglo de IDs requeridos en el parámetro bouquets de los
   endpoints create_line o edit_line.

5. Consideraciones Arquitectónicas y Buenas
   Prácticas
   ●
   Control de Duplicidad: Capturar y procesar de manera segura el error lanzado al enviar
   un nombre de usuario que ya existe ("Username already exists").
   ● Validación Transaccional: Comprobar que el booleano status es true. Si es false,
   guardar el evento en logs para una revisión de excepciones.
   ● Lista Blanca (IP Whitelisting): Por políticas de red perimetral, la configuración del
   Access Code en XUI.ONE debería limitar las llamadas de API exclusivamente a la IP de
   salida de los servidores backend donde corre el sistema orquestador para prevenir fugas
   y abusos de la clave de API.
   ● Manejo de Tareas Pesadas: Las suspensiones o habilitaciones masivas que involucren
   ciclos a principios o fines de mes deben manejarse mediante procesos asíncronos (jobs o
   workers) y aplicar retardos si es necesario, garantizando la estabilidad operativa del
   panel IPTV y evitando los errores de Timeout (HTTP 502/504).
