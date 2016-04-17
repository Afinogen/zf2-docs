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

        $form = $this->_form();
        if ($form instanceof  \Zend\Http\PhpEnvironment\Response) {
            return $form;
        }

        $view->setVariable('form', $form);

        return $view;
    }

    public function editDocAction()
    {
        $view = new ViewModel();
        $form = $this->_form();

        if ($form instanceof  \Zend\Http\PhpEnvironment\Response) {
            return $form;
        }

        $view->setVariable('form', $form);

        return $view;
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

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                $entity = new \Application\Entity\Doc();
                $entity->setType($form->getData()['type']);
                $entity->setTitle($form->getData()['title']);
                $entity->setDescription($form->getData()['description']);
                $entity->setResponsibleId(!empty($form->getData()['responsible_id']) ? $form->getData()['responsible_id'] : null);
                $entity->setResolution($form->getData()['resolution']);
                $entity->setPeriodExecution($form->getData()['period_execution']);
                $entity->isAgreed($form->getData()['is_agreed']);
                $entity->isApproved($form->getData()['is_approved']);
                $entity->isExecuted($form->getData()['is_executed']);

                if (!$entity->getId()) {
                    $entity->setAuthorId($this->_getAuthService()->getIdentity()->getId());
                }

                if ($this->_getDocMapper()->saveEntity($entity)) {
                    return $this->redirect()->toRoute('docs', ['action' => 'edit-doc', 'id' => $entity->getId()]);
                }
            }
        }

        $users = $this->_getUserMapper()->fetchAll();
        $usersOptionValue = [];
        foreach($users as $user) {
            $usersOptionValue[$user->getId()] = $user->getDisplayName();
        }

        $form->get('responsible_id')->setValueOptions($usersOptionValue);

        return $form;
    }

    /**
     * Id документа
     * @return int|null
     */
    protected function _getDocId()
    {
        return $this->params()->fromRoute('id');
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    protected function _getAuthService()
    {
        return $this->getServiceLocator()->get('zfcuser_auth_service');
    }

    /**
     * @return \User\Mapper\User
     */
    protected function _getUserMapper()
    {
        return $this->getServiceLocator()->get('User\Mapper\User');
    }

    /**
     * @return \Application\Mapper\Doc
     */
    protected function _getDocMapper()
    {
        return $this->getServiceLocator()->get('Application\Mapper\Doc');
    }
}
