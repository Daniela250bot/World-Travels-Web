<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservas extends Model
{
     protected $table = 'reservas';
     protected $fillable = [
          'Fecha_Reserva',
          'hora',
          'Numero_Personas',
          'Estado',
          'idUsuario',
          'idActividad',
          'notas'
      ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividades::class, 'idActividad');
    }

    public function comentarios()
    {
        return $this->hasMany(ComentariosReserva::class, 'id_reserva');
    }
}
