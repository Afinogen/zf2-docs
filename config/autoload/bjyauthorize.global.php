<?php

return [
    'bjyauthorize' => [
        'unauthorized_strategy' => 'BjyAuthorize\View\RedirectionStrategy',

        // set the 'guest' role as default (must be defined in a role provider)
        'default_role' => 'guest',

        /* this module uses a meta-role that inherits from any roles that should
         * be applied to the active user. the identity provider tells us which
         * roles the "identity role" should inherit from.
         *
         * for ZfcUser, this will be your default identity provider
         */
        'identity_provider' => 'User\Provider\Identity\ZendDb',

        /* role providers simply provide a list of roles that should be inserted
         * into the Zend\Acl instance. the module comes with two providers, one
         * to specify roles in a config file and one to load roles using a
         * Zend\Db adapter.
         */
        'role_providers' => [

            // this will load roles from the user_role table in a database
            // format: user_role(role_id(varchar), parent(varchar))
            'User\Provider\Role\ZendDb' => [
                'table' => 'user_role',
                'role_id_field' => 'name',
                'parent_role_field' => 'parent_id',
            ],
        ],

        // resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'admin' => [],
                //'pants' => array(),
            ],
        ],

        /* rules can be specified here with the format:
         * array(roles (array), resource, [privilege (array|string), assertion])
         * assertions will be loaded using the service manager and must implement
         * Zend\Acl\Assertion\AssertionInterface.
         * *if you use assertions, define them using the service manager!*
         */
        'rule_providers' => [
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                    // allow guests and users (and admins, through inheritance)
                    // the "wear" privilege on the resource "pants"
                    //array(array('guest', 'user'), 'pants', 'wear'),
                    [['admin'], 'admin'],
                ],

                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => [
                    // ...
                ],
            ],
        ],

        /* Currently, only controller and route guards exist
         */
        'guards' => [
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all controllers and actions unless they are specified here.
             * You may omit the 'action' index to allow access to the entire controller
             */
//            'BjyAuthorize\Guard\Controller' => array(
//                //array('controller' => 'index', 'action' => 'index', 'roles' => array('guest','user')),
//                //array('controller' => 'index', 'action' => 'stuff', 'roles' => array('user')),
//                array('controller' => 'zfcuser', 'roles' => array()),
//                array('controller' => 'Application\Controller\Index', 'roles' => array('guest', 'user')),
//            ),

            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => [
                ['route' => 'zfcuser', 'roles' => ['user']],
                ['route' => 'zfcuser/logout', 'roles' => ['user']],
                ['route' => 'zfcuser/login', 'roles' => ['guest']],
                ['route' => 'zfcuser/register', 'roles' => ['guest']],
                ['route' => 'zfcuser/changepassword', 'roles' => ['user']],
                ['route' => 'zfcuser/changeemail', 'roles' => ['user']],
                // Below is the default index action used by the [ZendSkeletonApplication](https://github.com/zendframework/ZendSkeletonApplication)
                ['route' => 'home', 'roles' => ['guest', 'user']],
            ],
        ],
    ],

];