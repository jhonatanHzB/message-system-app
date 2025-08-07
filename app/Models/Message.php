<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    /**
     * Atributos que son asignables en masa.
     *
     * @var string[]
     */
    protected $fillable = ['thread_id', 'user_id', 'body'];

    // Al guardar/actualizar un mensaje, actualiza automáticamente el updated_at del hilo padre.
    protected $touches = ['thread'];

    /**
     * Un mensaje pertenece a un hilo.
     *
     * @return BelongsTo
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Un mensaje fue enviado por un usuario.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene todos los registros de estado de lectura para mensaje.
     *
     * @return HasMany
     */
    public function readStatus(): HasMany
    {
        return $this->hasMany(MessageReadStatus::class);
    }
}
