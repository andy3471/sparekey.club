<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use MarcReichel\IGDBLaravel\Exceptions\AuthenticationException;
use MarcReichel\IGDBLaravel\Models\Game as IgdbGame;
use MarcReichel\IGDBLaravel\Models\Genre;
use Throwable;

class Game extends Model
{
    use HasUuids;

    protected $appends = [
        'url',
        'name',
        'description',
        'image',
    ];

    protected $fillable = [
        'igdb_id',
    ];

    private bool $igdbResolved = false;

    private ?IgdbGame $igdbData = null;

    public static function fromIgdbId(int $igdbId): ?self
    {
        $existing = self::where('igdb_id', $igdbId)->first();

        if ($existing) {
            return $existing;
        }

        try {
            $igdbGame = IgdbGame::select(['name', 'summary', 'id', 'parent_game'])->where('id', '=', $igdbId)->first();
        } catch (Throwable) {
            return null;
        }

        if ($igdbGame) {
            return self::createFromIgdb($igdbGame);
        }

        return null;
    }

    public static function createFromIgdb(
        IgdbGame $igdbGame,
    ): self {
        $game = self::where('igdb_id', $igdbGame->id)->first();

        if ($game) {
            return $game;
        }

        $game          = new self;
        $game->igdb_id = $igdbGame->id;
        $game->save();

        return $game;
    }

    /** @return HasMany<Key, $this> */
    public function keys(): HasMany
    {
        return $this->hasMany(Key::class);
    }

    public function getIgdbData(): ?IgdbGame
    {
        if ($this->igdbResolved) {
            return $this->igdbData;
        }

        $this->igdbResolved = true;

        try {
            $this->igdbData = IgdbGame::select([
                'name',
                'summary',
                'id',
                'parent_game',
                'aggregated_rating',
                'aggregated_rating_count',
            ])
                ->with(['cover' => ['image_id'], 'genres', 'screenshots', 'dlcs'])
                ->where('id', '=', $this->igdb_id)
                ->first();
        } catch (AuthenticationException|Throwable) {
            $this->igdbData = null;
        }

        return $this->igdbData;
    }

    /** @return Attribute<string, never> */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return route('games.show', $this->igdb_id);
            }
        );
    }

    /** @return Attribute<?string, never> */
    protected function name(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            return $this->getIgdbData()?->name;
        });
    }

    /** @return Attribute<?string, never> */
    protected function description(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            return $this->getIgdbData()?->summary;
        });
    }

    /** @return Attribute<?string, never> */
    protected function image(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            $igdb = $this->getIgdbData();
            if ($igdb instanceof IgdbGame && $igdb->cover && isset($igdb->cover->image_id)) {
                return 'https://images.igdb.com/igdb/image/upload/t_cover_big/'.$igdb->cover->image_id.'.jpg';
            }

            return null;
        });
    }

    /** @return Attribute<?Collection<int, Genre>, never> */
    protected function genres(): Attribute
    {
        return Attribute::make(get: function (): ?Collection {
            return $this->getIgdbData()?->genres;
        });
    }

    /** @return Attribute<?Collection<int, mixed>, never> */
    protected function screenshots(): Attribute
    {
        return Attribute::make(get: function (): ?Collection {
            return $this->getIgdbData()?->screenshots;
        });
    }

    /** @return Attribute<mixed, never> */
    protected function aggregatedRating(): Attribute
    {
        return Attribute::make(get: function (): mixed {
            return $this->getIgdbData()?->aggregated_rating;
        });
    }

    /** @return Attribute<mixed, never> */
    protected function aggregatedRatingCount(): Attribute
    {
        return Attribute::make(get: function (): mixed {
            return $this->getIgdbData()?->aggregated_rating_count;
        });
    }

    /** @return Attribute<?int, never> */
    protected function parentGameId(): Attribute
    {
        return Attribute::make(get: function (): ?int {
            return $this->getIgdbData()?->parent_game;
        });
    }
}
