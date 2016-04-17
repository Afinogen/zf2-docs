<?php


namespace User\Entity;


use Application\Entity\IdTrait;

/**
 * Class User
 * @package User\Entity
 */
class User
{
    use IdTrait;

    /**
     * @var string
     */
    protected $_username;

    /**
     * @var string
     */
    protected $_email;

    /**
     * @var string
     */
    protected $_displayName;

    /**
     * @var string
     */
    protected $_password;

    /**
     * @var int
     */
    protected $_state;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->_displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->_displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * @param int $state
     */
    public function setState($state)
    {
        $this->_state = $state;
    }
}