<?php
namespace Application\Model;

use Countable;
use DomainException;
use Laminas\Db\Sql\Ddl\Column\Integer;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\StringLength;

class Email implements InputFilterAwareInterface
{
    public $id;
    public $uid;
    public $name;
    public $email_string;
    private $inputFilter;
    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->uid     = !empty($data['uid']) ? $data['uid'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->email_string  = !empty($data['email_string']) ? $data['email_string'] : null;
    }
    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'uid'    => $this->uid,
            'name'   => $this->name,
            'email_string'  => $this->email_string,
        ];
    }
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
//creat inputfilter
    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();
        //id is Integer
        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
        //uid is Integer
        $inputFilter->add([
            'name' => 'uid',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
            'validators' => [
                [
                    'name' => IsInt::class,
                ],
            ]

        ]);
        //name type
        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        //email type
        $inputFilter->add([
            'name' => 'email_string',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => EmailAddress::class,
                    'type' => 'EmailAddress',
                ]
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}
