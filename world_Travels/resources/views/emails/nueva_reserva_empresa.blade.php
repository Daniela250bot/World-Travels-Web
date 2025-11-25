<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reserva Recibida</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2563eb; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background-color: #f8fafc; padding: 20px; border-radius: 0 0 8px 8px; }
        .reserva-info { background-color: white; padding: 15px; border-radius: 6px; margin: 15px 0; border-left: 4px solid #2563eb; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Nueva Reserva Recibida!</h1>
            <p>WORLD TRAVELS - Sistema de Reservas Turísticas</p>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $actividad->empresa->nombre ?? 'Empresa' }}</strong>,</p>

            <p>Has recibido una nueva reserva para tu actividad. Aquí están los detalles:</p>

            <div class="reserva-info">
                <h3>📅 Detalles de la Reserva</h3>
                <p><strong>Actividad:</strong> {{ $actividad->Nombre_Actividad }}</p>
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->Fecha_Reserva)->format('d/m/Y') }}</p>
                <p><strong>Hora:</strong> {{ $reserva->hora ?? 'N/A' }}</p>
                <p><strong>Número de personas:</strong> {{ $reserva->Numero_Personas }}</p>
                <p><strong>Estado:</strong>
                    @if($reserva->Estado === 'confirmada')
                        <span style="color: #10b981;">{{ ucfirst($reserva->Estado) }}</span>
                    @elseif($reserva->Estado === 'pendiente')
                        <span style="color: #f59e0b;">{{ ucfirst($reserva->Estado) }}</span>
                    @else
                        <span style="color: #ef4444;">{{ ucfirst($reserva->Estado) }}</span>
                    @endif
                </p>
                @if($reserva->notas)
                    <p><strong>Notas:</strong> {{ $reserva->notas }}</p>
                @endif
            </div>

            <div class="reserva-info">
                <h3>👤 Información del Turista</h3>
                <p><strong>Nombre:</strong> {{ $turista->Nombre }} {{ $turista->Apellido }}</p>
                <p><strong>Email:</strong> {{ $turista->Email }}</p>
                <p><strong>Teléfono:</strong> {{ $turista->Telefono ?? 'No proporcionado' }}</p>
            </div>

            <div class="reserva-info">
                <h3>💰 Información de Pago</h3>
                <p><strong>Precio por persona:</strong> ${{ number_format($actividad->Precio, 0, ',', '.') }}</p>
                <p><strong>Total a cobrar:</strong> ${{ number_format($actividad->Precio * $reserva->Numero_Personas, 0, ',', '.') }}</p>
            </div>

            <p><strong>Acciones requeridas:</strong></p>
            <ul>
                <li>Revisa los detalles de la reserva en tu panel de empresa</li>
                <li>Confirma la disponibilidad y contacta al turista si es necesario</li>
                <li>Actualiza el estado de la reserva según corresponda</li>
            </ul>

            <p>Para gestionar esta reserva, ingresa a tu cuenta en WORLD TRAVELS.</p>

            <p>¡Gracias por ser parte de WORLD TRAVELS!</p>
        </div>

        <div class="footer">
            <p>Este es un mensaje automático del sistema WORLD TRAVELS.</p>
            <p>Por favor, no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>