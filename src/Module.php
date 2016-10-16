<?php
/**
 * This source file is part of Virtupeer project.
 *
 * @link      https://virtupeer.com
 * @copyright Copyright (c) 2016, Virtupeer. All rights reserved.
 */

namespace Xloit\Bridge\Zend\Validator;

use Zend\ServiceManager\Factory\InvokableFactory;

/**
 * A {@link Module} class.
 *
 * @package Xloit\Bridge\Zend\Validator
 */
class Module
{
    /**
     * Return default zend-validator configuration for zend-mvc applications.
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'validators' => [
                'aliases'   => [
                    'consonant'            => Consonant::class,
                    'Consonant'            => Consonant::class,
                    'ein'                  => Ein::class,
                    'Ein'                  => Ein::class,
                    'dateage'              => DateAge::class,
                    'dateAge'              => DateAge::class,
                    'DateAge'              => DateAge::class,
                    'foldername'           => FolderName::class,
                    'folderName'           => FolderName::class,
                    'FolderName'           => FolderName::class,
                    'iataairlinecode'      => IataAirlineCode::class,
                    'iataAirlineCode'      => IataAirlineCode::class,
                    'IataAirlineCode'      => IataAirlineCode::class,
                    'iban'                 => Iban::class,
                    'Iban'                 => Iban::class,
                    'integer'              => Integer::class,
                    'Integer'              => Integer::class,
                    'luhn'                 => Luhn::class,
                    'Luhn'                 => Luhn::class,
                    'nowhitespace'         => NoWhitespace::class,
                    'noWhitespace'         => NoWhitespace::class,
                    'NoWhitespace'         => NoWhitespace::class,
                    'number'               => Number::class,
                    'Number'               => Number::class,
                    'password'             => Password::class,
                    'Password'             => Password::class,
                    'postcode'             => PostCode::class,
                    'postCode'             => PostCode::class,
                    'PostCode'             => PostCode::class,
                    'routingtransitnumber' => RoutingTransitNumber::class,
                    'routingTransitNumber' => RoutingTransitNumber::class,
                    'RoutingTransitNumber' => RoutingTransitNumber::class,
                    'ssn'                  => Ssn::class,
                    'Ssn'                  => Ssn::class,
                    'swift'                => Swift::class,
                    'Swift'                => Swift::class,
                    'username'             => Username::class,
                    'Username'             => Username::class,
                    'vatiN'                => VatIN::class,
                    'vatIN'                => VatIN::class,
                    'VatIN'                => VatIN::class,
                    'filecrc32'            => File\Crc32::class,
                    'fileCrc32'            => File\Crc32::class,
                    'FileCrc32'            => File\Crc32::class,
                    'filename'             => File\FileName::class,
                    'fileName'             => File\FileName::class,
                    'FileName'             => File\FileName::class,
                    'fileimagesize'        => File\ImageSize::class,
                    'fileImageSize'        => File\ImageSize::class,
                    'FileImageSize'        => File\ImageSize::class,
                    'fileiscompressed'     => File\IsCompressed::class,
                    'fileIsCompressed'     => File\IsCompressed::class,
                    'FileIsCompressed'     => File\IsCompressed::class,
                    'fileisimage'          => File\IsImage::class,
                    'fileIsImage'          => File\IsImage::class,
                    'FileIsImage'          => File\IsImage::class,
                    'filemd5'              => File\Md5::class,
                    'fileMd5'              => File\Md5::class,
                    'FileMd5'              => File\Md5::class,
                    'filemimetype'         => File\MimeType::class,
                    'fileMimeType'         => File\MimeType::class,
                    'FileMimeType'         => File\MimeType::class,
                    'filesha1'             => File\Sha1::class,
                    'fileSha1'             => File\Sha1::class,
                    'FileSha1'             => File\Sha1::class,
                    'filesize'             => File\Size::class,
                    'fileSize'             => File\Size::class,
                    'FileSize'             => File\Size::class,
                    'filewordcount'        => File\WordCount::class,
                    'fileWordCount'        => File\WordCount::class,
                    'FileWordCount'        => File\WordCount::class
                ],
                'factories' => [
                    Consonant::class            => InvokableFactory::class,
                    DateAge::class              => InvokableFactory::class,
                    Ein::class                  => InvokableFactory::class,
                    FolderName::class           => InvokableFactory::class,
                    IataAirlineCode::class      => InvokableFactory::class,
                    Iban::class                 => InvokableFactory::class,
                    Integer::class              => InvokableFactory::class,
                    Luhn::class                 => InvokableFactory::class,
                    NoWhitespace::class         => InvokableFactory::class,
                    Password::class             => InvokableFactory::class,
                    RoutingTransitNumber::class => InvokableFactory::class,
                    Ssn::class                  => InvokableFactory::class,
                    Swift::class                => InvokableFactory::class,
                    Username::class             => InvokableFactory::class,
                    VatIN::class                => InvokableFactory::class,
                    File\Crc32::class           => InvokableFactory::class,
                    File\FileName::class        => InvokableFactory::class,
                    File\ImageSize::class       => InvokableFactory::class,
                    File\IsCompressed::class    => InvokableFactory::class,
                    File\IsImage::class         => InvokableFactory::class,
                    File\Md5::class             => InvokableFactory::class,
                    File\MimeType::class        => InvokableFactory::class,
                    File\Sha1::class            => InvokableFactory::class,
                    File\Size::class            => InvokableFactory::class,
                    File\WordCount::class       => InvokableFactory::class
                ]
            ]
        ];
    }
}
