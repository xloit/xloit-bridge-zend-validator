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
     * Error Message Templates
     *
     * @var array
     */
    protected $messageTemplates = [
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
