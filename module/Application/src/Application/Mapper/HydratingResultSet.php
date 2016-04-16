<?php

namespace Application\Mapper;

/**
 * Class HydratingResultSet
 *
 * @package Application\Mapper
 */
class HydratingResultSet extends \Zend\Db\ResultSet\HydratingResultSet
{
    /**
     * Вернет массив с ключами из метода $_key
     *
     * @param string $_key
     *
     * @return array
     */
    public function asArray($_key = 'Id')
    {
        $_key = 'get'.$_key;
        $return = [];

        foreach ($this as $row) {
            if (method_exists($row, $_key)) {
                $key = is_array($row->{$_key}()) ? implode('-', $row->{$_key}()) : $row->{$_key}();

                $return[$key] = $row;

            } else {
                $return[] = $row;
            }
        }

        return $return;
    }
}
