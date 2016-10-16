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
 * A {@link RoutingTransitNumber} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class RoutingTransitNumber extends AbstractValidator
{
    /**
     *
     * @var string
     */
    const NO_MATCH = 'routingTransitNumberNoMatch';

    /**
     *
     * @var string
     */
    const UNSUPPORTED = 'routingTransitNumberUnsupported';

    /**
     *
     * @var string
     */
    const TOO_SHORT = 'routingTransitNumberTooShort';

    /**
     *
     * @var string
     */
    const TOO_LONG = 'routingTransitNumberTooLong';

    /**
     *
     * @var string
     */
    const INVALID = 'routingTransitNumberInvalid';

    /**
     * Validation failure message template definitions.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NO_MATCH    => 'The input given is not a routing transit number',
        self::UNSUPPORTED => 'The country provided is currently unsupported',
        self::TOO_SHORT   => 'The input must was too short',
        self::TOO_LONG    => 'The input must was too long',
        self::INVALID     => 'Invalid type given.  Numeric string expected'
    ];

    /**
     * Countries that support routing transit numbers.
     *
     * @var array
     */
    protected $countrySupported = [
        'US',
        'CA'
    ];

    /**
     * Country rules to apply by default checks all.
     *
     * @var string
     */
    protected $country;

    /**
     * ABA Table.
     *
     * @var array
     */
    protected $aba = [
        'US' => [
            [
                0,
                12
            ],
            [
                21,
                32
            ],
            [
                61,
                72
            ],
            [
                80,
                80
            ]
        ],
        'CA' => [
            [
                1,
                399
            ],
            [
                500,
                699
            ],
            [
                800,
                999
            ]
        ]
    ];

    /**
     * Constructor to prevent {@link RoutingTransitNumber} from being loaded more than once.
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
     * @return RoutingTransitNumber
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Returns true if and only if $value has a checksum match.
     *
     * @param  string $value
     * @param  array  $context
     *
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if (!is_scalar($value) || !is_numeric($value)) {
            $this->error(self::INVALID);

            return false;
        }

        $country = $this->getCountry();

        if (array_key_exists($country, $context)) {
            $country = $context[$country];
        }

        if (!in_array($country, $this->countrySupported, true)) {
            $this->error(self::UNSUPPORTED);

            return false;
        }

        if ($country === 'US') {
            if (strlen($value) < 9) {
                $this->error(self::TOO_SHORT);

                return false;
            }

            if (strlen($value) > 9) {
                $this->error(self::TOO_LONG);

                return false;
            }

            $aba   = (int) substr($value, 0, 2);
            $found = false;

            foreach ($this->aba['US'] as $check) {
                if ($aba >= $check[0] && $aba <= $check[1]) {
                    $found = true;
                }
            }

            if (!$found) {
                $this->error(self::NO_MATCH);

                return false;
            }

            /** @var string $value */
            $checksum = 7 * ($value[0] + $value[3] + $value[6]);
            $checksum += 3 * ($value[1] + $value[4] + $value[7]);
            $checksum += 9 * ($value[2] + $value[5]);

            if ($value[8] !== $checksum % 10) {
                $this->error(self::NO_MATCH);

                return false;
            }
        }

        if ($country === 'CA') {
            if (strlen($value) < 8) {
                $this->error(self::TOO_SHORT);

                return false;
            }

            if (strlen($value) > 8) {
                $this->error(self::TOO_LONG);

                return false;
            }

            $aba   = (int) substr($value, -3);
            $found = false;

            foreach ($this->aba['CA'] as $check) {
                if ($aba >= $check[0] && $aba <= $check[1]) {
                    $found = true;
                }
            }

            if (!$found) {
                $this->error(self::NO_MATCH);

                return false;
            }
        }

        return true;
    }
}
