<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reserva en el Sistema</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #dc2626; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background-color: #f8fafc; padding: 20px; border-radius: 0 0 8px 8px; }
        .reserva-info { background-color: white; padding: 15px; border-radius: 6px; margin: 15px 0; border-left: 4px solid #dc2626; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Nueva Reserva Registrada</h1>
            <p>WORLD TRAVELS - Sistema Administrativo</p>
        </div>

        <div class="content">
            <p><strong>Administrador,</strong></p>

            <p>Se ha registrado una nueva reserva en el sistema WORLD TRAVELS. Revisa los detalles a continuación:</p>

            <div class="reserva-info">
                <h3>📋 Información de la Reserva</h3>
                <p><strong>ID de Reserva:</strong> #{{ $reserva->id }}</p>
                <p><strong>Fecha de creación:</strong> {{ $reserva->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Estado:</strong>
                    @if($reserva->Estado === 'confirmada')
                        <span style="color: #10b981;">Confirmada</span>
                    @elseif($reserva->Estado === 'pendiente')
                        <span style="color: #f59e0b;">Pendiente</span>
                    @else
                        <span style="color: #ef4444;">{{ ucfirst($reserva->Estado) }}</span>
                    @endif
                </p>
            </div>

            <div class="reserva-info">
                <h3>🎯 Actividad Reservada</h3>
                <p><strong>Nombre:</strong> {{ $actividad->Nombre_Actividad }}</p>
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->Fecha_Reserva)->format('d/m/Y') }}</p>
                <p><strong>Hora:</strong> {{ $reserva->hora ?? 'N/A' }}</p>
                <p><strong>Ubicación:</strong> {{ $actividad->Ubicacion }}</p>
                <p><strong>Número de personas:</strong> {{ $reserva->Numero_Personas }}</p>
                <p><strong>Precio por persona:</strong> ${{ number_format($actividad->Precio, 0, ',', '.') }}</p>
                <p><strong>Total:</strong> ${{ number_format($actividad->Precio * $reserva->Numero_Personas, 0, ',', '.') }}</p>
                @if($reserva->notas)
                    <p><strong>Notas:</strong> {{ $reserva->notas }}</p>
                @endif
            </div>

            <div class="reserva-info">
                <h3>👤 Información del Turista</h3>
                <p><strong>Nombre completo:</strong> {{ $turista->Nombre }} {{ $turista->Apellido }}</p>
                <p><strong>Email:</strong> {{ $turista->Email }}</p>
                <p><strong>Teléfono:</strong> {{ $turista->Telefono ?? 'No proporcionado' }}</p>
                <p><strong>Nacionalidad:</strong> {{ $turista->Nacionalidad ?? 'No especificada' }}</p>
            </div>

            @if($empresa)
            <div class="reserva-info">
                <h3>🏢 Empresa Responsable</h3>
                <p><strong>Nombre:</strong> {{ $empresa->nombre }}</p>
                <p><strong>Email:</strong> {{ $empresa->correo }}</p>
                <p><strong>Teléfono:</strong> {{ $empresa->telefono ?? 'No disponible' }}</p>
            </div>
            @endif

            <p><strong>Acciones recomendadas:</strong></p>
            <ul>
                <li>Verificar que la empresa haya recibido la notificación</li>
                <li>Monitorear el estado de la reserva</li>
                <li>Intervenir si hay problemas con la confirmación</li>
                <li>Revisar reportes de ingresos cuando se confirme la reserva</li>
            </ul>

            <p>Para gestionar esta reserva, accede al panel administrativo de WORLD TRAVELS.</p>

            <p>Atentamente,<br>
            <strong>Sistema WORLD TRAVELS</strong></p>
        </div>

        <div class="footer">
            <p>Este es un mensaje automático del sistema WORLD TRAVELS.</p>
            <p>Por favor, no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>