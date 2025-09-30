# Documentación de la API de Cobros - OnePay

Esta guía describe cómo utilizar los endpoints de la API de OnePay para crear y gestionar solicitudes de cobro.

## Autenticación

Todas las solicitudes a la API deben estar autenticadas. Para ello, necesitas incluir un **token de acceso** en la cabecera `Authorization`.

**Formato:**
`Authorization: Bearer <tu_token_secreto>`

## Idempotencia

Para evitar la duplicación de solicitudes (por ejemplo, crear el mismo cobro dos veces por un reintento de red), puedes incluir una clave única en la cabecera `x-idempotency`. Si se recibe una solicitud con una clave de idempotencia ya utilizada, la API devolverá la respuesta original sin procesar la solicitud nuevamente.

**Formato:**
`x-idempotency: <clave_unica_generada_por_ti>`

---

## Endpoints de Cobros (`/payments`)

A continuación se detallan las operaciones disponibles para gestionar los cobros.

### 1. Crear un Cobro

Este endpoint te permite generar una nueva solicitud de pago. Al crearla, se genera un enlace de pago único que puedes compartir con tu cliente.

- **Método:** `POST`
- **URL:** `https://api.onepay.la/v1/payments`

#### Ejemplo de Solicitud (cURL)

```bash
curl --request POST \
  --url [https://api.onepay.la/v1/payments](https://api.onepay.la/v1/payments) \
  --header 'Authorization: Bearer <token>' \
  --header 'Content-Type: application/json' \
  --header 'x-idempotency: <x-idempotency>' \
  --data '{
  "amount": 150000,
  "title": "Pago Factura #123",
  "currency": "COP",
  "phone": "+573001234567",
  "email": "cliente@example.com",
  "reference": "FACT-123",
  "tax": 23950,
  "external_id": "user_id_12345",
  "description": "Compra de producto X",
  "expiration_date": "2025-10-31T23:59:59",
  "redirect_url": "[https://miempresa.com/pago-exitoso](https://miempresa.com/pago-exitoso)",
  "allows": {
    "cards": true,
    "accounts": true,
    "pse": true,
    "transfiya": true
  }
}'
```

#### Parámetros del Body

| Parámetro         | Tipo    | Descripción                                                                                              | Requerido |
| ----------------- | ------- | -------------------------------------------------------------------------------------------------------- | :-------: |
| `amount`          | Integer | **Monto total del cobro en centavos**. Por ejemplo, para $1.500 COP, el valor debe ser `150000`.            |    Sí     |
| `title`           | String  | Título principal del cobro que verá el cliente.                                                          |    Sí     |
| `currency`        | String  | Moneda del cobro en formato ISO 4217. Ejemplo: `COP`.                                                    |    Sí     |
| `phone`           | String  | Número de teléfono del cliente en formato internacional.                                                 | No        |
| `email`           | String  | Correo electrónico del cliente.                                                                          | No        |
| `reference`       | String  | Referencia de pago interna para tu sistema.                                                              | No        |
| `tax`             | Integer | Monto del impuesto incluido en el total, expresado en centavos.                                          | No        |
| `external_id`     | String  | Un identificador externo para asociar el cobro con un usuario o entidad en tu sistema.                   | No        |
| `description`     | String  | Descripción detallada del cobro.                                                                         | No        |
| `expiration_date` | String  | Fecha de expiración del enlace de pago en formato ISO 8601.                                              | No        |
| `redirect_url`    | String  | URL a la que se redirigirá al cliente después de un pago exitoso.                                        | No        |
| `allows`          | Object  | Objeto para habilitar o deshabilitar métodos de pago específicos (`cards`, `pse`, `transfiya`, etc.).    | No        |
| `splits`          | Array   | Arreglo de objetos para dividir el pago entre diferentes cuentas (disponible en planes específicos).     | No        |

#### Ejemplo de Respuesta Exitosa (`201 Created`)

