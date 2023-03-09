<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
        //Modificar el Request
        $request->request->add(['username' => Str::slug($request->username)]);

        $this->validate($request, [
            'username' => ['required', 'unique:users,username,' . auth()->user()->id, 'min:3', 'max:30', 'not_in:twitter,editar-perfil'],
            'email' => ['required', 'unique:users,email,' . auth()->user()->id, 'email', 'max:60'],
        ], [
            'username.required' => 'Es obligatorio escribir un nombre de usuario',
            'username.unique' => 'El nombre de usuario ya existe',
            'username.min' => 'El nombre de usuario no puede tener menos de 3 caracteres',
            'username.max' => 'El nombre de usuario no puede tener un max. de 30 caracteres',
            'email.required' => 'Es obligatorio escribir un email',
            'email.unique' => 'El email ya esta en uso',
            'email.email' => 'Introduce un email valido',
            'email.max' => 'El email solo puede contener un max. de 60 caracteres'
        ]);

        if ($request->imagen) {
            $imagen = $request->file('imagen');

            $nombreImagen = Str::uuid() . "." . $imagen->extension();

            $imagenServidor = Image::make($imagen);
            $imagenServidor->fit(1000, 1000);

            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
            $imagenServidor->save($imagenPath);
        }

        //Guardar cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        // Cambiar la contraseña
        if ($request->oldpassword || $request->password) {
            $this->validate($request, [
                'oldpassword' => 'required|min:6',
                'password' => 'required|confirmed|min:6'
            ], [
                'oldpassword.required' => 'Es obligatorio escribir una contraseña',
                'oldpassword.min' => 'La contraseña solo puede ser de 6 caracteres',
                'password.required' => 'Es obligatorio escribir una contraseña',
                'password.confirmed' => 'Las contraseñas no coinciden',
                'password.min' => 'La contraseña solo puede ser de 6 caracteres',
            ]);
            if (Hash::check($request->oldpassword, auth()->user()->password)) {
                $usuario->password = Hash::make($request->password) ?? auth()->user()->password;
            } else {
                return back()->with('mensaje', 'La contraseña actual no coincide');
            }
        }
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;
        $usuario->save();


        //Redireccionar
        return redirect()->route('post.index', $usuario->username);
    }
}
