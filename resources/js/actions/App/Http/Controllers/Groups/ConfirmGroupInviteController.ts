import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Groups\ConfirmGroupInviteController::__invoke
* @see app/Http/Controllers/Groups/ConfirmGroupInviteController.php:14
* @route '/invite/{code}'
*/
const ConfirmGroupInviteController = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: ConfirmGroupInviteController.url(args, options),
    method: 'post',
})

ConfirmGroupInviteController.definition = {
    methods: ["post"],
    url: '/invite/{code}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Groups\ConfirmGroupInviteController::__invoke
* @see app/Http/Controllers/Groups/ConfirmGroupInviteController.php:14
* @route '/invite/{code}'
*/
ConfirmGroupInviteController.url = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { code: args }
    }

    if (Array.isArray(args)) {
        args = {
            code: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        code: args.code,
    }

    return ConfirmGroupInviteController.definition.url
            .replace('{code}', parsedArgs.code.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Groups\ConfirmGroupInviteController::__invoke
* @see app/Http/Controllers/Groups/ConfirmGroupInviteController.php:14
* @route '/invite/{code}'
*/
ConfirmGroupInviteController.post = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: ConfirmGroupInviteController.url(args, options),
    method: 'post',
})

export default ConfirmGroupInviteController