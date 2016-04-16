<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Form\Doc;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * Добавление документа
     * @return ViewModel
     */
    public function addDocAction()
    {
        $view = new ViewModel();

        $view->setVariable('form', $this->_form());

        return $view;
    }

    public function editDocAction()
    {
        return new ViewModel();
    }

    public function viewDocAction()
    {
        return new ViewModel();
    }

    /**
     * @return Doc
     */
    protected function _form()
    {
        $form = new Doc('doc', []);

        return $form;
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    protected function _getAuthService()
    {
        return $this->getServiceLocator()->get('zfcuser_auth_service');
    }
}
