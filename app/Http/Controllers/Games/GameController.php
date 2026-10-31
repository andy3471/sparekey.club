<?php

declare(strict_types=1);

namespace App\Http\Controllers\Games;

use App\DataTransferObjects\Games\GameData;
use App\DataTransferObjects\Keys\KeyData;
use App\DataTransferObjects\Platforms\PlatformData;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Platform;
use App\Support\ActiveGroup;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use MarcReichel\IGDBLaravel\Models\Game as IgdbGame;
use Spatie\LaravelData\DataCollection;
use Throwable;

class GameController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Game::class);

        auth()->user()->loadMissing(['media', 'groups']);

        $platformIds   = $request->array('platforms');
        $userGroupIds  = ActiveGroup::userGroupIds(auth()->user());
        $activeGroupId = ActiveGroup::id($request);

        return Inertia::render('Games/Index', [
            'title'     => fn (): string => 'Games',
            'platforms' => fn () => PlatformData::collect(Cache::remember('platforms', 3600, fn () => Platform::all())),
            'games'     => Inertia::scroll(function () use ($platformIds, $activeGroupId, $userGroupIds) {
                $games = Game::query()
                    ->whereHas('keys', function ($query) use ($platformIds, $activeGroupId, $userGroupIds): void {
                        $query->visibleTo($activeGroupId, $userGroupIds);

                        if ($platformIds !== []) {
                            $query->whereIn('platform_id', $platformIds);
                        }
                    })
                    ->with(['keys' => function ($query) use ($platformIds, $activeGroupId, $userGroupIds): void {
                        $query->visibleTo($activeGroupId, $userGroupIds)
                            ->with('platform');

                        if ($platformIds !== []) {
                            $query->whereIn('platform_id', $platformIds);
                        }
                    }])
                    ->paginate(12);

                $games->through(fn ($game): GameData => GameData::from($game)->include('hasKey', 'keyCount', 'platforms'));

                return $games;
            }),
            'selectedPlatforms' => fn () => $platformIds,
        ]);
    }

    public function show(Request $request, string $igdb_id): Response
    {
        $game = Game::fromIgdbId((int) $igdb_id);

        abort_if(! $game instanceof Game, 404);

        auth()->user()->loadMissing(['media', 'groups']);

        $this->authorize('view', $game);

        $userGroupIds  = ActiveGroup::userGroupIds(auth()->user());
        $activeGroupId = ActiveGroup::id($request);

        return Inertia::render('Games/Show', [
            'game' => fn (): GameData => GameData::from($game)->include('genres', 'screenshots', 'aggregated_rating', 'aggregated_rating_count'),
            'keys' => fn (): DataCollection => KeyData::collect(
                KeyData::collect(
                    $game->keys()
                        ->select('id', 'game_id', 'platform_id', 'created_user_id', 'owned_user_id', 'group_id', 'key', 'message')
                        ->visibleTo($activeGroupId, $userGroupIds)
                        ->with('platform', 'createdUser.media', 'group.media')
                        ->get(), DataCollection::class)
            ),
            'parentGame' => function () use ($game): ?GameData {
                $parentId = $game->parent_game_id;

                if (! $parentId) {
                    return null;
                }

                try {
                    $igdbParent = IgdbGame::select(['name', 'summary', 'id'])
                        ->with(['cover' => ['image_id']])
                        ->where('id', '=', $parentId)
                        ->first();
                } catch (Throwable) {
                    return null;
                }

                if ($igdbParent) {
                    return GameData::fromIgdb($igdbParent);
                }

                return null;
            },
            'childGames' => Inertia::scroll(function () use ($game, $request): LengthAwarePaginator {
                try {
                    $allChildGames = IgdbGame::select(['name', 'summary', 'id'])
                        ->with(['cover' => ['image_id']])
                        ->where('parent_game', '=', $game->igdb_id)
                        ->limit(12)
                        ->get();
                } catch (Throwable) {
                    $allChildGames = collect();
                }

                $perPage     = 12;
                $currentPage = (int) $request->get('page', 1);
                $total       = $allChildGames->count();

                $lastPage    = (int) ceil($total / $perPage);
                $currentPage = min($currentPage, max(1, $lastPage));

                $items = $allChildGames->forPage($currentPage, $perPage)
                    ->map(fn (IgdbGame $igdbChild): GameData => GameData::fromIgdb($igdbChild)->include('keyCount', 'platforms'))
                    ->values();

                $queryParams = $request->except('childGames_page');

                return new LengthAwarePaginator(
                    $items,
                    $total,
                    $perPage,
                    $currentPage,
                    [
                        'path'     => $request->url(),
                        'query'    => $queryParams,
                        'pageName' => 'childGames_page',
                    ]
                );
            }),
        ]);
    }
}
