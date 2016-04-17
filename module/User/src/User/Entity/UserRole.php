<?php


namespace User\Entity;

use Application\Entity\IdTrait;

/**
 * Class UserRole
 * @package User\Entity
 */
class UserRole
{
    const ROLE_GUEST = 'guest';
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    use IdTrait;

    /**
     * @var int
     */
    protected $_name;

    /**
     * @var bool
     */
    protected $_isDefault;

    /**
     * @var int
     */
    protected $_parentId;

    /**
     * @return int
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param int $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @param bool|null $_isDefault
     * @return bool|null
     */
    public function isDefault($_isDefault = null)
    {
        if (!is_null($_isDefault)) {
            $this->_isDefault = $_isDefault;
        }

        return $this->_isDefault;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->_parentId;
    }

    /**
     * @param int $parentId
     */
    public function setParentId($parentId)
    {
        $this->_parentId = $parentId;
    }
}