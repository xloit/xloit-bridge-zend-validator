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

use Zend\Validator\InArray;

/**
 * An {@link NotInArray} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class NotInArray extends InArray
{
    /**
     *
     *
     * @var string
     */
    const IN_ARRAY = 'inArray';

    /**
     *
     *
     * @var array
     */
    protected $messageTemplates = [
        self::IN_ARRAY => 'The input was found in the haystack',
    ];

    /**
     * Returns true if and only if $value is contained in the haystack option. If the strict
     * option is true, then the type of $value is also checked.
     *
     * See {@link http://php.net/manual/function.in-array.php#104501}
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        if (parent::isValid($value)) {
            $this->setValue($value);

            $this->error(self::IN_ARRAY);

            return false;
        }

        return true;
    }
}
