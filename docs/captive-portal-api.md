# API del Portal Cautivo WiFi

## Descripción General

Este documento describe los endpoints del sistema de portal cautivo WiFi con autenticación OTP. El sistema permite validar clientes existentes con servicios activos o registrar usuarios invitados temporalmente.

## Base URL

```
https://tu-dominio.com/api/captive-portal
```

## Autenticación

Todos los endpoints requieren autenticación mediante Bearer Token (OAuth2).

```http
Authorization: Bearer {tu_token_de_acceso}
```

## Flujo de Uso

### Para Clientes Existentes:
1. Cliente solicita acceso con su número de cédula
2. Sistema verifica que sea cliente activo con servicios activos y sin facturas vencidas
3. Si cumple requisitos, envía OTP al correo/WhatsApp del cliente
4. Cliente ingresa el código OTP
5. Sistema verifica el código y otorga acceso por 24 horas

### Para Usuarios Invitados:
1. Usuario solicita acceso proporcionando nombre, email/teléfono
2. Sistema verifica si ya tiene acceso activo (últimas 24h)
3. Si no tiene acceso, envía OTP
4. Usuario ingresa el código OTP
5. Sistema verifica y otorga acceso por 24 horas

---

## Endpoints

### 1. Solicitar Acceso

Verifica si el usuario es cliente o invitado y envía un código OTP.

**Endpoint:** `POST /api/captive-portal/request-access`

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Body Parameters:**

| Campo | Tipo | Requerido | Descripción |
|-------|------|-----------|-------------|
| `identity_document` | string | Condicional* | Número de cédula del cliente |
| `email` | string | Condicional* | Email del usuario (requerido si no se envía identity_document) |
| `full_name` | string | Condicional** | Nombre completo (requerido para invitados) |
| `phone_number` | string | Condicional** | Número de teléfono (requerido para invitados) |
| `router_id` | integer | Sí | ID del router |
| `otp_method` | string | Sí | Método de envío: `email` o `whatsapp` |
| `mac_address` | string | No | Dirección MAC del dispositivo |
| `ip_address` | string | No | Dirección IP del dispositivo |

\* Se requiere `identity_document` O `email`
\*\* Se requiere `full_name` y `phone_number` solo para invitados (no clientes)

**Ejemplo - Cliente Existente:**
```json
{
  "identity_document": "1234567890",
  "router_id": 1,
  "otp_method": "email"
}
```

**Ejemplo - Usuario Invitado:**
```json
{
  "full_name": "Juan Pérez",
  "email": "juan@example.com",
  "phone_number": "3001234567",
  "router_id": 1,
  "otp_method": "email",
  "mac_address": "AA:BB:CC:DD:EE:FF",
  "ip_address": "192.168.1.100"
}
```

**Respuestas:**

**200 OK - OTP Enviado:**
```json
{
  "success": true,
  "message": "Código OTP enviado exitosamente.",
  "access_id": 123,
  "otp_method": "email",
  "expires_at": "2026-02-24T20:30:00.000000Z"
}
```

**200 OK - Acceso ya Activo (Invitado):**
```json
{
  "success": true,
  "message": "Ya tienes acceso activo.",
  "access_valid_until": "2026-02-25T10:15:00.000000Z"
}
```

**403 Forbidden - Sin Servicios Activos:**
```json
{
  "success": false,
  "message": "El cliente no tiene servicios activos."
}
```

**403 Forbidden - Facturas Vencidas:**
```json
{
  "success": false,
  "message": "El cliente tiene facturas vencidas pendientes de pago."
}
```

**422 Unprocessable Entity - Error de Validación:**
```json
{
  "success": false,
  "errors": {
    "router_id": ["The router id field is required."]
  }
}
```

---

### 2. Verificar Código OTP

Valida el código OTP enviado al usuario y otorga acceso por 24 horas.

**Endpoint:** `POST /api/captive-portal/verify-otp`

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Body Parameters:**

| Campo | Tipo | Requerido | Descripción |
|-------|------|-----------|-------------|
| `access_id` | integer | Sí | ID del registro de acceso (devuelto en request-access) |
| `otp_code` | string | Sí | Código OTP de 6 dígitos |

**Ejemplo:**
```json
{
  "access_id": 123,
  "otp_code": "456789"
}
```

**Respuestas:**

**200 OK - Acceso Concedido:**
```json
{
  "success": true,
  "message": "Acceso concedido exitosamente.",
  "access_valid_until": "2026-02-25T19:45:00.000000Z"
}
```

**401 Unauthorized - Código Inválido:**
```json
{
  "success": false,
  "message": "Código OTP inválido o expirado."
}
```

**422 Unprocessable Entity - Error de Validación:**
```json
{
  "success": false,
  "errors": {
    "otp_code": ["The otp code must be 6 characters."]
  }
}
```

---

### 3. Verificar Acceso Activo

Comprueba si un usuario tiene acceso WiFi válido actualmente.

**Endpoint:** `POST /api/captive-portal/check-access`

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Body Parameters:**

| Campo | Tipo | Requerido | Descripción |
|-------|------|-----------|-------------|
| `email` | string | Condicional* | Email del usuario |
| `phone_number` | string | Condicional* | Número de teléfono |
| `router_id` | integer | Sí | ID del router |

\* Se requiere `email` O `phone_number`

**Ejemplo:**
```json
{
  "email": "juan@example.com",
  "router_id": 1
}
```

**Respuestas:**

**200 OK - Con Acceso Activo:**
```json
{
  "success": true,
  "has_access": true,
  "access_valid_until": "2026-02-25T19:45:00.000000Z"
}
```

