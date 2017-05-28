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

use Zend\Validator\Digits;

/**
 * A {@link Number} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class Number extends Digits
{
    /**
     * Returns true if and only if $value only contains digit characters.
     *
     * @param int|float|string $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            $this->error(self::INVALID);

            return false;
        }

        $value = trim((string) $value);
        $this->value = $value;
        /** @var array $parts */
        $parts = explode('.', $value);

        if (count($parts) > 2) {
            $this->error(self::INVALID);

            return false;
        }

        foreach ($parts as $number) {
            // Check if number is a valid integer
            if (false !== filter_var($number, FILTER_VALIDATE_INT)) {
                continue;
            }

            // Check if number is invalid because of integer overflow
            $invalid = array_filter(
                str_split($number, strlen((string) PHP_INT_MAX) - 1),
                function($chunk) {
                    // Leading zeros should not invalidate the chunk
                    $chunk = ltrim($chunk, '0');

                    // Allow chunks containing zeros only
                    return '' !== $chunk && false === filter_var($chunk, FILTER_VALIDATE_INT);
                }
            );

            if (count($invalid) !== 0) {
                $this->error(self::INVALID);

                return false;
            }
        }

        return true;
    }
}
