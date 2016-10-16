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
use Xloit\Std\ArrayUtils;
use Zend\Validator\AbstractValidator;

/**
 * A {@link Swift} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class Swift extends AbstractValidator
{
    /**
     *
     * @var string
     */
    const NO_MATCH = 'swiftNoMatch';

    /**
     *
     * @var string
     */
    const INVALID = 'swiftInvalid';

    /**
     * Validation failure message template definitions.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NO_MATCH => 'The input does not match the SWIFT-BIC format',
        self::INVALID  => 'Invalid type given.  String expected'
    ];

    /**
     * Regular Expression pattern.
     *
     * @var string
     */
    protected $pattern = '/^([A-Z]{4})([A-Z]{2})([A-Z0-9]{2})([A-Z0-9]{3})?$/';

    /**
     * ISO 3611 Country Code.
     *
     * @var string
     */
    protected $country;

    /**
     * Constructor to prevent {@link Swift} from being loaded more than once.
     *
     * @param array|Traversable $options
     *
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
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
     * Returns the value of country.
     *
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
     *
     * @param  string $country
     *
     * @return Iban
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Returns true if and only if $value matches iban format.
     *
     *
     * @param  string $value
     * @param  array  $context
     *
     * @return bool
     */
    public function isValid($value = null, $context = null)
    {
        if (!is_string($value)) {
            $this->error(self::INVALID);

            return false;
        }

        if (!preg_match($this->pattern, $value, $matches)) {
            $this->error(self::NO_MATCH);

            return false;
        }

        $country = $this->getCountry();

        if ($country !== null) {
            if (array_key_exists($country, $context)) {
                $country = $context[$country];
            }

            if (strcasecmp($matches[2], $country) !== 0) {
                $this->error(self::NO_MATCH);

                return false;
            }
        }

        return true;
    }
}
