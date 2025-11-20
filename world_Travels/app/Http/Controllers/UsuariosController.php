<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    public function index()
    {
        $usuarios = Usuarios::all();
        return response()->json($usuarios);
    }
    
   public function store(Request $request)
   {
        $validator = Validator::make($request->all(),[
        'Nombre'=> 'required|string|max:255',
        'Apellido'=> 'required|string|max:255',
        'Email'=> 'required|string|email|max:255|unique:usuarios',
        'Contraseña'=> 'required|string|min:8',
        'Telefono'=> 'required|string|max:20',
        'Nacionalidad'=> 'required|string|max:255',
        'Rol'=> 'required|string|in:Turista,Guía Turístico,Administrador',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 422);
         }

        $data = $validator->validated();
        $data['Contraseña'] = bcrypt($data['Contraseña']);
        $usuarios = Usuarios::create($data);
        return response()->json($usuarios,201);
   }

     public function show(string $id)   
     {
        $usuarios = Usuarios::find($id);

        if (!$usuarios) { 
            return response()->json(['menssage'=> 'Usuario no encontrado'], 404);
        }

        return response()->json($usuarios);
    }

    public function update(Request $request, string $id)  
     {
          $usuarios = Usuarios::find($id);

          if (!$usuarios) { 
            return response()->json(['menssage'=> 'Usuario no encontrado para editar '], 404);
        }

         $validator = Validator::make($request->all(),[
         'Nombre'=> 'nullable|string|max:255',
         'Apellido'=> 'nullable|string|max:255',
         'Email'=> 'nullable|string|email|max:255|unique:usuarios,email,'.$id,
         'Contraseña'=> 'nullable|string|min:8',
         'Telefono'=> 'nullable|string|max:20',
         'Nacionalidad'=> 'nullable|string|max:255',
         'Rol'=> 'nullable|string|in:Turista,Guía Turístico,Administrador',
        ]);
        

          if ($validator-> fails()) {
         return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();
        if (!empty($data['Contraseña'])) {
            $data['Contraseña'] = bcrypt($data['Contraseña']);
        } else {
            unset($data['Contraseña']);
        }
        $usuarios->update($data);
        return response()->json($usuarios); 
    }

     public function destroy (string $id)
    {
         $usuarios = Usuarios::find($id);
          if (!$usuarios) { 
            return response()->json(['menssage'=> 'Usuario no encontrado para eliminar '], 404);
        }
          $usuarios->delete();
          return response()->json(['message' => 'Usuarios eliminado con exito']); 
    } 
     
}

