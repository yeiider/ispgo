
# 🛠️ Instrucciones para integración SmartOLT

## 📌 Objetivo

El objetivo de esta tarea es **integrar completamente el módulo de administración de ONUs (UNU)** de **SmartOLT** dentro de nuestro sistema ISP. Ya existe una implementación parcial que debe ser extendida con un tablero de control accesible desde cada servicio del cliente.

---

## 📁 Ubicación del código actual

- **Paquete Laravel Nova personalizado:**  
  `nova-components/Smartolt`

- **Documentación de APIs SmartOLT:**  
  `docs/smartolt_onu_api.md`

- **Colección Postman adicional (opcional):**  
  `docs/smartolt_collection.postman.json`

- **Conexión y llamadas actuales a la API:**  
  `nova-components/Smartolt/src/Services/ApiManager.php`

---

## ✅ Tareas

1. **Crear vista personalizada tipo Resource Tool**
   - Ubicación: `nova-components/Smartolt/resources/js/components/tools/OnuManager.vue` (o similar).
   - Esta vista debe permitir gestionar el dispositivo ONU asignado a un servicio.
   - El identificador principal es el **SN** de la ONU, extraído del servicio.

2. **Diseño del Tablero ONU**
   - Usa los endpoints documentados en `smartolt_onu_api.md`.
   - El tablero debe permitir:
     - Ver detalles generales.
     - Ver estado y señal.
     - Ver tráfico.
     - Ejecutar acciones como reboot, cambio de perfil, cambio de VLAN, etc.
   - Usa componentes interactivos como botones, gráficas y alertas para facilitar la administración.

3. **Controladores Laravel**
   - Implementa los endpoints necesarios en Laravel para interactuar con SmartOLT desde el backend.
   - Crea rutas dedicadas en `routes/api.php` o usa controladores dentro del mismo paquete.
   - Utiliza `ApiManager.php` como base para las conexiones.

---

## 🧩 Tecnologías

- **Backend:** Laravel 11
- **Interfaz de administración:** Laravel Nova
- **Frontend para Tool:** Vue.js (Nova Resource Tools)

---

## 📝 Notas

- Puedes extender el paquete `Smartolt` existente sin modificar la lógica ya implementada.
- Asegúrate de validar los errores de red/respuesta para mostrar mensajes adecuados al usuario.
- Sigue buenas prácticas de diseño de Nova Tools para mantener la mantenibilidad del sistema.

---

## 🚀 Resultado Esperado

Un tablero accesible desde el detalle de un servicio en Laravel Nova que permita:

- Ver y administrar el dispositivo ONU del cliente usando SmartOLT.
- Realizar acciones como reboot, cambio de perfil de velocidad, modo WAN, etc.
- Mostrar gráficas de señal y tráfico del dispositivo.

---

Para cualquier duda adicional, puedes revisar los documentos en la carpeta `docs/` o consultar con el líder técnico.

¡Éxito en la implementación!
