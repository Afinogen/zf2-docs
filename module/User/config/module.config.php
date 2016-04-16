<?php

return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__.'/../view'
        ],
    ],
    'controllers' => [
        'invokables' => [
            'zfcuser' => 'User\Controller\UserController',
        ],
    ],
    'router' => [
        'routes' => [
            'user' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/user[/]',
                    'defaults' => [
                        'controller' => 'zfcuser',
                        'action' => 'home',
                    ],
                ],
            ],
            'user-home' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/user/home[/]',
                    'defaults' => [
                        'controller' => 'zfcuser',
                        'action' => 'home',
                    ],
                ],
            ],
            'user-register' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/user/register[/]',
                    'defaults' => [
                        'controller' => 'zfcuser',
                        'action' => 'register',
                    ],
                ],
            ],
            'user-restore' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/user/restore[/:code][/]',
                    'constraints' => [
                        'code' => '[a-f0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'zfcuser',
                        'action' => 'restore',
                    ],
                ],
            ],
        ]
    ],
];

//
//return [
//    'controllers' => [
//        'invokables' => [
//            'zfcuser' => 'User\Controller\UserController',
//        ],
//    ],
//    'router' => [
//        'routes' => [
//            'user' => [
//                'type' => 'Literal',
//                'options' => [
//                    'route' => '/user',
//                    'defaults' => [
//                        '__NAMESPACE__' => 'User\Controller',
//                        'controller' => 'User',
//                        'action' => 'index',
//                    ],
//                ],
//                'may_terminate' => true,
//                'child_routes' => [
//
//                    'default' => [
//                        'type' => 'Segment',
//                        'options' => [
//                            'route' => '/[:controller[/:action]]',
//                            'constraints' => [
//                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                            ],
//                            'defaults' => [
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//        ],
//    ],
//    'view_manager' => [
//        'template_path_stack' => [
//            'User' => __DIR__.'/../view',
//        ],
//    ],
//];
