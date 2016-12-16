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

use Zend\I18n\Validator\Alnum;
use Zend\Stdlib\PriorityQueue;
use Zend\Validator\Regex as RegexValidator;
use Zend\Validator\StringLength as StringLengthValidator;
use Zend\Validator\ValidatorChain;

/**
 * An {@link Username} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class Username extends ValidatorChain
{
    /**
     *
     *
     * @return string
     */
    const INVALID = 'usernameNotString';

    /**
     *
     *
     * @return string
     */
    const NOT_ALLOWED = 'usernameNotAllowed';

    /**
     *
     *
     * @return string
     */
    const ALPHA = 'a-z';

    /**
     *
     *
     * @return string
     */
    const DIGIT = '0-9';

    /**
     *
     *
     * @return string
     */
    const SYMBOL = '_\-\.~';

    /**
     *
     *
     * @return string
     */
    const SYMBOL_RESERVED = '!#\$&\'\*\+=\?';

    /**
     *
     *
     * @var string
     */
    const ERROROUS = 'error';

    /**
     * Error Message Templates.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::ERROROUS                   => 'There was an internal error while validating your username'
        self::INVALID                    => 'Invalid value. String expected',
        self::NOT_ALLOWED                => 'The username provided contains words that are not allowed',
        Alnum::INVALID                   => 'Invalid username. Username must be alpha numeric!',
        RegexValidator::NOT_MATCH        => 'The username provided contains characters that are not allowed',
        StringLengthValidator::TOO_SHORT => 'Username need to have at least %min% characters!',
        StringLengthValidator::TOO_LONG  => 'Username need to have at less than %max% characters!'
    ];

    /**
     * Message variables.
     *
     * @var array
     */
    protected $messageVariables = [
        'min'      => 'minimalLength',
        'max'      => 'maximumLength',
        'encoding' => 'encoding'
    ];

    /**
     *
     *
     * @return int
     */
    protected $minimalLength = 3;

    /**
     *
     *
     * @return int
     */
    protected $maximumLength = 64;

    /**
     *
     *
     * @return string
     */
    protected $encoding = 'UTF-8';

    /**
     *
     *
     * @return string
     */
    protected $pattern = '/^([a-z0-9])([a-zA-Z0-9_\-\.~]+)$/';

    /**
     *
     *
     * @return array
     */
    protected $blacklists = [];

    /**
     * Constructor to prevent {@link Username} from being loaded more than once.
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

        if (isset($options['pattern'])) {
            $this->setPattern($options['pattern']);
        }

        if (isset($options['encoding'])) {
            $this->setEncoding($options['encoding']);
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
     * Returns the value of pattern.
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Set the value of pattern.
     *
     * @param string $pattern
     *
     * @return static
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * Returns the value of encoding option.
     *
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Set string encoding. Hard coded to only accept ascii encoding.
     *
     * @param string $encoding
     *
     * @return static
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;

        return $this;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getMinimalLength()
    {
        return $this->minimalLength;
    }

    /**
     *
     *
     * @param mixed $minimalLength
     *
     * @return static
     * @throws Exception\InvalidArgumentException
     */
    public function setMinimalLength($minimalLength)
    {
        if (!is_numeric($minimalLength)) {
            throw new Exception\InvalidArgumentException('Minimal length should be a number');
        }

        $maximumLength = $this->getMaximumLength();

        if ($minimalLength > $maximumLength && null !== $maximumLength) {
            throw new Exception\InvalidArgumentException('Minimal length cannot be greater than maximum length');
        }

        $this->minimalLength = (int) $minimalLength;

        return $this;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getMaximumLength()
    {
        return $this->maximumLength;
    }

    /**
     *
     *
     * @param mixed $maximumLength
     *
     * @return static
     * @throws Exception\InvalidArgumentException
     */
    public function setMaximumLength($maximumLength)
    {
        if (!is_numeric($maximumLength)) {
            $this->maximumLength = null;

            return $this;
        }

        if ($maximumLength < $this->minimalLength) {
            throw new Exception\InvalidArgumentException('Maximum length must be larger than minimal length.');
        }

        $this->maximumLength = (int) $maximumLength;

        return $this;
    }

    /**
     * Returns true if and only if $value meets the validation requirements.
     * If $value fails validation, then this method returns false, and getMessages() will return an array of
     * messages that explain why the validation failed.
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
        $value = mb_convert_encoding($value, $this->encoding);

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

        $this->attach(
            new StringLengthValidator(
                [
                    'min'              => $this->minimalLength,
                    'max'              => $this->maximumLength,
                    'encoding'         => $this->encoding,
                    'messageTemplates' => [
                        self::INVALID                    => $this->messageTemplates[self::INVALID],
                        StringLengthValidator::TOO_SHORT => $this->messageTemplates[StringLengthValidator::TOO_SHORT],
                        StringLengthValidator::TOO_LONG  => $this->messageTemplates[StringLengthValidator::TOO_LONG]
                    ]
                ]
            ),
            true
        );

        if ($this->pattern) {
            $this->attach(
                new RegexValidator(
                    [
                        'pattern'          => $this->pattern,
                        'messageTemplates' => [
                            RegexValidator::INVALID   => $this->messageTemplates[self::INVALID],
                            RegexValidator::NOT_MATCH => $this->messageTemplates[RegexValidator::NOT_MATCH],
                            RegexValidator::ERROROUS => $this->messageTemplates[self::ERROROUS]
                        ]
                    ]
                )
            );
        } else {
            $this->attach(
                new Alnum(
                    [
                        'messageTemplates' => [
                            Alnum::INVALID => $this->messageTemplates[Alnum::INVALID]
                        ],
                        'allowWhiteSpace'  => false
                    ]
                )
            );
        }

        return parent::isValid($value, $context);
    }
}
