<?php


namespace Application\Entity;

/**
 * Class Doc
 * @package Application\Entity
 */
class Doc
{
    const TYPE_INPUT = 1;
    const TYPE_OUTPUT = 2;
    const TYPE_INNER = 3;

    use IdTrait;

    /**
     * Тип документа
     * @var int
     */
    protected $_type = 1;

    /**
     * Автор документа
     * @var int
     */
    protected $_authorId;

    protected $_dateCreate;

    protected $_dateRegister;

    /**
     * Ригистрационный номер
     * @var string
     */
    protected $_registerNumber;

    /**
     * Название документа
     * @var string
     */
    protected $_title;

    /**
     * Краткое содержание
     * @var string
     */
    protected $_description;

    /**
     * Флаг согласования
     * @var bool
     */
    protected $_isAgreed = false;

    /**
     * Флаг утверждения
     * @var bool
     */
    protected $_isApproved = false;

    /**
     * Флаг исполнения
     * @var bool
     */
    protected $_isExecuted;

    /**
     * Ответственный
     * @var int
     */
    protected $_responsibleId;

    /**
     * Резолюция
     * @var string
     */
    protected $_resolution;

    /**
     * Срок исполнения
     * @var string
     */
    protected $_periodExecution;

    /**
     * @return int
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param int $_type
     */
    public function setType($_type)
    {
        $this->_type = $_type;
    }

    /**
     * @param int $_type
     * @return string
     */
    public function getTypeName($_type = 1)
    {
        return self::getDocTypeNames()[$_type];
    }

    /**
     * @return string[]
     */
    public static function getDocTypeNames()
    {
        return [
            self::TYPE_INPUT => 'Документы входящие',
            self::TYPE_OUTPUT => 'Документы исходящие',
            self::TYPE_INNER => 'Внутренние документы'
        ];
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->_authorId;
    }

    /**
     * @param int $_authorId
     */
    public function setAuthorId($_authorId)
    {
        $this->_authorId = $_authorId;
    }

    /**
     * @return string
     */
    public function getRegisterNumber()
    {
        return $this->_registerNumber;
    }

    /**
     * @param string $registerNumber
     */
    public function setRegisterNumber($registerNumber)
    {
        $this->_registerNumber = $registerNumber;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @param bool|null $_isAgreed
     * @return bool|null
     */
    public function isAgreed($_isAgreed = null)
    {
        if (!is_null($_isAgreed)) {
            $this->_isAgreed = $_isAgreed;
        }

        return $this->_isAgreed;
    }

    /**
     * @param bool|null $_isApproved
     * @return bool|null
     */
    public function isApproved($_isApproved = null)
    {
        if (!is_null($_isApproved)) {
            $this->_isApproved = $_isApproved;
        }

        return $this->_isApproved;
    }

    /**
     * @param bool|null $_isExecuted
     * @return bool|null
     */
    public function isExecuted($_isExecuted = null)
    {
        if (!is_null($_isExecuted)) {
            $this->_isExecuted = $_isExecuted;
        }

        return $this->_isExecuted;
    }

    /**
     * @return int
     */
    public function getResponsibleId()
    {
        return $this->_responsibleId;
    }

    /**
     * @param int $responsibleId
     */
    public function setResponsibleId($responsibleId)
    {
        $this->_responsibleId = $responsibleId;
    }

    /**
     * @return string
     */
    public function getResolution()
    {
        return $this->_resolution;
    }

    /**
     * @param string $resolution
     */
    public function setResolution($resolution)
    {
        $this->_resolution = $resolution;
    }

    /**
     * @return string
     */
    public function getPeriodExecution()
    {
        return $this->_periodExecution;
    }

    /**
     * @param string $periodExecution
     */
    public function setPeriodExecution($periodExecution)
    {
        $this->_periodExecution = $periodExecution;
    }
}