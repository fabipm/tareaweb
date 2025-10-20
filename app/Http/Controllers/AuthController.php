<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Prevent caching of the login page so stale CSRF tokens are not reused
        return response()->view('auth.login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'codigo_usuario' => 'required|string',
            'clave' => 'required|string',
        ]);

        $usuario = Usuario::where('codigo_usuario', $credentials['codigo_usuario'])
            ->where('estado', 1)
            ->first();

        if ($usuario && $usuario->clave === md5($credentials['clave'])) {
            Auth::login($usuario);
            // Regenerate session AFTER login to prevent session fixation
            $request->session()->regenerate();
            if ($usuario->rol === 'Administrador') {
                return redirect()->route('dashboard.admin');
            } else {
                return redirect()->route('dashboard.estudiante');
            }
        }

        return back()->withErrors(['codigo_usuario' => 'Credenciales incorrectas'])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        // Invalidate session and regenerate token
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'codigo_usuario' => 'required|digits:10|unique:usuarios,codigo_usuario',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,correo',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $usuario = Usuario::create([
            'codigo_usuario' => $data['codigo_usuario'],
            'nombre_completo' => $data['name'],
            'correo' => $data['email'],
            'clave' => md5($data['password']),
            'rol' => 'Estudiante',
            'estado' => 1,
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('dashboard.estudiante');
    }
}

