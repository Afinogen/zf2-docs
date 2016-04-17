<?php


namespace Application\Entity;

/**
 * Class File
 * @package Application\Entity
 */
class File
{
    use IdTrait;

    /**
     * Имя файла
     * @var string
     */
    protected $_filename;

    /**
     * Дата загрузки
     * @var string
     */
    protected $_date;

    /**
     * Id документа к которому привязан
     * @var int
     */
    protected $_docId;

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->_filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->_filename = $filename;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->_date = $date;
    }

    /**
     * @return int
     */
    public function getDocId()
    {
        return $this->_docId;
    }

    /**
     * @param int $docId
     */
    public function setDocId($docId)
    {
        $this->_docId = $docId;
    }
}