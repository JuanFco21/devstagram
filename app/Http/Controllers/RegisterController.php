<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    public function index() {
        return view('auth.register');
    }

    public function store(Request $request) {
        //dd($request);
        //dd($request->get('username'));

        //Modificar el Request
        $request->request->add(['username' => Str::slug($request->username)]);

        //Validacion
        $this->validate($request, [
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:30',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6'
        ],[
            'name.required' => 'Es obligatorio escribir tu nombre completo',
            'name.max' => 'El nombre solo puede contener un max. de 30 caracteres',
            'username.required' => 'Es obligatorio escribir un nombre de usuario',
            'username.unique' => 'El nombre de usuario ya existe',
            'username.min' => 'El nombre de usuario no puede tener menos de 3 caracteres',
            'username.max' => 'El nombre de usuario no puede tener un max. de 30 caracteres',
            'email.required' => 'Es obligatorio escribir un email',
            'email.unique' => 'El email ya esta en uso',
            'email.email' => 'Introduce un email valido',
            'email.max' => 'El email solo puede contener un max. de 60 caracteres',
            'password.required' => 'Es obligatorio escribir una contraseña',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña solo puede ser de 6 caracteres',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        //Autenticar un usuario
        auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        //Otra forma de autenticar
        //auth()->attempt($request->only('email', 'password'));

        //Redireccionar
        return redirect()->route('post.index', auth()->user()->username);
    }
}
