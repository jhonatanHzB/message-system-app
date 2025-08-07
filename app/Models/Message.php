<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    /**
     * Atributos que son asignables en masa.
     *
     * @var string[]
     */
    protected $fillable = ['thread_id', 'user_id', 'body'];

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
     */
    public function readStatus()
    {
        return $this->hasMany(MessageReadStatus::class);
    }
}
