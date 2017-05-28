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
 * An {@link Ein} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class Ein extends Regex
{
    /**
     *
     * @var string
     */
    const INVALID = 'einInvalid';

    /**
     *
     * @var string
     */
    const NO_MATCH = 'einNoMatch';

    /**
     * Default pattern Regular Expressions.
     *
     * @var string
     */
    const PATTERN = '/^(\d{2})(\d{7})$/';

    /**
     *
     * @var string
     */
    const TOO_LONG = 'einTooLong';

    /**
     *
     * @var string
     */
    const TOO_SHORT = 'einTooShort';

    /**
     * Validation failure message template definitions.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NO_MATCH  => 'The input does not match the EIN format',
        self::TOO_SHORT => 'The input must be 9 characters',
        self::TOO_LONG  => 'The input must be 9 characters',
        self::INVALID   => 'Invalid type given.  Numeric string expected'
    ];

    /**
     * Valid EAN prefix ranges (int).
     *
     * @link  http://www.irs.gov/Businesses/Small-Businesses-&-Self-Employed/How-EINs-are-Assigned-and-Valid-EIN-Prefixes
     *
     * @var array
     */
    protected $prefix = [
        [
            1,
            6
        ],
        [
            10,
            16
        ],
        [
            20,
            27
        ],
        [
            30,
            39
        ],
        [
            40,
            48
        ],
        [
            50,
            59
        ],
        [
            60,
            63
        ],
        [
            65,
            68
        ],
        [
            71,
            77
        ],
        [
            80,
            88
        ],
        [
            90,
            95
        ],
        [
            98,
            99
        ]
    ];

    /**
     * Constructor to prevent {@link Ein} from being loaded more than once.
     *
     * @param array|\Traversable|string $config
     *
     * @throws \Zend\Validator\Exception\InvalidArgumentException
     */
    public function __construct($config = [])
    {
        $this->setPattern(self::PATTERN);

        parent::__construct($config);
    }

    /**
     * Returns true if and only if $value matches iban format.
     *
     * TODO: port php-iban library for more complete validation
     *
     * @param string $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_scalar($value) || !is_numeric($value)) {
            $this->error(self::INVALID);

            return false;
        }

        $length = strlen($value);

        if ($length < 9) {
            $this->error(self::TOO_SHORT);

            return false;
        }

        if ($length > 9) {
            $this->error(self::TOO_LONG);

            return false;
        }

        if (!parent::isValid($value)) {
            $this->error(self::NO_MATCH);

            return false;
        }

        preg_match($this->pattern, $value, $matches);

        // check for EIN prefixes
        $intPrefix = (int) $matches[1];

        foreach ($this->prefix as $check) {
            if ($intPrefix >= $check[0] && $intPrefix <= $check[1]) {
                return true;
            }
        }

        $this->error(self::NO_MATCH);

        return false;
    }
}
