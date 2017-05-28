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

use Zend\Validator\Regex;

/**
 * An {@link IataAirlineCode} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class IataAirlineCode extends Regex
{
    /**
     * Error: Regex does not match.
     *
     * @var string
     */
    const BAD_FORMAT = 'badFormat';

    /**
     * Error: Not String.
     *
     * @var string
     */
    const INVALID = 'notString';

    /**
     * Default pattern Regular Expressions can be used for tokenize, validating, and parsing folder name.
     *
     * @var string
     */
    const PATTERN = '/^[A-Z0-9]{2}[A-Z]?$/';

    /**
     * Error: Empty String.
     *
     * @var string
     */
    const STR_EMPTY = 'emptyString';

    /**
     * Error: String length too long.
     *
     * @var string
     */
    const TOO_LONG = 'tooLong';

    /**
     * Error Message Templates.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID    => 'IATA Airline codes should be an alphanumeric string',
        self::STR_EMPTY  => 'I received an empty string. Please input the IATA Code',
        self::BAD_FORMAT => 'IATA airline codes should be two or three letters or digits. Letters should be uppercase',
        self::TOO_LONG   => 'IATA airline codes are a maximum of 3 characters long'
    ];

    /**
     * Constructor to prevent {@link IataAirlineCode} from being loaded more than once.
     *
     * @param  array|\Traversable|string $config
     *
     * @throws \Zend\Validator\Exception\InvalidArgumentException
     */
    public function __construct($config = [])
    {
        $this->setPattern(self::PATTERN);

        parent::__construct($config);
    }

    /**
     * Returns true if and only if $value meets the validation requirements.
     * If $value fails validation, then this method returns false, and getMessages() will return an array of messages
     * that explain why the validation failed.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_string($value)) {
            $this->error(self::INVALID);

            return false;
        }

        $this->setValue($value);

        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($value)) {
            $this->error(self::STR_EMPTY);

            return false;
        }

        if (strlen($value) > 3) {
            $this->error(self::TOO_LONG);

            return false;
        }

        if (!parent::isValid($value)) {
            $this->error(self::BAD_FORMAT);

            return false;
        }

        return true;
    }
}
