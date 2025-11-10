<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Habilidad extends Model
{
    protected $table = 'habilidades';
    
    protected $fillable = [
        'usuario_id',
        'categoria_id',
        'titulo',
        'descripcion',
        'horas_ofrecidas',
        'puntos_sugeridos',
        'imagen',
        'estado',
    ];

    protected $casts = [
        'estado' => 'string',
        'visitas' => 'integer',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Scope: solo habilidades aprobadas
     */
    public function scopeAprobadas(Builder $query)
    {
        return $query->where('estado', 'aprobado');
    }

    /**
     * Scope: solo habilidades pendientes
     */
    public function scopePendientes(Builder $query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function trueques(): HasMany
    {
        return $this->hasMany(Trueque::class, 'habilidad_ofrece_id')
            ->orWhere('habilidad_recibe_id', $this->id);
    }

    /**
     * Obtener URL de imagen con fallback a Unsplash
     */
    public function getImagenUrlAttribute(): string
    {
        if (!empty($this->imagen) && filter_var($this->imagen, FILTER_VALIDATE_URL)) {
            return $this->imagen;
        }
        
        if (!empty($this->imagen) && file_exists(public_path('storage/' . $this->imagen))) {
            return asset('storage/' . $this->imagen);
        }
        
        // URLs de Unsplash específicas por categoría
        $imagenes = [
            'Tecnología' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&h=600&fit=crop',
            'Idiomas' => 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=800&h=600&fit=crop',
            'Arte' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=800&h=600&fit=crop',
            'Música' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=800&h=600&fit=crop',
            'Deportes' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&h=600&fit=crop',
            'Cocina' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800&h=600&fit=crop',
            'Educación' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=600&fit=crop',
            'Manualidades' => 'https://images.unsplash.com/photo-1452860606245-08befc0ff44b?w=800&h=600&fit=crop',
            'Jardinería' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&h=600&fit=crop',
            'Reparaciones' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=800&h=600&fit=crop',
        ];
        
        $categoria = $this->categoria->nombre ?? 'Educación';
        return $imagenes[$categoria] ?? 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=600&fit=crop';
    }

    /**
     * Obtener icono de la categoría
     */
    public function getCategoriaIconoAttribute(): string
    {
        $iconos = [
            'Tecnología' => '💻',
            'Idiomas' => '🗣️',
            'Arte' => '🎨',
            'Música' => '🎵',
            'Deportes' => '⚽',
            'Cocina' => '🍳',
            'Educación' => '📚',
            'Manualidades' => '✂️',
            'Jardinería' => '🌱',
            'Reparaciones' => '🔧',
        ];
        
        return $iconos[$this->categoria->nombre ?? ''] ?? '🌟';
    }
}
