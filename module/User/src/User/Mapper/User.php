<?php


namespace User\Mapper;

use Application\Mapper\AbstractMapper;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\HydratorInterface;
use ZfcUser\Mapper\UserInterface;

/**
 * Class User
 * @package User\Mapper
 */
class User extends AbstractMapper implements UserInterface
{
    const TABLE_USER_HAS_ROLE = 'user_has_role';

    protected $tableName = 'user';

    /**
     * Установка ролей пользователю
     * @param array $_userRole
     * @param $_userId
     */
    public function setRoles(array $_userRole, $_userId)
    {
        $sql = new Sql($this->getDbAdapter());
        $delete = $sql->delete(self::TABLE_USER_HAS_ROLE);
        $delete->where(['user_id' => $_userId]);
        $sql->prepareStatementForSqlObject($delete)->execute();

        $roles = $this->_getUserRoleMapper()->fetchAll();
        foreach ($roles as $role) {
            if (in_array($role->getName(), $_userRole)) {
                $insert = $sql->insert(self::TABLE_USER_HAS_ROLE);
                $insert->values(['user_id' => $_userId, 'user_role_id' => $role->getId()]);
                $sql->prepareStatementForSqlObject($insert)->execute();
            }
        }
    }

    public function findByEmail($email)
    {
        $select = $this->getSelect()
            ->where(['email' => $email]);

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, ['entity' => $entity]);

        return $entity;
    }

    public function findByUsername($username)
    {
        $select = $this->getSelect()
            ->where(['username' => $username]);

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, ['entity' => $entity]);

        return $entity;
    }

    public function findById($id)
    {
        $select = $this->getSelect()
            ->where(['user_id' => $id]);

        $entity = $this->select($select)->current();
        $this->getEventManager()->trigger('find', $this, ['entity' => $entity]);

        return $entity;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function insert($entity, $tableName = null, HydratorInterface $hydrator = null)
    {
        $result = parent::insert($entity, $tableName, $hydrator);
        $entity->setId($result->getGeneratedValue());

        return $result;
    }

    public function update($entity, $where = null, $tableName = null, HydratorInterface $hydrator = null)
    {
        if (!$where) {
            $where = ['user_id' => $entity->getId()];
        }

        return parent::update($entity, $where, $tableName, $hydrator);
    }

    /**
     * @return \User\Mapper\UserRole
     */
    protected function _getUserRoleMapper()
    {
        return $this->srv('User\Mapper\UserRole');
    }
}