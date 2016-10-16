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
 * An {@link Integer} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class Integer extends AbstractValidator
{
    /**
     *
     *
     * @var string
     */
    const INVALID = 'invalid';

    /**
     * Error Message Templates.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID => "'%value%' is not integer value"
    ];

    /**
     *
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_numeric($value)) {
            $this->error(self::INVALID);

            return false;
        }

        // Cast value as a string
        $value = (string) $value;

        $this->setValue($value);

        // check if the value is integer: numeric and not float
        if ($value !== (string) round($value)) {
            $this->error(self::INVALID, $value);

            return false;
        }

        return true;
    }
} 
