<?php

namespace User;

use User\Mapper\User;
use \Zend\Form\Element;
use ZfcUser\Mapper\UserHydrator;

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
                'User\Mapper\User' => 'User\Mapper\User',
                'User\Mapper\UserRole' => 'User\Mapper\UserRole'
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
                'zfcuser_login_form' => function ($_sm) {
                    $options = $_sm->get('zfcuser_module_options');
                    $form = new \ZfcUser\Form\Login(null, $options);
                    $form->setInputFilter(new \ZfcUser\Form\LoginFilter($options));
                    $form->setAttribute('class', 'col-sm-4');

                    $form->get('identity')->setLabel('Логин/E-mail');
                    $form->get('identity')->setAttribute('placeholder', 'E-mail');
                    $form->get('identity')->setAttribute('class', 'form-control');
                    $form->get('credential')->setLabel('Пароль');
                    $form->get('credential')->setAttribute('placeholder', 'Пароль');
                    $form->get('credential')->setAttribute('class', 'form-control');

                    $ele = new Element\Submit('submit');
                    $ele->setAttribute('class', 'btn btn-success');
                    $ele->setValue('Войти');
                    $ele->setLabel('Войти');
                    $form->add($ele);

                    return $form;
                },
                'zfcuser_register_form' => function ($_sm) {
                    $options = $_sm->get('zfcuser_module_options');
                    $emailValid = new \ZfcUser\Validator\NoRecordExists(['key' => 'email']);
                    $emailValid->setMapper($_sm->get('zfcuser_user_mapper'));
                    $usernameValid = new \ZfcUser\Validator\NoRecordExists(['key' => 'email']);
                    $usernameValid->setMapper($_sm->get('zfcuser_user_mapper'));

                    $form = new \ZfcUser\Form\Register(null, $options);
                    $form->setInputFilter(new \ZfcUser\Form\RegisterFilter($emailValid, $usernameValid,$options));
                    $form->setAttribute('class', 'col-sm-4');

                    $form->get('email')->setLabel('Логин/E-mail');
                    $form->get('email')->setAttribute('placeholder', 'E-mail');
                    $form->get('email')->setAttribute('class', 'form-control');

                    $form->get('display_name')->setLabel('Отображаемое имя');
                    $form->get('display_name')->setAttribute('placeholder', 'Иван');
                    $form->get('display_name')->setAttribute('class', 'form-control');

                    $form->get('password')->setLabel('Пароль');
                    $form->get('password')->setAttribute('placeholder', 'Длина пароля минимум 6 знаков');
                    $form->get('password')->setAttribute('class', 'form-control');

                    $form->get('passwordVerify')->setLabel('Подтверждение пароля');
                    $form->get('passwordVerify')->setAttribute('placeholder', 'Повторите ввод пароля');
                    $form->get('passwordVerify')->setAttribute('class', 'form-control');
                    /*
                    $form->setCaptchaElement(
                        $_sm->get('zfcuser_captcha_element')
                    );
                    */

                    $form->get('submit')->setAttribute('class', 'btn btn-success');
                    $form->get('submit')->setValue('Регистрация');
                    $form->get('submit')->setLabel('Регистрация');

                    return $form;
                },
                'zfcuser_user_mapper' => function ($_sm) {
                    $options = $_sm->get('zfcuser_module_options');
                    $mapper = new \User\Mapper\User();
                    //$mapper->setDbAdapter($_sm->get('zfcuser_zend_db_adapter'));
                    $entityClass = $options->getUserEntityClass();
                    $mapper->setEntityPrototype(new $entityClass);
                    $mapper->setHydrator(new UserHydrator());
                    $mapper->setTableName($options->getTableName());

                    return $mapper;
                },
            ]
        ];
    }
}
