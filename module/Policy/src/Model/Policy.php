<?php
namespace Policy\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Filter\ToFloat;
use Laminas\Filter\DateTimeSelect;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;
use Laminas\Validator\Regex;
use Laminas\Validator\Date;
use Policy\Validator\PolicyDateValidator;

class Policy implements InputFilterAwareInterface
{
    public $id;
    public $firstName;
    public $lastName;
    public $policyNumber;
    public $startDate;
    public $endDate;
    public $premium;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id           = !empty($data['id']) ? $data['id'] : null;
        $this->firstName    = !empty($data['first_name']) ? $data['first_name'] : null;
        $this->lastName     = !empty($data['last_name']) ? $data['last_name'] : null;
        $this->policyNumber = !empty($data['policy_number']) ? $data['policy_number'] : null;
        $this->startDate    = !empty($data['start_date']) ? $data['start_date'] : null;
        $this->endDate      = !empty($data['end_date']) ? $data['end_date'] : null;
        $this->premium      = !empty($data['premium']) ? $data['premium'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'            => $this->id,
            'first_name'    => $this->firstName,
            'last_name'     => $this->lastName,
            'policy_number' => $this->policyNumber,
            'start_date'    => $this->startDate,
            'end_date'      => $this->endDate,
            'premium'       => $this->premium,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'first_name',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'last_name',
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
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'start_date',
            'required' => true,
            'filters' => [
                ['name' => DateTimeSelect::class],
            ],
            'validators' => [
                [
                    'name' => Date::class,
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'end_date',
            'required' => true,
            'filters' => [
                ['name' => DateTimeSelect::class],
            ],
            'validators' => [
                [
                    'name' => Date::class,
                ]
            ],
        ]);

        $inputFilter->add([
            'name' => 'policy_number',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 5,
                        'max' => 25,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'premium',
            'required' => false,
            'filters' => [
                ['name' => ToFloat::class],
            ],
            'validators' => [
                [
                    'name' => Regex::class,
                    'options' => [
                        'pattern' => "/^[0-9]\d*(\.\d+)?$/",
                        'messages' => [
                            'regexNotMatch' => 'Please provide a valid premium value'
                        ]
                    ],
                ],
            ],

        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}