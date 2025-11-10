<?php

namespace App\Http\Controllers;

use App\Models\Habilidad;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HabilidadController extends Controller
{
    /**
     * Mostrar listado de habilidades
     */
    public function index()
    {
        $habilidades = Habilidad::with(['usuario', 'categoria'])
            ->aprobadas()
            ->latest()
            ->paginate(12);

        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('habilidades.index', compact('habilidades', 'categorias'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('habilidades.create', compact('categorias'));
    }

    /**
     * Almacenar nueva habilidad
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:150',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'required|max:1000',
            'horas_ofrecidas' => 'required|integer|min:1|max:100',
            'puntos_sugeridos' => 'required|integer|min:1|max:1000',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('habilidades', 'public');
            $validated['imagen'] = $path;
        }

        $validated['usuario_id'] = Auth::id();
        $validated['estado'] = 'aprobado'; // Auto-aprobar por ahora

        $habilidad = Habilidad::create($validated);

        return redirect()
            ->route('habilidades.show', $habilidad)
            ->with('success', '¡Habilidad creada exitosamente!');
    }

    /**
     * Mostrar detalle de habilidad
     */
    public function show(Habilidad $habilidad)
    {
        // Incrementar contador de visitas
        $habilidad->increment('visitas');

        // Cargar relaciones necesarias
        $habilidad->load(['usuario', 'categoria']);

        // Obtener habilidades relacionadas de la misma categoría
        $relacionadas = Habilidad::where('categoria_id', $habilidad->categoria_id)
            ->where('id', '!=', $habilidad->id)
            ->aprobadas()
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('habilidades.show', compact('habilidad', 'relacionadas'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Habilidad $habilidad)
    {
        // Verificar que el usuario sea el dueño
        if ($habilidad->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta habilidad');
        }

        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('habilidades.edit', compact('habilidad', 'categorias'));
    }

    /**
     * Actualizar habilidad
     */
    public function update(Request $request, Habilidad $habilidad)
    {
        // Verificar que el usuario sea el dueño
        if ($habilidad->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para actualizar esta habilidad');
        }

        $validated = $request->validate([
            'titulo' => 'required|max:150',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'required|max:1000',
            'horas_ofrecidas' => 'required|integer|min:1|max:100',
            'puntos_sugeridos' => 'required|integer|min:1|max:1000',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($habilidad->imagen) {
                Storage::disk('public')->delete($habilidad->imagen);
            }
            $path = $request->file('imagen')->store('habilidades', 'public');
            $validated['imagen'] = $path;
        }

        $habilidad->update($validated);

        return redirect()
            ->route('habilidades.show', $habilidad)
            ->with('success', '¡Habilidad actualizada exitosamente!');
    }

    /**
     * Eliminar habilidad
     */
    public function destroy(Habilidad $habilidad)
    {
        // Verificar que el usuario sea el dueño
        if ($habilidad->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta habilidad');
        }

        // Eliminar imagen si existe
        if ($habilidad->imagen) {
            Storage::disk('public')->delete($habilidad->imagen);
        }

        $habilidad->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Habilidad eliminada exitosamente');
    }

    /**
     * Buscar habilidades
     */
    public function buscar(Request $request)
    {
        $query = $request->get('q');
        $categoria = $request->get('categoria');

        $habilidades = Habilidad::with(['usuario', 'categoria'])
            ->aprobadas()
            ->when($query, function($q) use ($query) {
                return $q->where(function($q) use ($query) {
                    $q->where('titulo', 'like', "%{$query}%")
                      ->orWhere('descripcion', 'like', "%{$query}%");
                });
            })
            ->when($categoria, function($q) use ($categoria) {
                return $q->where('categoria_id', $categoria);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categorias = Categoria::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('habilidades.buscar', compact('habilidades', 'categorias', 'query', 'categoria'));
    }
}