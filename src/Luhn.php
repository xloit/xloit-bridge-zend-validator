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
 * A {@link Luhn} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class Luhn extends AbstractValidator
{
    /**
     *
     * @var string
     */
    const INVALID = 'luhnInvalid';

    /**
     *
     * @var string
     */
    const NO_MATCH = 'luhnNoMatch';

    /**
     * Validation failure message template definitions.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NO_MATCH => 'The input is not a valid Luhn format',
        self::INVALID  => 'Invalid type given. Numeric string expected'
    ];

    /**
     * Returns true if and only if $value matches luhn algorithm.
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

        for ($sum = 0, $i = 0; $i < $length; $i++) {
            /** @var string $value */
            $sum += ($i % 2 === 0) ? $value[$i] : array_sum(str_split($value[$i] * 2));
        }

        return (($sum % 10) === 0);
    }
}
