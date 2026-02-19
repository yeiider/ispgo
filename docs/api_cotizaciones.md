### API de Cotizaciones

Esta API permite registrar solicitudes de cotización provenientes del portal web o de WhatsApp.

#### Autenticación

- Requiere `auth:api` (token Bearer). Obtén el token mediante el endpoint de login existente.

#### Endpoint

- Método: `POST`
- URL: `/api/v1/cotizaciones`
- Headers:
  - `Authorization: Bearer {TOKEN}`
  - `Accept: application/json`
  - `Content-Type: application/json`

#### Cuerpo (JSON)

```
{
  "nombre": "Juan",
  "apellido": "Pérez",
  "email": "juan.perez@correo.com",
  "telefono": "3201234567",
  "direccion": "Carrera 121 #42-93",
  "ciudad": "Cali",
  "plan": "Plan Premium 500 Mbps",
  "canal": "web",            // valores permitidos: web | whatsapp
  "estado": "pendiente",     // opcional, por defecto: pendiente (valores: pendiente | atendida | cancelada | no_contactado | completada)
  "notas": "Cliente prefiere contacto en la tarde" // opcional
}
```

Notas:
- Si `estado` no se envía, se asume `pendiente`.
- Regla de negocio: si ya existe una cotización con el mismo `telefono` y el mismo `estado`, NO se creará una nueva. Se responderá con error 422 indicando el duplicado.

#### Respuestas

- 201 Created
```
{
  "message": "Cotización creada correctamente",
  "data": {
    "id": 1,
    "nombre": "Juan",
    "apellido": "Pérez",
    "email": "juan.perez@correo.com",
    "telefono": "3201234567",
    "direccion": "Carrera 121 #42-93",
    "ciudad": "Cali",
    "plan": "Plan Premium 500 Mbps",
    "canal": "web",
    "estado": "pendiente",
    "notas": null,
    "metadata": null,
    "created_at": "2025-10-28T11:30:00.000000Z",
    "updated_at": "2025-10-28T11:30:00.000000Z"
  }
}
```

- 422 Unprocessable Entity (validación o duplicado)
```
{
  "message": "Ya existe una cotización con el mismo teléfono y estado: pendiente",
  "status": "duplicate",
  "errors": {
    // en caso de validaciones de campos
  }
}
```

- 401 Unauthorized (sin token válido)

#### Ejemplos

cURL
```
curl -X POST https://tu-dominio.com/api/v1/cotizaciones \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Juan",
    "apellido": "Pérez",
    "email": "juan.perez@correo.com",
    "telefono": "3201234567",
    "direccion": "Carrera 121 #42-93",
    "ciudad": "Cali",
    "plan": "Plan Premium 500 Mbps",
    "canal": "web"
  }'
```

Postman
- Método: POST
- URL: `https://tu-dominio.com/api/v1/cotizaciones`
- Authorization: Bearer Token
  - Body: JSON con los campos anteriores (sin el header `Content-Type`).

#### Migración y despliegue

1. Ejecuta migraciones:
```
php artisan migrate
```
2. Opcional: compila assets de Nova si aplica.

#### Recursos Nova

Se creó el recurso Nova `Cotizacion` para visualizar y actualizar el estado o canal desde el panel administrativo. Campos disponibles: nombre, apellido, email, teléfono, dirección, ciudad, plan, canal, estado, notas, timestamps.

#### Notificaciones por correo (en cola Redis)

Al crear una cotización exitosa (201), se encola el envío de un correo de confirmación al email del solicitante. El envío se realiza con colas de Laravel usando Redis.

- Mailable: `App\\Mail\\CotizacionCreadaMail` (implementa `ShouldQueue`)
- Vista: `resources/views/emails/cotizacion_creada.blade.php`
- Cola: `emails`

Variables que se renderizan en la plantilla del correo:
- `nombre`, `apellido`, `email`, `telefono`, `direccion`, `ciudad`
- Información del plan mostrada en la tarjeta del correo:
  - `plan_nombre` (por defecto usa el campo `plan` enviado)
  - `plan_velocidad` (opcional)
  - `plan_precio` (opcional)

Puedes enviar estos datos extra como parte del cuerpo de la solicitud:
```
{
  "nombre": "Juan",
  "apellido": "Pérez",
  "email": "juan.perez@correo.com",
  "telefono": "3201234567",
  "direccion": "Carrera 121 #42-93",
  "ciudad": "Cali",
  "plan": "Plan Premium 500 Mbps",
  "canal": "web",
  "plan_velocidad": "500 Mbps simétricos",
  "plan_precio": "$120.000/mes",
  "metadata": {
    "plan_nombre": "Premium 500",
    "plan": {"velocidad": "500 Mbps", "precio": "$120.000"}
  }
}
```

Notas de configuración para colas y correo:
1. Asegúrate de tener Redis ejecutándose. Con Sail:
```
./vendor/bin/sail up -d
```
2. Configura la cola a Redis en `.env`:
```
QUEUE_CONNECTION=redis
```
3. Ejecuta el worker para la cola de correos (o el worker general):
```
php artisan queue:work --queue=emails
# o
php artisan queue:work
```
4. Configura el mailer según tu entorno (por ejemplo, Mailpit con Sail):
```
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS="no-reply@raicesc.net"
MAIL_FROM_NAME="Raíces"
```

Con esta configuración, cada creación de cotización despachará un correo en segundo plano sin bloquear la respuesta del API.
