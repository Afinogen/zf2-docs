<?php

namespace Application\Mapper;

/**
 * Class File
 * @package Application\Mapper
 */
class File extends AbstractMapper
{
    /**
     * Сохранение файлов
     * @param array $_files
     * @param int $_docId
     */
    public function setFile(array $_files, $_docId)
    {
        if (count($_files)) {
            foreach($_files as $file) {
                if ($file['error'] == 0) {
                    rename($file['tmp_name'], './public/files/'.$file['name']);
                    $entity = new \Application\Entity\File();
                    $entity->setDocId($_docId);
                    $entity->setFilename($file['name']);
                    $this->saveEntity($entity);
                }
            }
        }
    }
}
