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

/**
 * A {@link FileTrait} trait.
 *
 * @package Xloit\Bridge\Zend\Validator\File
 */
trait FileTrait
{
    /**
     *
     *
     * @var bool
     */
    protected $allowEmpty = false;

    /**
     *
     *
     * @return bool
     */
    public function isAllowEmpty()
    {
        return $this->allowEmpty;
    }

    /**
     *
     *
     * @param bool $allowEmpty
     *
     * @return $this
     */
    public function setAllowEmpty($allowEmpty)
    {
        $this->allowEmpty = (bool) $allowEmpty;

        return $this;
    }

    /**
     * Indicates whether the file is defined.
     *
     * @param string|array $value Real file to check.
     * @param array        $file  File data from {@link \Zend\File\Transfer\Transfer} (optional).
     *
     * @return bool
     */
    protected function isFileEmpty($value, $file = null)
    {
        $source   = null;
        $filename = null;

        if (is_string($value) && is_array($file)) {
            // Legacy Zend\Transfer API support
            $filename = $file['name'];
            $source   = $file['tmp_name'];
        } elseif (is_array($value)) {
            /** @noinspection UnSafeIsSetOverArrayInspection */
            if (isset($value['tmp_name'])) {
                $source = $value['tmp_name'];
            }

            /** @noinspection UnSafeIsSetOverArrayInspection */
            if (isset($value['name'])) {
                $filename = $value['name'];
            }
        } else {
            $source   = $value;
            $filename = basename($source);
        }

        /** @noinspection IsEmptyFunctionUsageInspection */
        return empty($source) || empty($filename);
    }
}
