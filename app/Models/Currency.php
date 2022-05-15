<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method get()
 * @method static byCode(string $currencyCode)
 */
class Currency extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
      'rate'
    ];

    /**
     * @param $query
     * @param string $code
     * @return mixed
     */
    public function scopeByCode($query,string $code): mixed
    {
        return $query->where('code',$code);
    }

    /**
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->is_main === 1;
    }
}
