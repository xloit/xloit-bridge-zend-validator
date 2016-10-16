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

use Zend\Validator\File\MimeType as AbstractMimeType;

/**
 * A {@link MimeType} class.
 *
 * @package Xloit\Bridge\Zend\Validator\File
 */
class MimeType extends AbstractMimeType
{
    use FileTrait;

    /**
     * Returns true if the mimetype of the file matches the given ones. Also parts of mimetypes can be
     * checked. If you give for example "image" all image mime types will be accepted like "image/gif",
     * "image/jpeg" and so on.
     *
     * @param  string|array $value Real file to check
     * @param  array        $file  File data from \Zend\File\Transfer\Transfer (optional)
     *
     * @return boolean
     */
    public function isValid($value, $file = null)
    {
        if ($this->allowEmpty && $this->isFileEmpty($value, $file)) {
            return true;
        }

        return parent::isValid($value, $file);
    }
}
