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

use Traversable;
use Zend\I18n\Validator\PostCode as ZfPostCode;
use Zend\Stdlib\ArrayUtils;

/**
 * A {@link PostCode} class. Based off ISO-3611-1 countries.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class PostCode extends ZfPostCode
{
    /**
     *
     * @var string
     */
    const INVALID = 'postcodeInvalid';

    /**
     *
     * @var string
     */
    const UNSUPPORTED = 'postcodeUnsupported';

    /**
     *
     * @var string
     */
    const NO_MATCH = 'postcodeNoMatch';

    /**
     *
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID     => 'Invalid type given. String or integer expected',
        self::UNSUPPORTED => 'The country provided is currently unsupported',
        self::NO_MATCH    => 'The input does not appear to be a postal code'
    ];

    /**
     *
     *
     * @var string
     */
    protected $country;

    /**
     * Returns the value of country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of country.
     *
     * @param  string $country
     *
     * @return PostCode
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Constructor for the {@link PostCode} validator.
     *
     * @param array|Traversable $options
     *
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     * @throws \Zend\Validator\Exception\ExtensionNotLoadedException
     */
    public function __construct($options = [])
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (array_key_exists('country', $options)) {
            $this->setCountry($options['country']);
        }

        parent::__construct($options);
    }

    /**
     * Returns true if and only if $value matches post code format.
     *
     * @param  string $value
     * @param  array  $context
     *
     * @return bool
     */
    public function isValid($value = null, $context = null)
    {
        if (!is_scalar($value)) {
            $this->error(self::INVALID);

            return false;
        }

        $this->setValue($value);

        $country = $this->getCountry();

        if (!array_key_exists($country, self::$postCodeRegex) && array_key_exists($country, $context)) {
            $country = $context[$country];
        }

        if (!array_key_exists($country, self::$postCodeRegex)) {
            $this->error(self::UNSUPPORTED);

            return false;
        }

        $format = self::$postCodeRegex[$country];

        if ($format[0] !== '/') {
            $format = '/^' . $format;
        }

        if ($format[strlen($format) - 1] !== '/') {
            $format .= '$/';
        }

        if (!preg_match($format, $value)) {
            $this->error(self::NO_MATCH);

            return false;
        }

        return true;
    }
}
