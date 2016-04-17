<?php

namespace Application\Controller;

use Application\Entity\File;
use Application\Form\Doc;
use Zend\Db\Sql\Where;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends AbstractActionController
{
    /**
     * Главная страница сайта
     * @return ViewModel
     */
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
        if ($form instanceof \Zend\Http\PhpEnvironment\Response) {
            return $form;
        }

        $view->setVariable('form', $form);

        return $view;
    }

    /**
     * Редактирование документа
     * @return Doc|ViewModel
     */
    public function editDocAction()
    {
        $view = new ViewModel();
        $form = $this->_form();

        if ($form instanceof \Zend\Http\PhpEnvironment\Response) {
            return $form;
        }

        $doc = $this->_getDocMapper()->findById($this->_getDocId());
        $this->_getDocMapper()->setupDocs([$doc]);
        $files = $this->_getFileMapper()->fetchAll(['doc_id' => $doc->getId()]);

        $view->setVariable('form', $form);
        $view->setVariable('doc', $doc);
        $view->setVariable('files', $files);

        return $view;
    }

    /**
     * Просмотр списка документов
     * @return array|ViewModel
     */
    public function viewDocsAction()
    {
        $view = new ViewModel();

        $type = $this->params()->fromRoute('type');
        if (!$type) {
            return $this->notFoundAction();
        }

        $pagination = null;
        $where = new Where();
        $where->equalTo('type', $type);
        $docs = $this->_getDocMapper()->getDocsPagination($pagination, $where, $this->params()->fromRoute('page', 1));
        $this->_getDocMapper()->setupDocs($docs);

        $view->setVariable('type', $type);
        $view->setVariable('docs', $docs);
        $view->setVariable('paginator', $pagination);

        return $view;
    }

    /**
     * Поиск по документам
     * @return ViewModel
     */
    public function searchDocsAction()
    {
        $view = new ViewModel();

        $search = $this->params()->fromQuery('search', null);

        if ($search) {
            $pagination = null;
            $where = new Where();
            $where->like('title', '%'.$search.'%');
            $where->like('keywords', '%'.$search.'%');

            $docs = $this->_getDocMapper()->getDocsPagination($pagination, $where, $this->params()->fromRoute('page', 1));
            $this->_getDocMapper()->setupDocs($docs);
            $view->setVariable('docs', $docs);
            $view->setVariable('paginator', $pagination);
            $view->setVariable('search', $search);
        }

        return $view;
    }

    /**
     * Удаление файла
     * @return array|\Zend\Http\Response
     */
    public function deleteFileAction()
    {
        /** @var File $file */
        $file = $this->_getFileMapper()->findById($this->_getDocId());
        if ($file) {
            unlink('./public/files/'.$file->getFilename());
            $this->_getFileMapper()->deleteEntity($file);

            return $this->redirect()->toRoute('docs', ['action' => 'edit-doc', 'id' => $file->getDocId()]);
        }

        return $this->notFoundAction();
    }

    /**
     * @return Doc
     */
    protected function _form()
    {
        $form = new Doc('doc', []);

        $entity = $this->_getDocId() ? $this->_getDocMapper()->findById($this->_getDocId()) : new \Application\Entity\Doc();

        if ($this->getRequest()->isPost()) {
            $post = $post = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $entity->setType($form->getData()['type']);
                $entity->setTitle($form->getData()['title']);
                $entity->setDescription($form->getData()['description']);
                $entity->setResponsibleId(!empty($form->getData()['responsible_id']) ? $form->getData()['responsible_id'] : null);
                $entity->setResolution($form->getData()['resolution']);
                $entity->setPeriodExecution($form->getData()['period_execution']);
                $entity->isAgreed($form->getData()['is_agreed']);
                $entity->isApproved($form->getData()['is_approved']);
                $entity->isExecuted($form->getData()['is_executed']);
                $entity->setKeywords($form->getData()['keywords']);

                if (!$entity->getId()) {
                    $entity->setAuthorId($this->_getAuthService()->getIdentity()->getId());
                }

                if (isset($post['register'])) {
                    $entity->setDateRegister(date('Y-m-d H:i:s'));
                    $number = 'ТД'.sprintf('%09d', $entity->getId()).'-';
                    if ($entity->getType() == \Application\Entity\Doc::TYPE_INNER) {
                        $number .= 'ВН';
                    } elseif ($entity->getType() == \Application\Entity\Doc::TYPE_INPUT) {
                        $number .= 'В';
                    } else {
                        $number .= 'И';
                    }
                    $entity->setRegisterNumber($number);
                }

                if ($this->_getDocMapper()->saveEntity($entity)) {
                    $this->_getFileMapper()->setFile($form->getData()['file'], $entity->getId());

                    return $this->redirect()->toRoute('docs', ['action' => 'edit-doc', 'id' => $entity->getId()]);
                }
            }
        }

        $users = $this->_getUserMapper()->fetchAll();
        $usersOptionValue = [];
        foreach ($users as $user) {
            $usersOptionValue[$user->getId()] = $user->getDisplayName();
        }

        $form->get('responsible_id')->setValueOptions($usersOptionValue);


        $form->get('type')->setValue($entity->getType());
        $form->get('title')->setValue($entity->getTitle());
        $form->get('description')->setValue($entity->getDescription());
        $form->get('responsible_id')->setValue($entity->getResponsibleId());
        $form->get('resolution')->setValue($entity->getResolution());
        $form->get('period_execution')->setValue($entity->getPeriodExecution());
        $form->get('is_agreed')->setValue($entity->isAgreed());
        $form->get('is_approved')->setValue($entity->isApproved());
        $form->get('is_executed')->setValue($entity->isExecuted());
        $form->get('keywords')->setValue($entity->getKeywords());


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

    /**
     * @return \Application\Mapper\File
     */
    protected function _getFileMapper()
    {
        return $this->getServiceLocator()->get('Application\Mapper\File');
    }
}
