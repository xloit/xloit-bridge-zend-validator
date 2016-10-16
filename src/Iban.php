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
 * An {@link Iban} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class Iban extends AbstractValidator
{
    /**
     *
     * @var string
     */
    const NO_MATCH = 'ibanNoMatch';

    /**
     *
     * @var string
     */
    const UNSUPPORTED = 'ibanUnsupported';

    /**
     *
     * @var string
     */
    const INVALID_LENGTH = 'ibanInvalidLength';

    /**
     *
     * @var string
     */
    const INVALID = 'ibanInvalid';

    /**
     * Validation failure message template definitions.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NO_MATCH       => 'The input does not match the IBAN format',
        self::UNSUPPORTED    => 'The country provided is currently unsupported',
        self::INVALID_LENGTH => 'The input must be either 8 or 11 characters',
        self::INVALID        => 'Invalid type given.  String expected'
    ];

    /**
     * IBAN formats by ISO-3611.
     *
     * @var array
     */
    protected $iban = [
        'AD' => [
            'bban' => '/^([0-9]{4})([0-9]{4})([A-Za-z0-9]{12})$/',
            'iban' => '/^(AD)([0-9]{2})([0-9]{4})([0-9]{4})([A-Za-z0-9]{12})$/'
        ],
        'AE' => [
            'bban' => '/^([0-9]{3})([0-9]{16})$/',
            'iban' => '/^(AE)([0-9]{2})([0-9]{3})([A-Za-z0-9]{16})$/'
        ],
        'AL' => [
            'bban' => '/^([0-9]{8})([A-Za-z0-9]{16})$/',
            'iban' => '/^(AL)([0-9]{2})([0-9]{8})([A-Za-z0-9]{16})$/'
        ],
        'AT' => [
            'bban' => '/^([0-9]{5})([0-9]{11})$/',
            'iban' => '/^(AT)([0-9]{2})([0-9]{5})([0-9]{11})$/'
        ],
        'AZ' => [
            'bban' => '/^([A-Z]{4})([A-Za-z0-9]{20})$/',
            'iban' => '/^(AZ)([0-9]{2})([A-Z]{4})([A-Za-z0-9]{20})$/'
        ],
        'BA' => [
            'bban' => '/^([0-9]{3})([0-9]{3})([0-9]{8})([0-9]{2})$/',
            'iban' => '/^(BA)([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{8})([0-9]{2})$/'
        ],
        'BE' => [
            'bban' => '/^([0-9]{3})([0-9]{7})([0-9]{2})$/',
            'iban' => '/^(BE)([0-9]{2})([0-9]{3})([0-9]{7})([0-9]{2})$/'
        ],
        'BG' => [
            'bban' => '/^([A-Z]{4})([0-9]{4})([0-9]{2})([A-Za-z0-9]{8})$/',
            'iban' => '/^(BG)([0-9]{2})([A-Z]{4})([0-9]{4})([0-9]{2})([A-Za-z0-9]{8})$/'
        ],
        'BH' => [
            'bban' => '/^([A-Z]{4})([A-Za-z0-9]{14})$/',
            'iban' => '/^(BH)([0-9]{2})([A-Z]{4})([A-Za-z0-9]{14})$/'
        ],
        'BR' => [
            'bban' => '/^([0-9]{8})([0-9]{5})([0-9]{10})([A-Z])([A-Za-z0-9])$/',
            'iban' => '/^BR([0-9]{2})([0-9]{8})([0-9]{5})([0-9]{10})([A-Z])([A-Za-z0-9])$/'
        ],
        'CH' => [
            'bban' => '/^([0-9]{5})([A-Za-z0-9]{12})$/',
            'iban' => '/^(CH)([0-9]{2})([0-9]{5})([A-Za-z0-9]{12})$/'
        ],
        'CR' => [
            'bban' => '/^([0-9]{3})([0-9]{14})$/',
            'iban' => '/^(CR)([0-9]{2})([0-9]{3})([0-9]{14})$/'
        ],
        'CY' => [
            'bban' => '/^([0-9]{3})([0-9]{5})([A-Za-z0-9]{16})$/',
            'iban' => '/^(CY)([0-9]{2})([0-9]{3})([0-9]{5})([A-Za-z0-9]{16})$/'
        ],
        'CZ' => [
            'bban' => '/^([0-9]{4})([0-9]{6})([0-9]{10})$/',
            'iban' => '/^(CZ)([0-9]{2})([0-9]{4})([0-9]{6})([0-9]{10})$/'
        ],
        'DE' => [
            'bban' => '/^([0-9]{8})([0-9]{10})$/',
            'iban' => '/^(DE)([0-9]{2})([0-9]{8})([0-9]{10})$/'
        ],
        'DK' => [
            'bban' => '/^([0-9]{4})([0-9]{9})([0-9])$/',
            'iban' => '/^(DK|FO|GL)([0-9]{2})([0-9]{4})([0-9]{9})([0-9])$/'
        ],
        'DO' => [
            'bban' => '/^([A-Za-z0-9]{4})([0-9]{20})$/',
            'iban' => '/^(DO)([0-9]{2})([A-Za-z0-9]{4})([0-9]{20})$/'
        ],
        'EE' => [
            'bban' => '/^([0-9]{2})([0-9]{2})([0-9]{11})([0-9])$/',
            'iban' => '/^(EE)([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{11})([0-9])$/'
        ],
        'ES' => [
            'bban' => '/^([0-9]{4})([0-9]{4})([0-9])([0-9])([0-9]{10})$/',
            'iban' => '/^(ES)([0-9]{2})([0-9]{4})([0-9]{4})([0-9])([0-9])([0-9]{10})$/'
        ],
        'FI' => [
            'bban' => '',
            'iban' => '/^(FI|AX)([0-9]{2})([0-9]{6})([0-9]{7})([0-9])$/'
        ],
        'FR' => [
            'bban' => '/^([0-9]{5})([0-9]{5})([A-Za-z0-9]{11})([0-9]{2})$/',
            'iban' => '/^(FR|GF|GP|MQ|RE|PF|TF|YT|NC|BL|MF|PM|WF|GP|RE|MQ|GF|PM|YT)([0-9]{2})([0-9]{5})([0-9]{5})([A-Za-z0-9]{11})([0-9]{2})$/'
        ],
        'GB' => [
            'bban' => '/^([A-Z]{4})([0-9]{6})([0-9]{8})$/',
            'iban' => '/^(GB)([0-9]{2})([A-Z]{4})([0-9]{6})([0-9]{8})$/'
        ],
        'GE' => [
            'bban' => '/^([A-Z]{2})([0-9]{16})$/',
            'iban' => '/^(GE)([0-9]{2})([A-Z]{2})([0-9]{16})$/'
        ],
        'GI' => [
            'bban' => '/^([A-Z]{4})([A-Za-z0-9]{15})$/',
            'iban' => '/^(GI)([0-9]{2})([A-Z]{4})([A-Za-z0-9]{15})$/'
        ],
        'GR' => [
            'bban' => '/^([0-9]{3})([0-9]{4})([A-Za-z0-9]{16})$/',
            'iban' => '/^(GR)([0-9]{2})([0-9]{3})([0-9]{4})([A-Za-z0-9]{16})$/'
        ],
        'GT' => [
            'bban' => '/^([A-Za-z0-9]{4})([A-Za-z0-9]{20})$/',
            'iban' => '/^(GT)([0-9]{2})([A-Za-z0-9]{4})([A-Za-z0-9]{20})$/'
        ],
        'HR' => [
            'bban' => '/^([0-9]{7})([0-9]{10})$/',
            'iban' => '/^(HR)([0-9]{2})([0-9]{7})([0-9]{10})$/'
        ],
        'HU' => [
            'bban' => '/^([0-9]{3})([0-9]{4})([0-9])([0-9]{15})([0-9]):$/',
            'iban' => '/^(HU)([0-9]{2})([0-9]{3})([0-9]{4})([0-9])([0-9]{15})([0-9])$/'
        ],
        'IE' => [
            'bban' => '/^([A-Z]{4})([0-9]{6})([0-9]{8})$/',
            'iban' => '/^(IE)([0-9]{2})([A-Z]{4})([0-9]{6})([0-9]{8})$/'
        ],
        'IL' => [
            'bban' => '/^([0-9]{3})([0-9]{3})([0-9]{13})$/',
            'iban' => '/^(IL)([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{13})$/'
        ],
        'IS' => [
            'bban' => '/^([0-9]{4})([0-9]{2})([0-9]{6})([0-9]{10})$/',
            'iban' => '/^(IS)([0-9]{2})([0-9]{4})([0-9]{2})([0-9]{6})([0-9]{10})$/'
        ],
        'IT' => [
            'bban' => '/^([A-Z])([0-9]{5})([0-9]{5})([A-Za-z0-9]{12})$/',
            'iban' => '/^(IT)([0-9]{2})([A-Z])([0-9]{5})([0-9]{5})([A-Za-z0-9]{12})$/'
        ],
        'KW' => [
            'bban' => '/^([A-Z]{4})([A-Za-z0-9]{22})$/',
            'iban' => '/^(KW)([0-9]{2})([A-Z]{4})([A-Za-z0-9]{22})$/'
        ],
        'KZ' => [
            'bban' => '/^([0-9]{3})([A-Za-z0-9]{13})$/',
            'iban' => '/^(KZ)([0-9]{2})([0-9]{3})([A-Za-z0-9]{13})$/'
        ],
        'LB' => [
            'bban' => '/^([0-9]{4})([A-Za-z0-9]{20})$/',
            'iban' => '/^(LB)([0-9]{2})([0-9]{4})([A-Za-z0-9]{20})$/'
        ],
        'LI' => [
            'bban' => '/^([0-9]{5})([A-Za-z0-9]{12})$/',
            'iban' => '/^(LI)([0-9]{2})([0-9]{5})([A-Za-z0-9]{12})$/'
        ],
        'LT' => [
            'bban' => '/^([0-9]{5})([0-9]{11})$/',
            'iban' => '/^(LT)([0-9]{2})([0-9]{5})([0-9]{11})$/'
        ],
        'LU' => [
            'bban' => '/^([0-9]{3})([A-Za-z0-9]{13})$/',
            'iban' => '/^(LU)([0-9]{2})([0-9]{3})([A-Za-z0-9]{13})$/'
        ],
        'LV' => [
            'bban' => '/^([A-Z]{4})([A-Za-z0-9]{13})$/',
            'iban' => '/^(LV)([0-9]{2})([A-Z]{4})([A-Za-z0-9]{13})$/'
        ],
        'MC' => [
            'bban' => '/^([0-9]{5})([0-9]{5})([A-Za-z0-9]{11})([0-9]{2})$/',
            'iban' => '/^(MC)([0-9]{2})([0-9]{5})([0-9]{5})([A-Za-z0-9]{11})([0-9]{2})$/'
        ],
        'MD' => [
            'bban' => '/^([A-Za-z0-9]{2})([A-Za-z0-9]{18})$/',
            'iban' => '/^(MD)([0-9]{2})([A-Za-z0-9]{20})$/'
        ],
        'ME' => [
            'bban' => '/^([0-9]{3})([0-9]{13})([0-9]{2})$/',
            'iban' => '/^(ME)([0-9]{2})([0-9]{3})([0-9]{13})([0-9]{2})$/'
        ],
        'MK' => [
            'bban' => '/^([0-9]{3})([A-Za-z0-9]{10})([0-9]{2})$/',
            'iban' => '/^(MK)([0-9]{2})([0-9]{3})([A-Za-z0-9]{10})([0-9]{2})$/'
        ],
        'MR' => [
            'bban' => '/^([0-9]{5})([0-9]{5})([0-9]{11})([0-9]{2})$/',
            'iban' => '/^(MR)13([0-9]{5})([0-9]{5})([0-9]{11})([0-9]{2})$/'
        ],
        'MT' => [
            'bban' => '/^([A-Z]{4})([0-9]{5})([A-Za-z0-9]{18})$/',
            'iban' => '/^(MT)([0-9]{2})([A-Z]{4})([0-9]{5})([A-Za-z0-9]{18})$/'
        ],
        'MU' => [
            'bban' => '/^([A-Z]{4})([0-9]{2})([0-9]{2})([0-9]{12})([0-9]{3})([A-Z]{3})$/',
            'iban' => '/^(MU)([0-9]{2})([A-Z]{4})([0-9]{2})([0-9]{2})([0-9]{12})([0-9]{3})([A-Z]{3})$/'
        ],
        'NL' => [
            'bban' => '/^([A-Z]{4})([0-9]{10})$/',
            'iban' => '/^(NL)([0-9]{2})([A-Z]{4})([0-9]{10})$/'
        ],
        'NO' => [
            'bban' => '/^([0-9]{4})([0-9]{6})([0-9])$/',
            'iban' => '/^(NO)([0-9]{2})([0-9]{4})([0-9]{6})([0-9])$/'
        ],
        'PK' => [
            'bban' => '/^([A-Z]{4})([A-Za-z0-9]{16})$/',
            'iban' => '/^(PK)([0-9]{2})([A-Z]{4})([A-Za-z0-9]{16})$/'
        ],
        'PL' => [
            'bban' => '/^([0-9]{8})([0-9]{16})$/',
            'iban' => '/^(PL)([0-9]{2})([0-9]{8})([0-9]{1,16})$/'
        ],
        'PS' => [
            'bban' => '/^([A-Z]{4})([A-Za-z0-9]{21})$/',
            'iban' => '/^(PS)([0-9]{2})([A-Z]{4})([A-Za-z0-9]{21})$/'
        ],
        'PT' => [
            'bban' => '/^([0-9]{4})([0-9]{4})([0-9]{11})([0-9]{2})$/',
            'iban' => '/^(PT)([0-9]{2})([0-9]{4})([0-9]{4})([0-9]{11})([0-9]{2})$/'
        ],
        'RO' => [
            'bban' => '/^([A-Z]{4})([A-Za-z0-9]{16})$/',
            'iban' => '/^(RO)([0-9]{2})([A-Z]{4})([A-Za-z0-9]{16})$/'
        ],
        'RS' => [
            'bban' => '/^([0-9]{3})([0-9]{13})([0-9]{2})$/',
            'iban' => '/^(RS)([0-9]{2})([0-9]{3})([0-9]{13})([0-9]{2})$/'
        ],
        'SA' => [
            'bban' => '/^([0-9]{2})([A-Za-z0-9]{18})$/',
            'iban' => '/^(SA)([0-9]{2})([0-9]{2})([A-Za-z0-9]{18})$/'
        ],
        'SE' => [
            'bban' => '/^([0-9]{3})([0-9]{16})([0-9])$/',
            'iban' => '/^(SE)([0-9]{2})([0-9]{3})([0-9]{16})([0-9])$/'
        ],
        'SI' => [
            'bban' => '/^([0-9]{5})([0-9]{8})([0-9]{2})$/',
            'iban' => '/^(SI)([0-9]{2})([0-9]{5})([0-9]{8})([0-9]{2})$/'
        ],
        'SK' => [
            'bban' => '/^([0-9]{4})([0-9]{6})([0-9]{10})$/',
            'iban' => '/^(SK)([0-9]{2})([0-9]{4})([0-9]{6})([0-9]{10})$/'
        ],
        'SM' => [
            'bban' => '/^([A-Z])([0-9]{5})([0-9]{5})([A-Za-z0-9]{12})$/',
            'iban' => '/^(SM)([0-9]{2})([A-Z])([0-9]{5})([0-9]{5})([A-Za-z0-9]{12})$/'
        ],
        'TN' => [
            'bban' => '/^([0-9]{2})([0-9]{3})([0-9]{13})([0-9]{2})$/',
            'iban' => '/^(TN)59([0-9]{2})([0-9]{3})([0-9]{13})([0-9]{2})$/'
        ],
        'TR' => [
            'bban' => '/^([0-9]{5})([A-Za-z0-9])([A-Za-z0-9]{16})$/',
            'iban' => '/^(TR)([0-9]{2})([0-9]{5})([A-Za-z0-9])([A-Za-z0-9]{16})$/'
        ],
        'VG' => [
            'bban' => '/^([A-Z]{4})([0-9]{16})$/',
            'iban' => '/^(VG)([0-9]{2})([A-Z]{4})([0-9]{16})$/'
        ]
    ];

    /**
     * ISO 3611 Country Code.
     *
     * @var string
     */
    protected $country;

    /**
     * Allow BBAN.
     *
     * @var bool
     */
    protected $allowBban = false;

    /**
     * Constructor to prevent {@link Iban} from being loaded more than once.
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

        if (array_key_exists('allow_bban', $options)) {
            $this->allowBban($options['allow_bban']);
        }

        parent::__construct($options);
    }

    /**
     * Allow Bban.
     *
     * @param  bool $allow
     *
     * @return bool|static
     */
    public function allowBban($allow = null)
    {
        if (null !== $allow) {
            $this->allowBban = (bool) $allow;

            return $this;
        }

        return $this->allowBban;
    }

    /**
     * Get Country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set Country.
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

        $country = $this->getCountry();

        if (!array_key_exists($country, $this->iban)) {
            if (array_key_exists($country, $context)) {
                $country = $context[$country];
            } else {
                $country = substr($value, 0, 2);
            }
        }

        if (!array_key_exists($country, $this->iban)) {
            $this->error(self::UNSUPPORTED);

            return false;
        }

        if (!preg_match($this->iban[$country]['iban'], $value)) {
            if ($this->allowBban && $this->iban[$country]['bban']) {
                return true;
            }

            $this->error(self::NO_MATCH);

            return false;
        }

        return true;
    }
}
