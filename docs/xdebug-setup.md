# Guía de Configuración de Xdebug en ISP Go

Esta guía te ayudará a configurar y usar Xdebug para depurar tu aplicación Laravel en este proyecto.

## Requisitos Previos

- Laravel Sail instalado y funcionando
- Docker Desktop o Docker Engine ejecutándose
- Un IDE compatible con Xdebug (VS Code, PhpStorm, etc.)

## Configuración en el Proyecto

### 1. Habilitar Xdebug en Laravel Sail

Edita tu archivo `.env` y agrega las siguientes variables:

```env
SAIL_XDEBUG_MODE=develop,debug
SAIL_XDEBUG_CONFIG="client_host=host.docker.internal client_port=9003"
```

**⚠️ Importante:** El valor de `SAIL_XDEBUG_CONFIG` debe estar entre comillas porque contiene espacios. Sin las comillas, el parser de `.env` fallará y la aplicación no iniciará.

**Explicación de los modos:**
- `develop`: Habilita funciones de desarrollo (como `var_dump()` mejorado)
- `debug`: Habilita el depurador remoto
- `coverage`: Para generar reportes de cobertura de código (opcional)

**Puerto por defecto:**
- Xdebug 3.x usa el puerto **9003** por defecto
- Si usas Xdebug 2.x, el puerto es **9000**

### 2. Reiniciar los Contenedores

Después de modificar el `.env`, reinicia los contenedores:

```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
```

O simplemente:

```bash
./vendor/bin/sail restart
```

### 3. Verificar que Xdebug está Habilitado

Puedes verificar que Xdebug está activo ejecutando:

```bash
./vendor/bin/sail php -v
```

Deberías ver información sobre Xdebug en la salida. También puedes verificar con:

```bash
./vendor/bin/sail php -m | grep xdebug
```

## Configuración del IDE

### VS Code

1. **Instalar la extensión PHP Debug**
   - Abre VS Code
   - Ve a Extensiones (Ctrl+Shift+X)
   - Busca "PHP Debug" de Xdebug
   - Instálala

