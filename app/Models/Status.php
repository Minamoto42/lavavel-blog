<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class Status
 *
 * @property string content
 * @property int user_id
 * @property User user
 * @property string created_at
 * @property string updated_at
 */
class Status extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content'];

    /**
     * The attributes that are mass assignable.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        // 一条微博一个用户
        return $this->belongsTo(User::class);
    }
}
