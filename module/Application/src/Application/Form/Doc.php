<?php


namespace Application\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Validator\Digits;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

/**
 * Class Doc
 * @package Application\Form
 */
class Doc extends Form
{
    /** @var \Zend\InputFilter\InputFilter */
    protected $_inputFilter;

    /**
     * Doc constructor.
     * @param int|null|string $name
     * @param array $options
     */
    public function __construct($name, array $options)
    {
        parent::__construct($name, $options);

        $this->setInputFilter($this->getInputFilter());

        $ele = new Element\Select('type');
        $ele->setAttribute('class', 'form-control');
        $ele->setLabel('Тип документа');
        $ele->setValueOptions(\Application\Entity\Doc::getDocTypeNames());
        $this->add($ele);

        $ele = new Element\Text('title');
        $ele->setAttribute('class', 'form-control');
        $ele->setLabel('Наименование документа');
        $this->add($ele);

        $ele = new Element\Textarea('description');
        $ele->setAttribute('class', 'form-control');
        $ele->setLabel('Краткое содержание');
        $this->add($ele);

        $ele = new Element\Select('responsible_id');
        $ele->setAttribute('class', 'form-control');
        $ele->setLabel('Ответственный');
        $ele->setEmptyOption('Выбор ответственного');
        $this->add($ele);

        $ele = new Element\Textarea('resolution');
        $ele->setAttribute('class', 'form-control');
        $ele->setLabel('Резолюция');
        $this->add($ele);

        $ele = new Element\Text('period_execution');
        $ele->setAttribute('class', 'form-control');
        $ele->setLabel('Срок исполнения');
        $this->add($ele);

        $ele = new Element\Checkbox('is_agreed');
        $ele->setLabel('Согласован');
        $this->add($ele);

        $ele = new Element\Checkbox('is_approved');
        $ele->setLabel('Утвержден');
        $this->add($ele);

        $ele = new Element\Checkbox('is_executed');
        $ele->setLabel('Исполнен');
        $this->add($ele);

        $ele = new Element\File('file');
        $ele->setLabel('Файлы');
        $ele->setAttribute('id', 'doc-file');
        $this->add($ele);

        $ele = new Element\Button('save');
        $ele->setValue('Сохранить');
        $ele->setLabel('Сохранить');
        $ele->setAttribute('class', 'btn btn-default');
        $ele->setAttribute('type', 'submit');
        $this->add($ele);
    }

    /**
     * @return InputFilter
     */
    public function getInputFilter()
    {
        if (is_null($this->_inputFilter)) {
            $this->_inputFilter = new InputFilter;
            $factory = new InputFactory;

            $this->_inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'type',
                        'required' => true,
                        'validators' => [
                            [
                                'name' => 'NotEmpty',
                                'options' => [
                                    'messages' => [
                                        NotEmpty::IS_EMPTY =>
                                            'Поле `Тип документа` должно быть заполнено'
                                    ]
                                ]
                            ],
                            [
                                'name' => 'Digits',
                                'options' => [
                                    'messages' => [
                                        Digits::NOT_DIGITS =>
                                            'Поле `Тип документа` должно содержать только цифры',
                                        Digits::STRING_EMPTY =>
                                            'Поле `Тип документа` должно содержать только цифры',
                                    ]
                                ]
                            ],
                        ]
                    ]
                )
            );

            $this->_inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'title',
                        'required' => true,
                        'validators' => [
                            [
                                'name' => 'NotEmpty',
                                'options' => [
                                    'messages' => [
                                        NotEmpty::IS_EMPTY =>
                                            'Поле `Наименование документа` должно быть заполнено'
                                    ]
                                ]
                            ],
                            [
                                'name' => 'StringLength',
                                'options' => [
                                    'min' => 2,
                                    'max' => 255,
                                    'messages' => [
                                        StringLength::TOO_SHORT =>
                                            'Поле `Наименование документа` должно быть от 2 до 255 символов',
                                        StringLength::TOO_LONG =>
                                            'Поле `Наименование документа` должно быть от 2 до 255 символов',
                                    ]
                                ],
                            ]
                        ]
                    ]
                )
            );

            $this->_inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'description',
                        'required' => false
                    ]
                )
            );

            $this->_inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'responsible_id',
                        'required' => false,
                        'validators' => [
                            [
                                'name' => 'Digits',
                                'options' => [
                                    'messages' => [
                                        Digits::NOT_DIGITS =>
                                            'Поле `Тип документа` должно содержать только цифры',
                                        Digits::STRING_EMPTY =>
                                            'Поле `Тип документа` должно содержать только цифры',
                                    ]
                                ]
                            ],
                        ]
                    ]
                )
            );

            $this->_inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'resolution',
                        'required' => false
                    ]
                )
            );

            $this->_inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'period_execution',
                        'required' => false
                    ]
                )
            );

            $this->_inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'is_agreed',
                        'required' => false
                    ]
                )
            );

            $this->_inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'is_approved',
                        'required' => false
                    ]
                )
            );

            $this->_inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'is_executed',
                        'required' => false
                    ]
                )
            );
        }

        return $this->_inputFilter;
    }
}