2. **Crear configuración de launch.json**
   
   Crea o edita `.vscode/launch.json` en la raíz del proyecto:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html": "${workspaceFolder}"
            },
            "log": true,
            "xdebugSettings": {
                "max_data": 65535,
                "show_hidden": 1,
                "max_children": 100,
                "max_depth": 5
            }
        },
        {
            "name": "Launch currently open script",
            "type": "php",
            "request": "launch",
            "program": "${file}",
            "cwd": "${fileDirname}",
            "port": 9003,
            "pathMappings": {
                "/var/www/html": "${workspaceFolder}"
            }
        }
    ]
}
```

3. **Configurar pathMappings**
   
   Asegúrate de que el mapeo de rutas sea correcto:
   - Ruta en el contenedor: `/var/www/html`
   - Ruta local: `${workspaceFolder}` (ruta de tu proyecto)

### PhpStorm

1. **Configurar el servidor**
   - Ve a `File > Settings > PHP > Servers`
   - Crea un nuevo servidor:
     - Name: `Laravel Sail`
     - Host: `localhost`
     - Port: `80` (o el puerto que uses en `APP_PORT`)
     - Debugger: `Xdebug`
     - Use path mappings: ✅ (marcado)
     - Mapea `/var/www/html` a tu ruta local del proyecto

2. **Configurar Xdebug**
   - Ve a `File > Settings > PHP > Debug`
   - Xdebug port: `9003`
   - Can accept external connections: ✅ (marcado)

3. **Iniciar la sesión de depuración**
   - Haz clic en el botón "Start Listening for PHP Debug Connections" (teléfono con escucha)
   - O usa el atajo: `Ctrl+Shift+F9` (Windows/Linux) o `Cmd+Shift+F9` (Mac)

## Uso de Xdebug

### Establecer Breakpoints

1. **En VS Code:**
   - Haz clic en el margen izquierdo del editor junto al número de línea
   - O coloca el cursor en la línea y presiona `F9`

2. **En PhpStorm:**
   - Haz clic en el margen izquierdo junto al número de línea
   - O coloca el cursor y presiona `Ctrl+F8`

### Iniciar la Depuración

1. **Desde el navegador:**
   - Instala una extensión del navegador para Xdebug:
     - **Chrome/Edge**: Xdebug Helper
     - **Firefox**: Xdebug Helper
   - Activa el modo "Debug" en la extensión
   - Navega a tu aplicación Laravel
   - Los breakpoints se activarán automáticamente

2. **Desde el IDE:**
   - Inicia la sesión de depuración en tu IDE
   - Abre tu aplicación en el navegador
   - Los breakpoints se activarán cuando se ejecute el código

3. **Desde la línea de comandos (Artisan):**
   - Ejecuta comandos con Xdebug habilitado:
   ```bash
   ./vendor/bin/sail artisan route:list
   ```
   - Los breakpoints se activarán si el IDE está escuchando

### Depuración de Tests

Para depurar tests de PHPUnit:

```bash
# Con Xdebug habilitado, los breakpoints funcionarán en los tests
./vendor/bin/sail artisan test --filter NombreDelTest
```

O directamente con PHPUnit:

```bash
./vendor/bin/sail php vendor/bin/phpunit --filter NombreDelTest
```

### Depuración de Comandos Artisan

1. Establece breakpoints en tu comando
2. Inicia la sesión de depuración en tu IDE
3. Ejecuta el comando:
   ```bash
   ./vendor/bin/sail artisan tu:comando
   ```

## Configuración Avanzada

### Personalizar el Puerto de Xdebug

Si necesitas cambiar el puerto (por ejemplo, si 9003 está ocupado):

1. En `.env`:
```env
SAIL_XDEBUG_CONFIG="client_host=host.docker.internal client_port=9004"
```

2. En tu IDE, cambia el puerto a `9004` en la configuración

3. Reinicia los contenedores:
```bash
./vendor/bin/sail restart
```

### Optimizar el Rendimiento

Xdebug puede ralentizar la aplicación. Para desarrollo diario, considera:

1. **Deshabilitar Xdebug cuando no lo uses:**
```env
SAIL_XDEBUG_MODE=off
```

2. **Usar solo el modo develop (sin debug):**
```env
SAIL_XDEBUG_MODE=develop
```

3. **Habilitar solo cuando necesites depurar:**
```env
SAIL_XDEBUG_MODE=debug
```

### Variables de Entorno Adicionales

Puedes agregar más configuraciones de Xdebug en `.env`:

```env
SAIL_XDEBUG_CONFIG="client_host=host.docker.internal client_port=9003 start_with_request=yes"
```

**Opciones útiles:**
- `start_with_request=yes`: Inicia la sesión de depuración automáticamente
- `start_with_request=trigger`: Solo inicia con el trigger (recomendado)
- `idekey=PHPSTORM`: Especifica la clave del IDE

## Solución de Problemas

### Xdebug no se conecta

1. **Verifica que Xdebug esté habilitado:**
   ```bash
   ./vendor/bin/sail php -m | grep xdebug
   ```

2. **Verifica el puerto:**
   ```bash
   ./vendor/bin/sail php -i | grep xdebug.client_port
   ```

3. **Verifica que el IDE esté escuchando:**
   - En VS Code: Debe aparecer "Listening for Xdebug" en la barra de estado
   - En PhpStorm: El botón de teléfono debe estar activo

4. **Verifica el firewall:**
   - Asegúrate de que el puerto 9003 no esté bloqueado

### Los breakpoints no se activan

1. **Verifica el mapeo de rutas:**
   - Asegúrate de que las rutas en el IDE coincidan con las del contenedor

2. **Verifica que el código se ejecute:**
   - Asegúrate de que el código con el breakpoint realmente se ejecute

3. **Limpia la caché de Laravel:**
   ```bash
   ./vendor/bin/sail artisan cache:clear
   ./vendor/bin/sail artisan config:clear
   ```

### Xdebug es muy lento

1. **Deshabilita Xdebug cuando no lo uses:**
   ```env
   SAIL_XDEBUG_MODE=off
   ```

2. **Usa solo el modo necesario:**
   - `develop` es más rápido que `debug`
   - `coverage` es el más lento

3. **Limita la profundidad de inspección:**
   - En la configuración del IDE, reduce `max_depth`

## Ejemplos Prácticos

### Depurar un Controlador

1. Abre el archivo del controlador (ej: `app/Http/Controllers/CustomerController.php`)
2. Establece un breakpoint en el método que quieres depurar
3. Inicia la sesión de depuración en tu IDE
4. Navega a la ruta que usa ese controlador
5. El IDE se detendrá en el breakpoint

### Depurar un Servicio

1. Abre el archivo del servicio (ej: `app/Services/Billing/CustomerBillingService.php`)
2. Establece breakpoints en los métodos relevantes
3. Inicia la sesión de depuración
4. Ejecuta la acción que usa ese servicio (desde la web o Artisan)
5. Inspecciona las variables y el flujo de ejecución

### Depurar un Evento/Listener

1. Establece breakpoints en el evento y el listener
2. Inicia la sesión de depuración
3. Dispara el evento desde tu aplicación
4. Depura tanto el evento como el listener

## Recursos Adicionales

- [Documentación oficial de Xdebug](https://xdebug.org/docs/)
- [Laravel Sail Documentation](https://laravel.com/docs/sail)
- [VS Code PHP Debug Extension](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug)
- [PhpStorm Xdebug Guide](https://www.jetbrains.com/help/phpstorm/configuring-xdebug.html)

## Notas Importantes

- Xdebug puede ralentizar significativamente la aplicación
- Deshabilita Xdebug en producción
- El puerto 9003 debe estar disponible en tu máquina local
- Los path mappings deben coincidir exactamente entre el contenedor y tu IDE
