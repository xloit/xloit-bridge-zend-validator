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

namespace Xloit\Bridge\Zend\Validator\File;

use Zend\Validator\Regex;

/**
 * A {@link FileName} class.
 *
 * @package Xloit\Bridge\Zend\Validator\File
 */
class FileName extends Regex
{
    /**
     * Default pattern Regular Expressions.
     *
     * @var string
     */
    const PATTERN = '#^(\\.{1,2}|.*[\\x00/?*:;{}\\\\].*)$#';

    /**
     * Constructor to prevent {@link FileName} from being loaded more than once.
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
} 
