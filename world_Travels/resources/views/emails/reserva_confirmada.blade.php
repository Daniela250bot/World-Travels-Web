<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Reserva</title>
</head>
<body>
    <h1>¡Reserva Confirmada!</h1>
    <p>Hola {{ $reserva->usuario->name }},</p>
    <p>Tu reserva para la actividad "{{ $reserva->actividad->Nombre_Actividad }}" ha sido confirmada.</p>
    <p><strong>Detalles de la reserva:</strong></p>
    <ul>
        <li>Fecha: {{ $reserva->Fecha_Reserva }}</li>
        @if($reserva->hora)
        <li>Hora: {{ $reserva->hora }}</li>
        @endif
        <li>Número de personas: {{ $reserva->Numero_Personas }}</li>
    </ul>
    <p>¡Gracias por elegir World Travels!</p>
</body>
</html>