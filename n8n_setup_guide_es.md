# Guía de Instalación n8n - Glow Belleza

Esta guía te ayudará a implementar el sistema de notificaciones "perfecto" en tu n8n.

### 1. Instrucciones de Importación
1.  Abre tu n8n (`https://nathacc18.app.n8n.cloud/`).
2.  Crea un nuevo Workflow.
3.  Copia el contenido del archivo `n8n_ultimate_workflow.json` (está en tu carpeta del proyecto).
4.  Pega el contenido directamente en el lienzo de n8n o usa la opción **Import from File**.

### 2. Configuración de Nodos
- **Nodo de Webhook:** Ya está configurado con la ruta `formulario-contacto`.
- **Nodo de Gmail:** 
    - Haz clic en el nodo **Enviar Gmail**.
    - En la sección **Credentials**, selecciona tus credenciales SMTP o crea unas nuevas usando tu cuenta de Gmail (debes usar una "Contraseña de Aplicación" si tienes 2FA).
- **Nodo de WhatsApp:** 
    - Este nodo usa una petición HTTP genérica.
    - Debes configurar la URL y las credenciales según el proveedor de WhatsApp que uses (Twilio, Meta Business API, etc.).

### 3. Activación
1.  Haz clic en el botón superior **Execute Workflow** para probar (recuerda que el modo `-test` solo dura una ejecución).
2.  Para que funcione SIEMPRE de forma automática, haz clic en el interruptor **Active** (arriba a la derecha).
3.  Una vez activado, cambia el `$env = 'test'` a `$env = 'prod'` en el archivo `n8n_send_data.php` de tu proyecto.

---
*Hecho con precisión para Glow Belleza.*