**200 OK - Sin Acceso Activo:**
```json
{
  "success": true,
  "has_access": false
}
```

**422 Unprocessable Entity - Error de Validación:**
```json
{
  "success": false,
  "errors": {
    "router_id": ["The router id field is required."]
  }
}
```

---

## Base de Datos

### Tabla: `guest_wifi_access`

Almacena los registros de acceso WiFi tanto para clientes como para invitados.

**Campos:**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | ID único del registro |
| `full_name` | string | Nombre completo del usuario |
| `phone_number` | string(15) | Número de teléfono |
| `email` | string | Correo electrónico |
| `router_id` | bigint | ID del router (FK) |
| `otp_code` | string(6) | Código OTP generado |
| `otp_method` | enum | Método de envío: 'email' o 'whatsapp' |
| `otp_expires_at` | timestamp | Fecha de expiración del OTP (10 minutos) |
| `is_verified` | boolean | Indica si el OTP fue verificado |
| `verified_at` | timestamp | Fecha de verificación del OTP |
| `access_expires_at` | timestamp | Fecha de expiración del acceso (24 horas) |
| `ip_address` | string(45) | Dirección IP del dispositivo |
| `mac_address` | string(17) | Dirección MAC del dispositivo |
| `created_at` | timestamp | Fecha de creación |
| `updated_at` | timestamp | Fecha de última actualización |

---

## Lógica de Negocio

### Validación de Clientes

Para que un cliente pueda obtener acceso debe cumplir:

1. **Estado activo:** `customer_status = 'active'` en la tabla `customers`
2. **Servicios activos:** Al menos un servicio con `service_status = 'active'` en la tabla `services`
3. **Sin facturas vencidas:** No tener facturas con `status = 'unpaid'` y `due_date < now()`

### Código OTP

- Formato: 6 dígitos numéricos (ejemplo: "123456")
- Validez: 10 minutos desde su generación
- Un solo uso: Una vez verificado no puede reutilizarse

### Acceso Temporal

- Duración: 24 horas desde la verificación exitosa
- Bloqueo: Durante las 24 horas de acceso activo, no se enviarán nuevos OTP
- Renovación: Después de expirar las 24 horas, puede solicitarse nuevo acceso

---

## Método de Envío OTP

### Email (Implementado)

El código se envía por correo electrónico usando el sistema de mail de Laravel.

**Asunto:** Código de Acceso WiFi

**Contenido:**
```
Tu código de acceso WiFi es: 123456

Este código expira en 10 minutos.
```

### WhatsApp (Por Implementar)

Actualmente registra el código en logs. Para implementar completamente:

1. Integrar servicio de API de WhatsApp (Twilio, WhatsApp Business API, etc.)
2. Actualizar el método `sendOtp()` en `CaptivePortalController.php`

---

## Ejemplos de Integración

### Ejemplo Completo - JavaScript

```javascript
// 1. Solicitar acceso para cliente
async function requestAccessForCustomer(identityDocument, routerId) {
  const response = await fetch('https://tu-dominio.com/api/captive-portal/request-access', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer YOUR_TOKEN',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      identity_document: identityDocument,
      router_id: routerId,
      otp_method: 'email'
    })
  });

  const data = await response.json();
  return data.access_id;
}

// 2. Verificar código OTP
async function verifyOtp(accessId, otpCode) {
  const response = await fetch('https://tu-dominio.com/api/captive-portal/verify-otp', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer YOUR_TOKEN',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      access_id: accessId,
      otp_code: otpCode
    })
  });

  const data = await response.json();
  return data.success;
}

// 3. Verificar si tiene acceso activo
async function checkAccess(email, routerId) {
  const response = await fetch('https://tu-dominio.com/api/captive-portal/check-access', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer YOUR_TOKEN',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      email: email,
      router_id: routerId
    })
  });

  const data = await response.json();
  return data.has_access;
}
```

### Ejemplo - PHP cURL

```php
<?php

// Solicitar acceso para invitado
$ch = curl_init('https://tu-dominio.com/api/captive-portal/request-access');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer YOUR_TOKEN',
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'full_name' => 'Juan Pérez',
    'email' => 'juan@example.com',
    'phone_number' => '3001234567',
    'router_id' => 1,
    'otp_method' => 'email'
]));

$response = curl_exec($ch);
$data = json_decode($response, true);
curl_close($ch);

echo "Access ID: " . $data['access_id'];
?>
```

---

## Seguridad

### Recomendaciones

1. **Rate Limiting:** Limitar la cantidad de solicitudes de OTP por IP/email/teléfono
2. **HTTPS:** Todos los endpoints deben usarse sobre HTTPS
3. **Token Rotation:** Rotar tokens de autenticación regularmente
4. **Logs:** Registrar todos los intentos de acceso para auditoría
5. **Validación MAC:** Validar que la MAC address coincida en verificación

### Limpieza de Datos

Se recomienda crear un job programado para limpiar registros antiguos:

```php
// Eliminar registros de más de 7 días
GuestWifiAccess::where('created_at', '<', now()->subDays(7))->delete();
```

---

## Códigos de Estado HTTP

| Código | Descripción |
|--------|-------------|
| 200 | Operación exitosa |
| 401 | No autenticado o OTP inválido |
| 403 | Cliente no cumple requisitos |
| 422 | Error de validación en parámetros |
| 500 | Error interno del servidor |

---

## Soporte

Para soporte técnico o dudas sobre la integración, contactar al equipo de desarrollo.

**Versión del documento:** 1.0
**Fecha:** 24 de Febrero, 2026
