<?php
/**
 * Created by PhpStorm.
 * User: Afinogen
 * Date: 17.04.2016
 * Time: 1:15
 */

namespace Application\Form;


use Zend\Form\Form;

/**
 * Class Doc
 * @package Application\Form
 */
class Doc extends Form
{
    public function __construct($name, array $options)
    {
        parent::__construct($name, $options);
    }
}