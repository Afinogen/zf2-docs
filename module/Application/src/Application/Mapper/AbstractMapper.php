<?php
/**
 * Created by PhpStorm.
 * User: Afinogen
 * Date: 16.04.2016
 * Time: 14:45
 */

namespace Application\Mapper;


use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcBase\Mapper\AbstractDbMapper;

/**
 * Class AbstractMapper
 * @package Application\Mapper
 */
class AbstractMapper extends AbstractDbMapper implements ServiceLocatorAwareInterface, EventManagerAwareInterface
{
    /**
     * Ключи
     *
     * @var array
     */
    protected static $_pri = [];
    /**
     * Атрибуты столбцов
     *
     * @var array
     */
    protected static $_attrs = [];

    /** @var \Zend\ServiceManager\ServiceLocatorInterface */
    protected $_serviceManager;

    /**
     * AbstractMapper constructor.
     * Инициализация параметров класса
     */
    public function __construct()
    {
        $this->setDbAdapter(GlobalAdapterFeature::getStaticAdapter());
        $this->tableName = $this->computeTable();
        $this->getEntityPrototype();
        $this->getHydrator();
        $this->initialize();
    }

    /**
     * @param ServiceLocatorInterface $_serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $_serviceLocator)
    {
        $this->_serviceManager = $_serviceLocator;
    }

    /**
     * @param string $_name
     *
     * @return array|object
     */
    public function srv($_name)
    {
        return $this->getServiceLocator()->get($_name);
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->_serviceManager;
    }

    /**
     * @param null $_where
     * @return \Application\Mapper\HydratingResultSet
     */
    public function fetchAll($_where = null)
    {
        $select = $this->getSql()->select($this->getTableName());
        if (!is_null($_where)) {
            $select->where($_where);
        }

        return $this->hydrate($this->select($select));
    }

    /**
     * @param $_results
     *
     * @return HydratingResultSet
     */
    public function hydrate($_results)
    {
        $items = new HydratingResultSet($this->getHydrator(), $this->getEntityPrototype());

        $results = is_array($_results) ? $_results : $_results->toArray();

        return $items->initialize($results);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getEntityPrototype()
    {
        if (!$this->entityPrototype) {
            $this->setEntityPrototype($this->computeEntityPrototype());
        }

        return $this->entityPrototype;
    }

    public function setEntityPrototype($_entityPrototype)
    {
        $this->entityPrototype = $_entityPrototype;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function computeEntityPrototype()
    {
        $matches = [];
        preg_match('/^\\\?([a-zA-Z]+)\\\Mapper\\\(.+)$/', get_called_class(), $matches);

        if (!$matches) {
            throw new \Exception('Unable to determine entity class name');
        }

        $className = "\\$matches[1]\\Entity\\$matches[2]";

        return new $className;
    }

    /**
     * @return string
     */
    public function computeTable()
    {
        $path = explode('\\', preg_replace('/^\\\?[a-zA-Z]+\\\Mapper\\\/', '', get_called_class()));


        // Обработка случаев, когда класс находится в одноименной папке.

        $cnt = count($path);

        if ($cnt > 1 && $path[$cnt - 2] == $path[$cnt - 1]) {
            unset($path[$cnt - 1]);
        }

        $table = strtolower(self::underline(implode('_', $path)));


        // Add module prefix if module is not base

//        if (is_string($this->_tablePrefix)) {
//            $table = "{$this->_tablePrefix}_{$table}";
//        } else {
//            if ($this->_tablePrefix) {
//                $autoPrefix = strtolower($this->_getModuleName());
//                $table = "{$autoPrefix}_{$table}";
//            }
//        }

        return $table;
    }

    public static function underline($_string)
    {
        return implode('_', static::split($_string));
    }

    public static function split($_string)
    {
        $res = [''];
        $lc = strtolower($_string);
        $uc = strtoupper($_string);
        $sep = ['_', '-', ':', '\\'];

        for ($j = 0, $len = strlen($_string), $i = 0; $i < $len; $i++) {
            $s = $_string{$i};

            if (
                !empty($res[$j]) &&
                (in_array($s, $sep) || ($s == $uc{$i} && !is_numeric($s)))
            ) {
                $res[++$j] = '';
            }

            if (!in_array($s, $sep)) {
                $res[$j] .= $lc{$i};
            }
        }

        return $res;
    }

    /**
     * @return Hydrator
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->setHydrator($this->computeHydrator());
        }

        return $this->hydrator;
    }

    /**
     * @param Hydrator $_hydrator
     */
    public function setHydrator($_hydrator)
    {
        $this->hydrator = $_hydrator;
    }

    /**
     * @return Hydrator
     */
    public function computeHydrator()
    {
        return new Hydrator($this);
    }

    /**
     * @param null|string $_prefix
     *
     * @return array
     */
    public function getAttrs($_prefix = null)
    {
        if (!isset(static::$_attrs[$this->getTableName()])) {
            static::$_attrs[$this->getTableName()] = $this->computeAttrs();
        }

        if ($_prefix) {
            $attrs = [];

            foreach (static::$_attrs[$this->getTableName()] as $value) {
                $attrs[$_prefix.$value] = $value;
            }

            return $attrs;
        }

        return static::$_attrs[$this->getTableName()];
    }

    /**
     * @return array
     */
    public function computeAttrs()
    {
//        $adapter = $this->getAdapter();
//        $sql = new Sql($adapter);

        $keys = [];
        $columns = [];

        $stmt = $this->getSql()->getAdapter()->getDriver()->getConnection()->execute('SHOW COLUMNS FROM `'.($this->getTableName()).'`');

        while ($res = $stmt->next()) {
            $columns[] = $res['Field'];
            if ($res['Key'] == 'PRI') {
                $keys[] = $res['Field'];
            }
        }

        static::$_pri[$this->getTableName()] = $keys;

        return $columns;
    }

    /**
     * @return array
     */
    public function getPri()
    {
        if (!isset(static::$_pri[$this->getTableName()])) {
            $this->getAttrs();
        }

        return static::$_pri[$this->getTableName()];
    }

    /**
     * @return array
     */
    public function computePri()
    {
        return [$this->getTableName().'_id'];
    }
}