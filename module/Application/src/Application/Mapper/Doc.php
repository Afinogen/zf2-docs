<?php


namespace Application\Mapper;

use User\Entity\User;

/**
 * Class Doc
 * @package Application\Mapper
 */
class Doc extends AbstractMapper
{
    /**
     * Получение списка документов с пагинацией
     * @param $_pagination
     * @param $_where
     * @param int $_page
     * @return \Application\Entity\Doc[]
     */
    public function getDocsPagination(&$_pagination, $_where, $_page = 1)
    {
        $select = $this->getSql()->select($this->getTableName());
        $select->where($_where);

        $_pagination = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\DbSelect($select, $this->getDbAdapter()));

        $_pagination->setDefaultItemCountPerPage(2);
        $_pagination->setCurrentPageNumber($_page);
        $resultSet = $_pagination->getIterator();

        return $this->resultToArray($this->hydrate($resultSet));
    }

    /**
     * @param \Application\Entity\Doc[] $_docs
     */
    public function setupDocs($_docs)
    {
        if ($_docs) {
            $userIds = [];
            foreach ($_docs as $doc) {
                $userIds[] = $doc->getAuthorId();
            }

            /** @var User[] $users */
            $users = $this->_getUserMapper()->fetchAll(['user_id' => $userIds]);
            foreach ($_docs as $doc) {
                if (isset($users[$doc->getAuthorId()])) {
                    $doc->setAuthor($users[$doc->getAuthorId()]);
                }
            }
        }
    }

    /**
     * Возвращает массив всех доступных ключевых слов
     * @return array
     */
    public function getDocKeywords()
    {
        $select = $this->getSql()->select($this->getTableName());
        $select->columns(['keywords']);
        $stmt = $this->getSql()->prepareStatementForSqlObject($select)->execute();

        $res = [];
        foreach ($stmt as $item) {
            if (!empty($item['keywords'])) {
                $res = array_merge($res, explode(',', $item['keywords']));
            }
        }

        return $res;
    }

    /**
     * @return \User\Mapper\User
     */
    protected function _getUserMapper()
    {
        return $this->getServiceLocator()->get('User\Mapper\User');
    }
}