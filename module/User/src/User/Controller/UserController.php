<?php
/**
 * Created by PhpStorm.
 * User: Afinogen
 * Date: 23.01.2016
 * Time: 21:14
 */

namespace User\Controller;

use User\Entity\UserRole;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;

/**
 * Class UserController
 * @package User\Controller
 */
class UserController extends \ZfcUser\Controller\UserController
{
    public function registerAction()
    {
        // if the user is logged in, we don't need to register
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        // if registration is disabled
        if (!$this->getOptions()->getEnableRegistration()) {
            return ['enableRegistration' => false];
        }

        $request = $this->getRequest();
        $service = $this->getUserService();
        $form = $this->getRegisterForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        $redirectUrl = $this->url()->fromRoute(static::ROUTE_REGISTER)
            .($redirect ? '?redirect='.rawurlencode($redirect) : '');
        $prg = $this->prg($redirectUrl, true);

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return [
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            ];
        }

        $post = $prg;
        $user = $service->register($post);

        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

        if (!$user) {
            return [
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            ];
        }

        //установка дефолтной роли
        $roles = [
            UserRole::ROLE_USER
        ];
        $this->_getUserMapper()->setRoles($roles, $user->getId());

        if ($service->getOptions()->getLoginAfterRegistration()) {
            $identityFields = $service->getOptions()->getAuthIdentityFields();
            if (in_array('email', $identityFields)) {
                $post['identity'] = $user->getEmail();
            } elseif (in_array('username', $identityFields)) {
                $post['identity'] = $user->getUsername();
            }
            $post['credential'] = $post['password'];
            $request->setPost(new Parameters($post));

            return $this->forward()->dispatch(static::CONTROLLER_NAME, ['action' => 'authenticate']);
        }

        // TODO: Add the redirect parameter here...
        return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN).($redirect ? '?redirect='.rawurlencode($redirect) : ''));
    }

    /**
     * @return \User\Mapper\User
     */
    protected function _getUserMapper()
    {
        return $this->getServiceLocator()->get('User\Mapper\User');
    }
}