<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PlaceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Place extends Model
{
    /** @use HasFactory<PlaceFactory> */
    use HasFactory;
    use HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'longitude',
        'latitude',
    ];

    /**
     * Updates or inserts the position for a given user.
     *
     * @param  int  $userId  The ID of the user.
     * @param  float  $latitude  The latitude of the position.
     * @param  float  $longitude  The longitude of the position.
     */
    public static function updateOrInsertPosition(int $userId, float $latitude, float $longitude): Place
    {
        $existingPlace = self::where('user_id', $userId)->first();

        if ($existingPlace) {
            $existingPlace->update([
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);

            return $existingPlace;
        }
        return self::create([
            'user_id' => $userId,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

    }

    /**
     * Calculate the distance between a given latitude and longitude and the latitude and longitude of the records in the table.
     *
     * @param  Builder<Place>  $query  The query builder instance.
     * @param  float  $longitude  The longitude of the reference point.
     * @param  float  $latitude  The latitude of the reference point.
     * @param  float  $radius  The radius within which the records should be selected.
     * @return Builder<Place> The modified query builder instance.
     */
    public function scopeInRaduis(Builder $query, float $longitude, float $latitude, float $radius): Builder
    {
        return $query->select('*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$latitude, $longitude, $latitude],
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
    }

    /**
     * Get the user that owns the Place
     *
     * @return BelongsTo<User, Place>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