```json
{
  "id": "9e5ccd4a-d2f0-49dd-87fc-a0da752bd166",
  "status": "pending",
  "currency": "COP",
  "amount": 1400000,
  "title": "Prueba",
  "description": null,
  "payment_link": "[https://pagos.onepay.la/payment/9e5ccd4a-d2f0-49dd-87fc-a0da752bd166](https://pagos.onepay.la/payment/9e5ccd4a-d2f0-49dd-87fc-a0da752bd166)",
  "expiration_at": "2025-03-20T22:36:24.000000Z",
  "is_expired": false,
  "created_at": "2025-03-05T22:36:24.000000Z",
  "amount_label": "$1.400.000",
  "allows": {
    "cards": true,
    "accounts": true,
    "card_extra": false,
    "realtime": false,
    "pse": true,
    "transfiya": true
  },
  "company": {
    "id": "9940779b-0d13-467a-b1a3-ac0a74b70e40",
    "name": "OnePay",
    "legal_name": "OnePay SAS"
  },
  "customer": null
}
```

---

### 2. Consultar un Cobro

Permite obtener el estado y los detalles de un cobro específico utilizando su ID.

- **Método:** `GET`
- **URL:** `https://api.onepay.la/v1/payments/{payment_id}`

#### Ejemplo de Solicitud (cURL)

```bash
curl --request GET \
  --url [https://api.onepay.la/v1/payments/9bf2bc44-28d4-4693-9896-7fc1fe1f5b65](https://api.onepay.la/v1/payments/9bf2bc44-28d4-4693-9896-7fc1fe1f5b65) \
  --header 'Authorization: Bearer <token>'
```

#### Ejemplo de Respuesta Exitosa (`200 OK`)

*Nota: La respuesta puede variar si se consulta un listado (`/payments`) o un cobro individual. Este ejemplo corresponde a un objeto de cobro único.*

```json
{
  "id": "9bf2bc44-28d4-4693-9896-7fc1fe1f5b65",
  "status": "cancelled",
  "currency": "COP",
  "amount": 63040,
  "title": "Factura Movistar Noviembre",
  "description": "Factura Movistar Noviembre",
  "payment_link": "[https://pagos.onepay.la/payment/9bf2bc44-28d4-4693-9896-7fc1fe1f5b65](https://pagos.onepay.la/payment/9bf2bc44-28d4-4693-9896-7fc1fe1f5b65)",
  "phone": "+573138977841",
  "expiration_at": "2024-05-03T16:47:09.000000Z",
  "created_at": "2024-05-02T16:47:09.000000Z",
  "paid_at": null,
  "customer": {
    "first_name": "Juan",
    "last_name": "Demo",
    "email": "hola@onepay.la",
    "phone": "+573167591039"
  },
  "method": null
}
```

---

### 3. Reenviar Notificación de Cobro

Este endpoint permite reenviar la notificación del cobro (generalmente por correo electrónico o SMS) a un cliente.

- **Método:** `POST`
- **URL:** `https://api.onepay.la/v1/payments/{payment_id}`
  *(Nota: Algunos sistemas usan `/resend` al final de la URL. Verifica la documentación oficial si este formato no funciona)*.

#### Ejemplo de Solicitud (cURL)

```bash
curl --request POST \
  --url [https://api.onepay.la/v1/payments/](https://api.onepay.la/v1/payments/){payment_id} \
  --header 'Authorization: Bearer <token>' \
  --header 'x-idempotency: <clave_unica_para_el_reenvio>'
```

Una respuesta exitosa generalmente será un código `200 OK` o `204 No Content` con un cuerpo vacío.

---

### 4. Eliminar / Anular un Cobro

Permite anular un cobro que se encuentra en estado `pending` (pendiente). Un cobro ya pagado no puede ser eliminado.

- **Método:** `DELETE`
- **URL:** `https://api.onepay.la/v1/payments/{payment_id}`

#### Ejemplo de Solicitud (cURL)

```bash
curl --request DELETE \
  --url [https://api.onepay.la/v1/payments/](https://api.onepay.la/v1/payments/){payment_id} \
  --header 'Authorization: Bearer <token>'
```

Una respuesta exitosa generalmente será un código `204 No Content`, indicando que el recurso fue eliminado correctamente.
