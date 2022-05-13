<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;


/**
 *
 */
class PersonalAccessToken extends Model
{
    use HasFactory;

    /**
     * @param array $options
     * @return false
     */
    public function save(array $options = []): bool
    {
        $changes = $this->getDirty();
        // Проверяем два изменения, так как одно из них это всегда поле updated_at
        if (! array_key_exists('last_used_at', $changes) || count($changes) > 2) {
            parent::save();
        }
        return false;
    }
}
