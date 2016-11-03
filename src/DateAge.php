<?php
/**
 * This source file is part of Xloit project.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * <http://www.opensource.org/licenses/mit-license.php>
 * If you did not receive a copy of the license and are unable to obtain it through the world-wide-web,
 * please send an email to <license@xloit.com> so we can send you a copy immediately.
 *
 * @license   MIT
 * @link      http://xloit.com
 * @copyright Copyright (c) 2016, Xloit. All rights reserved.
 */

namespace Xloit\Bridge\Zend\Validator;

use DateTime;
use Traversable;
use Xloit\Std\ArrayUtils;
use Zend\Validator\Date as AbstractDate;

/**
 * A {@link DateAge} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class DateAge extends AbstractDate
{
    /**#@+
     * Validity constants
     *
     * @var string
     */
    const NOT_GREATER = 'notGreaterThan';

    /**#@-*/

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID      => 'Invalid type given. String, integer, array or DateTime expected',
        self::INVALID_DATE => 'The date does not appear to be a valid date',
        self::FALSEFORMAT  => 'The date does not fit the date format "%format%"',
        self::NOT_GREATER  => 'The date is not greater than "%minimum%" year(s)'
    ];

    /**
     *
     *
     * @var array
     */
    protected $messageVariables = [
        'format'  => 'format',
        'minimum' => 'minimum'
    ];

    /**
     *
     *
     * @var int
     */
    protected $minimum = 17;

    /**
     * Constructor to prevent {@link DateAge} from being loaded more than once.
     *
     * @param array|Traversable $options
     *
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function __construct($options = [])
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (array_key_exists('minimum', $options)) {
            $this->setMinimum($options['minimum']);
        }

        parent::__construct($options);
    }

    /**
     * Returns the value of minimum.
     *
     * @return int
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * Set the value of minimum.
     *
     * @param int $minimum
     *
     * @return static
     */
    public function setMinimum($minimum)
    {
        $this->minimum = $minimum;

        return $this;
    }

    /**
     * Returns true if and only if $value matches iban format.
     *
     *
     * @param  string $value
     * @param  array  $context
     *
     * @return bool
     */
    public function isValid($value = null, $context = null)
    {
        $isValid = parent::isValid($value);
        $date    = $this->convertToDateTime($value);

        if (!$isValid || !$date) {
            return false;
        }

        $now = new DateTime();

        $now->setTime(23, 59, 59);
        $date->setTime(0, 0, 0);

        if ($date > $now || $date->diff($now)->y < $this->minimum) {
            $this->error(self::NOT_GREATER);

            return false;
        }

        return true;
    }
}
