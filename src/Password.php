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

use Zend\Stdlib\PriorityQueue;
use Zend\Validator\Regex as RegexValidator;
use Zend\Validator\StringLength as StringLengthValidator;
use Zend\Validator\ValidatorChain;

/**
 * A {@link Password} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class Password extends ValidatorChain
{
    /**
     *
     *
     * @var string
     */
    const UPPER_NOT_CONTAIN = 'upperNotContain';

    /**
     *
     *
     * @var string
     */
    const LOWER_NOT_CONTAIN = 'lowerNotContain';

    /**
     *
     *
     * @var string
     */
    const DIGIT_NOT_CONTAIN = 'digitNotContain';

    /**
     *
     *
     * @var string
     */
    const SYMBOL_NOT_CONTAIN = 'symbolNotContain';

    /**
     *
     *
     * @return string
     */
    const NOT_ALLOWED = 'notAllowed';

    /**
     * Error Message Templates
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_ALLOWED                => 'The password provided contains words that are not allowed',
        self::UPPER_NOT_CONTAIN          => 'Password must include at least one uppercase letter!',
        self::LOWER_NOT_CONTAIN          => 'Password must include at least one lowercase letter!',
        self::DIGIT_NOT_CONTAIN          => 'Password must include at least one number!',
        self::SYMBOL_NOT_CONTAIN         => 'Password must include at least one special character!',
        StringLengthValidator::TOO_SHORT => 'Password need to have at least %min% characters!',
        StringLengthValidator::TOO_LONG  => 'Password need to have at less than %max% characters!'
    ];

    /**
     *
     *
     * @return int
     */
    protected $minimalLength = 8;

    /**
     *
     *
     * @return int
     */
    protected $maximumLength = 255;

    /**
     *
     *
     * @return boolean
     */
    protected $enableLowercase = true;

    /**
     *
     *
     * @return boolean
     */
    protected $enableUppercase = true;

    /**
     *
     *
     * @return boolean
     */
    protected $enableDigit = true;

    /**
     *
     *
     * @return boolean
     */
    protected $enableSymbol = true;

    /**
     *
     *
     * @return array
     */
    protected $blacklists = [];

    /**
     * Constructor to prevent {@link Password} from being loaded more than once.
     *
     * @param array|\Traversable $options
     *
     * @throws \Xloit\Bridge\Zend\Validator\Exception\InvalidArgumentException
     */
    public function __construct($options = [])
    {
        if (isset($options['blacklists'])) {
            $this->setBlacklists($options['blacklists']);
        }

        if (isset($options['enableLowercase'])) {
            $this->setEnableLowercase((bool) $options['enableLowercase']);
        } elseif (isset($options['lowercase'])) {
            $this->setEnableLowercase((bool) $options['lowercase']);
        }

        if (isset($options['enableUppercase'])) {
            $this->setEnableUppercase((bool) $options['enableUppercase']);
        } elseif (isset($options['uppercase'])) {
            $this->setEnableUppercase((bool) $options['uppercase']);
        }

        if (isset($options['enableDigit'])) {
            $this->setEnableDigit((bool) $options['enableDigit']);
        } elseif (isset($options['digit'])) {
            $this->setEnableDigit((bool) $options['digit']);
        }

        if (isset($options['enableSymbol'])) {
            $this->setEnableSymbol((bool) $options['enableSymbol']);
        } elseif (isset($options['symbol'])) {
            $this->setEnableSymbol((bool) $options['symbol']);
        }

        if (isset($options['minimalLength'])) {
            $this->setMinimalLength($options['minimalLength']);
        } elseif (isset($options['min'])) {
            $this->setMinimalLength($options['min']);
        }

        if (isset($options['maximumLength'])) {
            $this->setMaximumLength($options['maximumLength']);
        } elseif (isset($options['max'])) {
            $this->setMaximumLength($options['max']);
        }

        parent::__construct();
    }

    /**
     * Returns the value of blacklists.
     *
     * @return array
     */
    public function getBlacklists()
    {
        return $this->blacklists;
    }

    /**
     * Set the value of blacklists.
     *
     * @param array $blacklists
     *
     * @return static
     */
    public function setBlacklists($blacklists)
    {
        $this->blacklists = $blacklists;

        return $this;
    }

    /**
     * Returns the MaximumLength value.
     *
     * @return mixed
     */
    public function getMaximumLength()
    {
        return $this->maximumLength;
    }

    /**
     * Sets the MaximumLength value.
     *
     * @param mixed $maximumLength
     *
     * @return self
     * @throws Exception\InvalidArgumentException
     */
    public function setMaximumLength($maximumLength)
    {
        if ($maximumLength <= $this->minimalLength) {
            throw new Exception\InvalidArgumentException('Maximum length must be larger than minimal length.');
        }

        $this->maximumLength = $maximumLength;

        return $this;
    }

    /**
     * Returns the MinimalLength value.
     *
     * @return mixed
     */
    public function getMinimalLength()
    {
        return $this->minimalLength;
    }

    /**
     * Sets the MinimalLength value.
     *
     * @param mixed $minimalLength
     *
     * @return self
     * @throws Exception\InvalidArgumentException
     */
    public function setMinimalLength($minimalLength)
    {
        if ($minimalLength < 1) {
            throw new Exception\InvalidArgumentException('Minimal length must be larger than 0.');
        }

        $this->minimalLength = $minimalLength;

        return $this;
    }

    /**
     * Returns the EnableDigit value
     *
     * @return mixed
     */
    public function isEnableDigit()
    {
        return $this->enableDigit;
    }

    /**
     * Sets the EnableDigit value
     *
     * @param mixed $enableDigit
     *
     * @return static
     */
    public function setEnableDigit($enableDigit)
    {
        $this->enableDigit = $enableDigit;

        return $this;
    }

    /**
     * Returns the EnableLowercase value
     *
     * @return mixed
     */
    public function isEnableLowercase()
    {
        return $this->enableLowercase;
    }

    /**
     * Sets the EnableLowercase value
     *
     * @param mixed $enableLowercase
     *
     * @return static
     */
    public function setEnableLowercase($enableLowercase)
    {
        $this->enableLowercase = $enableLowercase;

        return $this;
    }

    /**
     * Returns the EnableUppercase value
     *
     * @return mixed
     */
    public function isEnableUppercase()
    {
        return $this->enableUppercase;
    }

    /**
     * Sets the EnableUppercase value
     *
     * @param mixed $enableUppercase
     *
     * @return static
     */
    public function setEnableUppercase($enableUppercase)
    {
        $this->enableUppercase = $enableUppercase;

        return $this;
    }

    /**
     *
     *
     * @return mixed
     */
    public function isEnableSymbol()
    {
        return $this->enableSymbol;
    }

    /**
     *
     *
     * @param mixed $enableSymbol
     *
     * @return static
     */
    public function setEnableSymbol($enableSymbol)
    {
        $this->enableSymbol = $enableSymbol;

        return $this;
    }

    /**
     * Returns true if and only if $value meets the validation requirements.
     * If $value fails validation, then this method returns false, and getMessages() will return an array of messages
     * that explain why the validation failed.
     *
     * @param  mixed $value
     * @param  mixed $context
     *
     * @throws \Zend\Validator\Exception\InvalidArgumentException
     * @throws \Zend\Validator\Exception\RuntimeException
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if (count($this->blacklists) > 0) {
            $validator = new NotInArray(
                [
                    'haystack'         => $this->blacklists,
                    'messageTemplates' => [
                        NotInArray::IN_ARRAY => $this->messageTemplates[self::NOT_ALLOWED]
                    ]
                ]
            );

            if (!$validator->isValid(strtolower($value))) {
                $messages       = $validator->getMessages();
                $this->messages = array_replace_recursive($this->messages, $messages);

                return false;
            }
        }

        $this->validators = new PriorityQueue();

        /**
         * Explaining $\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
         *
         * $ = beginning of string
         * \S* = any set of characters
         * (?=\S{8,}) = of at least length 8
         * (?=\S*[a-z]) = containing at least one lowercase letter
         * (?=\S*[A-Z]) = and at least one uppercase letter
         * (?=\S*[\d]) = and at least one number
         * (?=\S*[`~!@#$%^&*\(\)_\-=+\[\]{\}\\|:;'",<.>/?]) = and at least a special character (non-word characters)
         * $ = end of the string
         */
        $this->attach(
            new StringLengthValidator(
                [
                    'min'              => $this->minimalLength,
                    'max'              => $this->maximumLength,
                    'messageTemplates' => [
                        StringLengthValidator::TOO_SHORT => $this->messageTemplates[StringLengthValidator::TOO_SHORT],
                        StringLengthValidator::TOO_LONG  => $this->messageTemplates[StringLengthValidator::TOO_LONG]
                    ]
                ]
            ),
            true
        );

        if ($this->isEnableLowercase()) {
            $this->attach(
                new RegexValidator(
                    [
                        'pattern'          => '$\S*(?=\S*[a-z])\S*$',
                        'messageTemplates' => [
                            RegexValidator::NOT_MATCH => $this->messageTemplates[self::LOWER_NOT_CONTAIN]
                        ]
                    ]
                ),
                true
            );
        }

        if ($this->isEnableUppercase()) {
            $this->attach(
                new RegexValidator(
                    [
                        'pattern'          => '$\S*(?=\S*[A-Z])\S*$',
                        'messageTemplates' => [
                            RegexValidator::NOT_MATCH => $this->messageTemplates[self::UPPER_NOT_CONTAIN]
                        ]
                    ]
                ),
                true
            );
        }

        if ($this->isEnableDigit()) {
            $this->attach(
                new RegexValidator(
                    [
                        'pattern'          => '$\S*(?=\S*[0-9])\S*$',
                        'messageTemplates' => [
                            RegexValidator::NOT_MATCH => $this->messageTemplates[self::DIGIT_NOT_CONTAIN]
                        ]
                    ]
                ),
                true
            );
        }

        if ($this->isEnableSymbol()) {
            $this->attach(
                new RegexValidator(
                    [
                        'pattern'          => '$\S*(?=\S*[`~!@#\\$%^&*\(\)_\-=+\[\]{\}\\|:;\'",<.>/?])\S*$',
                        'messageTemplates' => [
                            RegexValidator::NOT_MATCH => $this->messageTemplates[self::SYMBOL_NOT_CONTAIN]
                        ]
                    ]
                ),
                true
            );
        }

        return parent::isValid($value, $context);
    }
}
