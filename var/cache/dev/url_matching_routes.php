<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/admindash' => [[['_route' => 'admindash', '_controller' => 'App\\Controller\\AdmindashController::index'], null, null, null, false, false, null]],
        '/AAA' => [[['_route' => 'Rec', '_controller' => 'App\\Controller\\AdmindashController::showRec'], null, ['GET' => 0], null, false, false, null]],
        '/Recnew' => [[['_route' => 'Rec_new', '_controller' => 'App\\Controller\\AdmindashController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/msg/msg' => [[['_route' => 'SASA', '_controller' => 'App\\Controller\\AdmindashController::SASA'], null, ['GET' => 0], null, false, false, null]],
        '/new/msg' => [[['_route' => 'mess_new', '_controller' => 'App\\Controller\\AdmindashController::newM'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/' => [[['_route' => 'app_home', '_controller' => 'App\\Controller\\HomeController::index'], null, null, null, false, false, null]],
        '/messages/sasa' => [[['_route' => 'mymain', '_controller' => 'App\\Controller\\MessagesController::index'], null, ['GET' => 0], null, false, false, null]],
        '/messages/new' => [[['_route' => 'app_messages_new', '_controller' => 'App\\Controller\\MessagesController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/reclamation/wesh' => [[['_route' => 'app_reclamation_index', '_controller' => 'App\\Controller\\ReclamationController::index'], null, ['GET' => 0], null, false, false, null]],
        '/reclamation/new/ss' => [[['_route' => 'app_reclamation_new', '_controller' => 'App\\Controller\\ReclamationController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/([^/]++)/editRec(*:24)'
                .'|/delete/([^/]++)(*:47)'
                .'|/([^/]++)(?'
                    .'|(*:66)'
                    .'|/(?'
                        .'|editM(*:82)'
                        .'|m(?'
                            .'|sg(*:95)'
                            .'|m(*:103)'
                        .')'
                    .')'
                .')'
                .'|/messages/([^/]++)(?'
                    .'|(*:135)'
                    .'|/edit(*:148)'
                    .'|(*:156)'
                .')'
                .'|/reclamation/([^/]++)(?'
                    .'|/edit(*:194)'
                    .'|(*:202)'
                .')'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:242)'
                    .'|wdt/([^/]++)(*:262)'
                    .'|profiler(?'
                        .'|(*:281)'
                        .'|/([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:319)'
                                .'|router(*:333)'
                                .'|exception(?'
                                    .'|(*:353)'
                                    .'|\\.css(*:366)'
                                .')'
                            .')'
                            .'|(*:376)'
                        .')'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        24 => [[['_route' => 'Rec_Edit', '_controller' => 'App\\Controller\\AdmindashController::edit'], ['idRec'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        47 => [[['_route' => 'Rec_delete', '_controller' => 'App\\Controller\\AdmindashController::delete'], ['idRec'], ['POST' => 0], null, false, true, null]],
        66 => [[['_route' => 'reclamation_show', '_controller' => 'App\\Controller\\AdmindashController::show'], ['idRec'], ['GET' => 0], null, false, true, null]],
        82 => [[['_route' => 'mes_edit', '_controller' => 'App\\Controller\\AdmindashController::editM'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        95 => [[['_route' => 'mes_delete', '_controller' => 'App\\Controller\\AdmindashController::deleteM'], ['id'], ['POST' => 0], null, false, false, null]],
        103 => [[['_route' => 'showmsg', '_controller' => 'App\\Controller\\AdmindashController::showM'], ['id'], ['GET' => 0], null, false, false, null]],
        135 => [[['_route' => 'app_messages_show', '_controller' => 'App\\Controller\\MessagesController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        148 => [[['_route' => 'app_messages_edit', '_controller' => 'App\\Controller\\MessagesController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        156 => [[['_route' => 'app_messages_delete', '_controller' => 'App\\Controller\\MessagesController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        194 => [[['_route' => 'app_reclamation_edit', '_controller' => 'App\\Controller\\ReclamationController::edit'], ['idRec'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        202 => [
            [['_route' => 'app_reclamation_delete', '_controller' => 'App\\Controller\\ReclamationController::delete'], ['idRec'], ['POST' => 0], null, false, true, null],
            [['_route' => 'app_reclamation_show', '_controller' => 'App\\Controller\\ReclamationController::show'], ['idRec'], ['GET' => 0], null, false, true, null],
        ],
        242 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        262 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        281 => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], [], null, null, true, false, null]],
        319 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        333 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        353 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        366 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        376 => [
            [['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
