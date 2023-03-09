<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function store(Request $request, User $user, Post $post)
    {
        //dd('Comentario');

        //Validar
        $this->validate($request, [
            'comentario' => 'required|max:255'
        ],[
            'comentario.required' => 'Tienes que escribir un comentario para que se publique',
            'comentario.max' => 'El comentario solo puede tener un max. de 255 caracteres',
        ]);

        //Almacenar el resultado
        Comentario::create([
            'user_id' => auth()->user()->id,
            'post_id' => $post->id,
            'comentario' => $request->comentario
        ]);

        //Imprimir un mensaje
        return back()->with('mensaje', 'Comentario realizado correctamente');
    }
}
