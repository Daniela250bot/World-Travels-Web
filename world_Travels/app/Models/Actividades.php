<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
{
     protected $table = 'actividades';
     protected $fillable = [
          'Nombre_Actividad',
          'Descripcion',
          'Fecha_Actividad',
          'Hora_Actividad',
          'Precio',
          'Cupo_Maximo',
          'Ubicacion',
          'Imagen',
          'idCategoria',
          'idUsuario',
          'idMunicipio',
          'empresa_id'
      ];

    public function categoria()
    {
        return $this->belongsTo(Category::class, 'idCategoria');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'idUsuario');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipios::class, 'idMunicipio');
    }

    public function reservas()
    {
        return $this->hasMany(Reservas::class, 'idActividad');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentarios::class, 'idActividad');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
