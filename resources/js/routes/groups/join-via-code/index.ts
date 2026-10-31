import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Groups\ConfirmGroupInviteController::__invoke
* @see app/Http/Controllers/Groups/ConfirmGroupInviteController.php:15
* @route '/invite/{code}'
*/
export const accept = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: accept.url(args, options),
    method: 'post',
})

accept.definition = {
    methods: ["post"],
    url: '/invite/{code}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Groups\ConfirmGroupInviteController::__invoke
* @see app/Http/Controllers/Groups/ConfirmGroupInviteController.php:15
* @route '/invite/{code}'
*/
accept.url = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return accept.definition.url
            .replace('{code}', parsedArgs.code.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Groups\ConfirmGroupInviteController::__invoke
* @see app/Http/Controllers/Groups/ConfirmGroupInviteController.php:15
* @route '/invite/{code}'
*/
accept.post = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: accept.url(args, options),
    method: 'post',
})

const joinViaCode = {
    accept: Object.assign(accept, accept),
}

export default joinViaCode