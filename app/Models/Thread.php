<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Thread extends Model
{
    use HasFactory;

    /**
     * Atributos que son asignables en masa.
     *
     * @var string[]
     */
    protected $fillable = ['subject'];


    /**
     * Un hilo tiene muchos mensajes.
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Un hilo tiene y pertenece a muchos participantes.
     *
     * @return BelongsToMany
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participants');
    }
}
