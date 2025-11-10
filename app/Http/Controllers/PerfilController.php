<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load(['habilidades.categoria', 'logros']);

        $habilidades = $user->habilidades;
        
        $truequesCompletados = $user->truequesOfrecidos()->where('estado', 'completado')->count() 
            + $user->truequesRecibidos()->where('estado', 'completado')->count();

        $transacciones = $user->transaccionesPuntos()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('perfil.index', compact('user', 'habilidades', 'truequesCompletados', 'transacciones'));
    }

    public function show(User $user)
    {
        $usuario = $user;
        $usuario->load(['habilidades.categoria', 'logros']);

        $habilidades = $usuario->habilidades()->where('estado', 'aprobada')->get();
        
        $truequesCompletados = $usuario->truequesOfrecidos()->where('estado', 'completado')->count() 
            + $usuario->truequesRecibidos()->where('estado', 'completado')->count();

        // Últimas valoraciones recibidas (podemos limitar para rendimiento)
        $valoracionesRecibidas = $usuario->valoracionesRecibidas()
            ->with(['evaluador', 'trueque'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $totalValoraciones = $usuario->valoracionesRecibidas()->count();

        return view('perfil.show', compact(
            'usuario',
            'habilidades',
            'truequesCompletados',
            'valoracionesRecibidas',
            'totalValoraciones'
        ));
    }

    public function editar()
    {
        return view('perfil.editar', ['user' => Auth::user()]);
    }

    public function actualizar(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Perfil actualizado correctamente');
    }

    public function cambiarPassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta']);
        }

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente');
    }

    public function transacciones()
    {
        $transacciones = Auth::user()->transaccionesPuntos()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalGanado = Auth::user()->transaccionesPuntos()
            ->where('tipo', 'ganado')
            ->sum('cantidad');

        $totalGastado = Auth::user()->transaccionesPuntos()
            ->where('tipo', 'gastado')
            ->sum('cantidad');

        return view('perfil.transacciones', compact('transacciones', 'totalGanado', 'totalGastado'));
    }
}
