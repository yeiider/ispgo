<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Solicitud - Raíces</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333333; background-color: #f4f4f4; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #0047AB 0%, #0066CC 100%); padding: 40px 20px; text-align: center; }
        .logo { font-size: 32px; font-weight: bold; color: #ffffff; margin-bottom: 10px; letter-spacing: 2px; }
        .tagline { color: #ffffff; font-size: 14px; opacity: 0.9; }
        .success-badge { background-color: #ffffff; margin: -30px auto 0; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        .checkmark { width: 40px; height: 40px; border-radius: 50%; background-color: #10B981; position: relative; }
        .checkmark::after { content: '✓'; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #ffffff; font-size: 28px; font-weight: bold; }
        .content { padding: 50px 30px 30px; }
        .title { font-size: 28px; font-weight: bold; color: #0047AB; text-align: center; margin-bottom: 15px; }
        .subtitle { font-size: 16px; color: #666666; text-align: center; margin-bottom: 30px; }
        .greeting { font-size: 18px; color: #333333; margin-bottom: 20px; }
        .message { font-size: 15px; color: #555555; margin-bottom: 30px; line-height: 1.8; }
        .plan-details { background: linear-gradient(135deg, #0047AB 0%, #0066CC 100%); border-radius: 12px; padding: 25px; margin: 30px 0; color: #ffffff; }
        .plan-header { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; margin-bottom: 10px; }
        .plan-name { font-size: 32px; font-weight: bold; margin-bottom: 15px; color: #FFD700; }
        .plan-info { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.2); }
        .plan-speed { font-size: 24px; font-weight: bold; }
        .plan-price { font-size: 28px; font-weight: bold; }
        .customer-info { background-color: #F9FAFB; border-radius: 8px; padding: 20px; margin: 25px 0; }
        .info-title { font-size: 16px; font-weight: bold; color: #0047AB; margin-bottom: 15px; }
        .info-row { display: flex; padding: 8px 0; border-bottom: 1px solid #E5E7EB; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: #666666; width: 120px; font-size: 14px; }
        .info-value { color: #333333; flex: 1; font-size: 14px; }
        .next-steps { background-color: #FEF3C7; border-left: 4px solid #F59E0B; padding: 20px; margin: 25px 0; border-radius: 4px; }
        .next-steps-title { font-size: 16px; font-weight: bold; color: #92400E; margin-bottom: 10px; }
        .next-steps-list { list-style: none; padding: 0; }
        .next-steps-list li { padding: 8px 0; color: #78350F; font-size: 14px; padding-left: 25px; position: relative; }
        .next-steps-list li::before { content: '→'; position: absolute; left: 0; color: #F59E0B; font-weight: bold; }
        .contact-section { background-color: #F3F4F6; padding: 25px; text-align: center; margin-top: 30px; }
        .contact-title { font-size: 18px; font-weight: bold; color: #0047AB; margin-bottom: 15px; }
        .contact-methods { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; margin-top: 15px; }
        .contact-item { text-align: center; }
        .contact-icon { font-size: 24px; margin-bottom: 5px; }
        .contact-label { font-size: 12px; color: #666666; margin-bottom: 3px; }
        .contact-value { font-size: 14px; font-weight: 600; color: #0047AB; text-decoration: none; }
        .footer { background-color: #1F2937; color: #ffffff; padding: 30px 20px; text-align: center; }
        .footer-logo { font-size: 24px; font-weight: bold; margin-bottom: 10px; letter-spacing: 1px; }
        .footer-text { font-size: 13px; color: #9CA3AF; margin: 5px 0; }
        .social-links { margin-top: 20px; }
        .social-links a { color: #ffffff; text-decoration: none; margin: 0 10px; font-size: 14px; }
        @media only screen and (max-width: 600px) {
            .content { padding: 40px 20px 20px; }
            .plan-info { flex-direction: column; gap: 15px; text-align: center; }
            .contact-methods { flex-direction: column; gap: 15px; }
            .info-row { flex-direction: column; }
            .info-label { width: 100%; margin-bottom: 5px; }
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <div class="logo">RAÍCES</div>
        <div class="tagline">Tu conexión al futuro</div>
    </div>

    <div class="content">
        <div class="success-badge">
            <div class="checkmark"></div>
        </div>

        <h1 class="title">¡Solicitud Recibida!</h1>
        <p class="subtitle">Tu orden ha sido aceptada exitosamente</p>

        <p class="greeting">Hola <strong>{{ $nombre }} {{ $apellido }}</strong>,</p>

        <p class="message">
            ¡Gracias por elegir Raíces! Nos complace informarte que hemos recibido tu solicitud de contratación
            y nuestro equipo ya está trabajando para procesar tu orden. Estamos emocionados de que formes parte
            de nuestra familia y pronto disfrutes de la mejor conexión a internet.
        </p>

        <div class="plan-details">
            <div class="plan-header">Plan Seleccionado</div>
            <div class="plan-name">{{ $plan_nombre }}</div>
            <div class="plan-info">
                <div class="plan-speed">{{ $plan_velocidad }}</div>
                <div class="plan-price">{{ $plan_precio }}</div>
            </div>
        </div>

        <div class="customer-info">
            <div class="info-title">Información de Contacto</div>
            <div class="info-row">
                <span class="info-label">Nombre:</span>
                <span class="info-value">{{ $nombre }} {{ $apellido }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Correo:</span>
                <span class="info-value">{{ $email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                <span class="info-value">{{ $telefono }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Dirección:</span>
                <span class="info-value">{{ $direccion }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ciudad:</span>
                <span class="info-value">{{ $ciudad }}</span>
            </div>
        </div>

        <div class="next-steps">
            <div class="next-steps-title">📋 Próximos Pasos</div>
            <ul class="next-steps-list">
                <li>Nuestro equipo verificará la cobertura en tu zona</li>
                <li>Te contactaremos en las próximas 24-48 horas</li>
                <li>Coordinaremos la fecha de instalación</li>
                <li>Un técnico especializado realizará la instalación</li>
            </ul>
        </div>

        <div class="contact-section">
            <div class="contact-title">¿Necesitas ayuda?</div>
            <p style="color: #666666; font-size: 14px; margin-bottom: 15px;">
                Nuestro equipo está disponible para atenderte
            </p>
            <div class="contact-methods">
                <div class="contact-item">
                    <div class="contact-icon">📱</div>
                    <div class="contact-label">WhatsApp</div>
                    <a href="https://wa.me/6028912989" class="contact-value">(602) 891 2989</a>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">📞</div>
                    <div class="contact-label">Teléfono</div>
                    <a href="tel:3147347051" class="contact-value">314 734 7051</a>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">🌐</div>
                    <div class="contact-label">Web</div>
                    <a href="https://www.raicesc.net" class="contact-value">www.raicesc.net</a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-logo">RAÍCES</div>
        <p class="footer-text">Tu conexión al futuro</p>
        <p class="footer-text">© 2025 Raíces Comunicaciones. Todos los derechos reservados.</p>
        <div class="social-links">
            <a href="#">Facebook</a>
            <a href="#">Instagram</a>
            <a href="#">TikTok</a>
        </div>
    </div>
</div>
</body>
</html>
