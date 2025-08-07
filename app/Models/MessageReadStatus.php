<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageReadStatus extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'message_read_status';

    /**
     * Desactiva los timestamps (created_at, updated_at) para este modelo.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atributos que se pueden asignar masivamente.
     *
     * @var array
     */
    protected $fillable = ['message_id', 'user_id', 'read_at'];
}
