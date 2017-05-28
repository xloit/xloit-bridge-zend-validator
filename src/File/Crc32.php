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

use Zend\Validator\File\Crc32 as AbstractCrc32;

/**
 * A {@link Crc32} class.
 *
 * @package Xloit\Bridge\Zend\Validator\File
 */
class Crc32 extends AbstractCrc32
{
    use FileTrait;

    /**
     * Returns true if and only if the given file confirms the set hash.
     *
     * @param string|array $value Real file to check.
     * @param array        $file  File data from {@link \Zend\File\Transfer\Transfer} (optional).
     *
     * @return bool
     */
    public function isValid($value, $file = null)
    {
        if ($this->allowEmpty && $this->isFileEmpty($value, $file)) {
            return true;
        }

        return parent::isValid($value, $file);
    }
}
