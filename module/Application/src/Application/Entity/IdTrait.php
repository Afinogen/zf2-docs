<?php

namespace Application\Entity;

/**
 * Class IdTrait
 *
 * @package Application\Entity
 */
trait IdTrait
{
    /** @var int */
    protected $_id;

    /**
     * Установка id
     *
     * @param int $_id
     */
    public function setId($_id)
    {
        $this->_id = $_id;
    }

    /**
     * Получение id
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }
}
