<?php

namespace Policy\Model\Policy\Validator;

use Laminas\Validator\AbstractValidator;

class PolicyDateValidator extends AbstractValidator
{
    public const NOT_GREATER   = 'notGreaterThan';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_GREATER      => 'Policy Enddate should be greater that Policy Start Date',
    ];

    /** @var array */
    protected $messageVariables = [
        'date' => 'date',
    ];

    /**
     * Minimum value
     *
     * @var mixed
     */
    protected $date;

    /**
     * Sets validator options
     *
     * @param  array|Traversable $options
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($options = null)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (! is_array($options)) {
            $options      = func_get_args();
            $temp['date'] = array_shift($options);

            $options = $temp;
        }

        if (! array_key_exists('date', $options)) {
            throw new Exception\InvalidArgumentException("Missing option 'date'");
        }

        parent::__construct($options);
    }

    /**
     * Returns true if and only if $value is greater than start date
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);

        if ( strtotime( $this->date) >= strtotime( $value )) {
            $this->error(self::NOT_GREATER);
            return false;
        }

        return true;
    }

    /**
     * Returns the min option
     *
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the date option
     *
     * @param  mixed $date
     * @return $this Provides a fluent interface
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
}
