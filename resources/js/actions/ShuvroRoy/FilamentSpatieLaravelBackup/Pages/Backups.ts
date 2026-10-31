import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups::__invoke
* @see vendor/shuvroroy/filament-spatie-laravel-backup/src/Pages/Backups.php:7
* @route '/admin/backups'
*/
const Backups = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Backups.url(options),
    method: 'get',
})

Backups.definition = {
    methods: ["get","head"],
    url: '/admin/backups',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups::__invoke
* @see vendor/shuvroroy/filament-spatie-laravel-backup/src/Pages/Backups.php:7
* @route '/admin/backups'
*/
Backups.url = (options?: RouteQueryOptions) => {
    return Backups.definition.url + queryParams(options)
}

/**
* @see \ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups::__invoke
* @see vendor/shuvroroy/filament-spatie-laravel-backup/src/Pages/Backups.php:7
* @route '/admin/backups'
*/
Backups.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: Backups.url(options),
    method: 'get',
})

/**
* @see \ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups::__invoke
* @see vendor/shuvroroy/filament-spatie-laravel-backup/src/Pages/Backups.php:7
* @route '/admin/backups'
*/
Backups.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: Backups.url(options),
    method: 'head',
})

export default Backups