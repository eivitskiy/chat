<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Message
 *
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static Builder|Message onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static Builder|Message withTrashed()
 * @method static Builder|Message withoutTrashed()
 * @mixin Eloquent
 */
class Message extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'user_id',
        'message',
    ];

    protected $appends = ['class'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::create($date)->format('d.m.Y H:i:s');
    }

    public function getClassAttribute()
    {
        return 'badge-secondary';
    }
}
