
# 📡 SmartOLT API – Gestión de ONU por `external_id`

**Última actualización:** 2025-05-22

Este documento describe las principales APIs de SmartOLT utilizadas para consultar y manipular ONUs (UNUs) asociadas a un cliente, a través del `external_id`. Este resumen es útil para integrarlas en un panel de administración para servicios ISP desarrollado en Laravel Nova y React.

---

## 🔐 Autenticación

Todas las solicitudes deben contener el encabezado:

```http
X-Token: TU_API_KEY
```

---

## 🔍 Endpoints GET (Consultas)

### 1. Obtener detalles de la ONU

**GET** `/api/onu/get_onu_details/{external_id}`

- Retorna la información básica de la ONU.
- Incluye board, puerto, número de onu, tipo, estado, SN, etc.

📌 **Uso sugerido:** Mostrar datos del equipo en la vista de servicio.

---

### 2. Obtener estado completo de la ONU

**GET** `/api/onu/get_onu_full_status_info/{external_id}`

- Retorna información operativa, de tráfico, señal óptica, etc.

📌 **Uso sugerido:** Diagnóstico técnico completo del estado de la ONU.

---

### 3. Obtener configuración actual de la ONU

**GET** `/api/onu/get_onu_running_config/{external_id}`

- Devuelve el estado actual de configuración del dispositivo.

📌 **Uso sugerido:** Confirmar que la configuración activa es la correcta.

---

### 4. Obtener gráfico de señal óptica

**GET** `/api/onu/get_onu_signal_graph/{external_id}`

- Muestra la calidad de señal óptica con histórico.

📌 **Uso sugerido:** Seguimiento de calidad del enlace.

---

### 5. Obtener gráfico de tráfico

**GET** `/api/onu/get_onu_traffic_graph/{external_id}`

- Muestra estadísticas de uso de datos.

📌 **Uso sugerido:** Diagnóstico por consumo excesivo o caídas de servicio.

---

## ⚙️ Endpoints POST (Acciones)

### 6. Autorizar ONU

**POST** `/api/onu/authorize_onu`

**Body:**
```json
{
  "external_id": "...",
  "sn": "...",
  "board": 0,
  "port": 1,
  "onu": 2,
  "type": "zte-f660"
}
```

📌 **Uso:** Activar una ONU en la red.

---

### 7. Actualizar tipo de ONU

**POST** `/api/onu/update_onu_type_by_onu_external_id`

**Body:**
```json
{
  "external_id": "...",
  "onu_type_id": 1
}
```

📌 **Uso:** Cuando se cambia el modelo de ONU.

---

### 8. Cambiar perfil de velocidad

**POST** `/api/onu/update_onu_speed_profiles_by_onu_external_id`

**Body:**
```json
{
  "external_id": "...",
  "speed_profile_id": 2
}
```

📌 **Uso:** Actualizar velocidad contratada.

---

### 9. Reiniciar la ONU

**POST** `/api/onu/reboot_onu_by_onu_external_id`

**Body:**
```json
{
  "external_id": "..."
}
```

📌 **Uso:** Solucionar problemas sin visita técnica.

---

### 10. Restaurar configuración de fábrica

**POST** `/api/onu/restore_onu_factory_defaults_by_onu_external_id`

📌 **Uso:** Reconfiguración o eliminación del cliente.

---

### 11. Cambiar VLAN principal

**POST** `/api/onu/update_onu_main_vlan_id_by_onu_external_id`

**Body:**
```json
{
  "external_id": "...",
  "vlan_id": 100
}
```

📌 **Uso:** Cambiar configuración de red para el cliente.

---

### 12. Cambiar modo WAN

**POST** `/api/onu/set_onu_wan_mode_by_onu_external_id`

**Body:**
```json
{
  "external_id": "...",
  "wan_mode": "pppoe"
}
```

📌 **Uso:** Configurar red según tipo de conexión.

---

### 13. Habilitar/Deshabilitar acceso remoto

**POST habilitar** `/api/onu/enable_onu_allow_remote_access_to_wan_ip_by_onu_external_id`  
**POST deshabilitar** `/api/onu/disable_onu_allow_remote_access_to_wan_ip_by_onu_external_id`

📌 **Uso:** Para soporte remoto si es necesario.

---

### 14. Actualizar ubicación geográfica

**POST** `/api/onu/update_onu_location_details_by_onu_external_id`

**Body:**
```json
{
  "external_id": "...",
  "zone": "zona 1",
  "odb": "ODB-XX",
  "gps_lat": "3.4516",
  "gps_long": "-76.5320"
}
```

📌 **Uso:** Registro geográfico para gestión y soporte técnico.

---

## 📎 Recursos

- [Documentación oficial en Postman](https://www.postman.com/smartolt/smartolt-s-public-workspace/documentation/5cwqhzj/smartolt)

---
