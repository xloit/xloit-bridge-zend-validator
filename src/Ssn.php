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

use Zend\Validator\AbstractValidator;

/**
 * A {@link Ssn} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class Ssn extends AbstractValidator
{
    /**
     *
     * @var string
     */
    const INVALID = 'ssnInvalid';

    /**
     *
     * @var string
     */
    const NO_MATCH = 'ssnNoMatch';

    /**
     *
     * @var string
     */
    const TOO_LONG = 'ssnTooLong';

    /**
     *
     * @var string
     */
    const TOO_SHORT = 'ssnTooShort';

    /**
     * Validation failure message template definitions.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NO_MATCH  => 'The input does not match the SSN format',
        self::TOO_SHORT => 'The input must be 9 characters',
        self::TOO_LONG  => 'The input must be 9 characters',
        self::INVALID   => 'Invalid type given.  Numeric string expected'
    ];

    /**
     * Simple Regular Expression Test for Format.
     *
     * @var string
     */
    protected $pattern = '/^(\d{3})(\d{2})(\d{4})$/';

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

        if (!preg_match($this->pattern, $value, $matches)) {
            $this->error(self::NO_MATCH);

            return false;
        }

        // no digit group can be all 0's
        /** @var array $matches */
        foreach ($matches as $match) {
            if (0 === (int) $match) {
                $this->error(self::NO_MATCH);

                return false;
            }
        }

        // invalid first digit groups 666 or 900-999
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($matches[1] == 666 || $matches[1] >= 900) {
            $this->error(self::NO_MATCH);

            return false;
        }

        // invalid for advertisements
        if ($value >= 987654320 && $value <= 987654329) {
            $this->error(self::NO_MATCH);

            return false;
        }
        // handle famous instance of 1938
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($value == '078051120') {
            $this->error(self::NO_MATCH);

            return false;
        }

        return true;
    }
}
