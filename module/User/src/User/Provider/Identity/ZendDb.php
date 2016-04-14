<?php

namespace User\Provider\Identity;

use \BjyAuthorize\Provider\Identity\ZfcUserZendDb;
use \Zend\Db\Sql\Sql;
use \Zend\Db\Sql\Where;

/**
 * @todo Add options support
 */
class ZendDb extends ZfcUserZendDb
{
    /**
     * @var string
     */
    protected $tableName = 'user_role_linker';

    /**
     * @var string
     */
    protected $rolesTableName = 'user_role';

    /**
     * @var string
     */
    protected $idFieldName = 'user_role_id';

    /**
     * @var string
     */
    protected $roleIdFieldName = 'name';

    /**
     * @var string
     */
    protected $userIdFieldName = 'user_id';

    /**
     * {@inheritDoc}
     */
    public function getIdentityRoles()
    {
        $authService = $this->userService->getAuthService();

        $identity = $authService->getIdentity();

        if (!$authService->hasIdentity() || empty($identity)) {
            return [$this->getDefaultRole()];
        }

        // get roles associated with the logged in user
        $sql = new Sql($this->adapter);
        $select = $sql->select(['uhr' => $this->tableName]);
        $select->join(['ur' => $this->rolesTableName], "ur.{$this->idFieldName}=uhr.{$this->idFieldName}");
        $where = new Where();

        $where->equalTo($this->userIdFieldName, $identity->getId());

        $statement = $sql->prepareStatementForSqlObject($select->where($where))->execute();
        $roles = [];

        foreach ($statement as $row) {
            $roles[] = $row[$this->roleIdFieldName];
        }

        return $roles;
    }
}
