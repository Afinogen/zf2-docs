<?php

namespace User;

/**
 * Class Module
 *
 * @package Auth
 */
class Module
{
    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__
                ]
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return [
            'invokables' => [
                  'zfcuser_controller' => 'ZfcUser\Controller\UserController',
            ],
            'factories' => [
                'User\Provider\Identity\ZendDb' => function ($_sm) {
                    /* @var $adapter \Zend\Db\Adapter\Adapter */
                    $adapter = $_sm->get('zfcuser_zend_db_adapter');
                    /* @var $userService \ZfcUser\Service\User */
                    $userService = $_sm->get('zfcuser_user_service');
                    $config = $_sm->get('BjyAuthorize\Config');

                    $provider = new \User\Provider\Identity\ZendDb($adapter, $userService);

                    $provider->setDefaultRole($config['default_role']);

                    return $provider;
                },
            ]
        ];
    }
}
