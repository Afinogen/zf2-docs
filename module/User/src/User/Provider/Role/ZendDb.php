<?php

namespace User\Provider\Role;

use \BjyAuthorize\Acl\Role;
use \BjyAuthorize\Provider\Role\ZendDb as ZfcUserZendDbRoleProvider;
use \Zend\ServiceManager\ServiceLocatorInterface;
use \Zend\Db\Sql\Select;
use \Zend\Db\Sql\Sql;

class ZendDb extends ZfcUserZendDbRoleProvider
{
    /**
     * @var int
     */
    protected $idFieldName = 'user_role_id';

    /**
     * @param array $options
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function __construct($options, ServiceLocatorInterface $serviceLocator)
    {
        parent::__construct($options, $serviceLocator);

        if (isset($options['identifier_field'])) {
            $this->idFieldName = $options['identifier_field'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        /* @var $adapter \Zend\Db\Adapter\Adapter */
        $adapter = $this->serviceLocator->get($this->adapterName);
        $sql = new Sql($adapter);

        $select = $sql->select(['r' => $this->tableName]);
        $select->join(
            ['rp' => $this->tableName], "r.{$this->parentRoleFieldName} = rp.{$this->idFieldName}", ['parent_name' => $this->roleIdFieldName],
            Select::JOIN_LEFT
        );

        $stmt = $sql->prepareStatementForSqlObject($select);

        $roles = [];

        // Pass One: Build each object
        foreach ($stmt->execute() as $row) {
            $roleId = $row[$this->roleIdFieldName];
            $roles[$roleId] = new Role($roleId, $row['parent_name']);
        }

        // Pass Two: Re-inject parent objects to preserve hierarchy
        /* @var $roleObj Role */
        foreach ($roles as $roleObj) {
            $parentRoleObj = $roleObj->getParent();

            if ($parentRoleObj && $parentRoleObj->getRoleId()) {
                $roleObj->setParent($roles[$parentRoleObj->getRoleId()]);
            }
        }

        return array_values($roles);
    }
}