<?php
namespace YOCLIB\DNS\Tests\Types;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\WindowBlockBitmap;
use YOCLIB\DNS\Types\NSEC;

class NSECTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(NSEC::class,new NSEC([
            new FQDN('host','example','com',''),
            new WindowBlockBitmap([DNSType::A,DNSType::MX,DNSType::RRSIG,DNSType::NSEC,1234]),
        ]));
    }

    /**
     * @return void
     * @throws DNSTypeException
     */
    public function testConstructorTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Only two fields allowed.');

        new NSEC([
            new FQDN('host','example','com',''),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidFirstField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('First field should be a FQDN.');

        new NSEC([
            new IPv4Address('127.0.0.1'),
            new WindowBlockBitmap([DNSType::A,DNSType::MX,DNSType::RRSIG,DNSType::NSEC,1234]),
        ]);
    }

    /**
     * @return void
     * @throws DNSTypeException
     */
    public function testConstructorInvalidSecondField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Second field should be a window block bitmap.');

        new NSEC([
            new FQDN('host','example','com',''),
            new Binary(''),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('host.example.com.',(new NSEC([
            new FQDN('host','example','com',''),
            new WindowBlockBitmap([]),
        ]))->serializeToPresentationFormat());

        self::assertSame('host.example.com. A MX RRSIG NSEC TYPE1234',(new NSEC([
            new FQDN('host','example','com',''),
            new WindowBlockBitmap([DNSType::A,DNSType::MX,DNSType::RRSIG,DNSType::NSEC,1234]),
        ]))->serializeToPresentationFormat());

        self::assertSame('host.example.com. TYPE1234',(new NSEC([
            new FQDN('host','example','com',''),
            new WindowBlockBitmap([1234]),
        ]))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame([],NSEC::deserializeFromPresentationFormat('host.example.com.')->getFields()[1]->getValue());

        self::assertSame([DNSType::A,DNSType::MX,DNSType::RRSIG,DNSType::NSEC,1234],NSEC::deserializeFromPresentationFormat('host.example.com. A MX RRSIG NSEC TYPE1234')->getFields()[1]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('NSEC record should contain at least 1 field.');

        NSEC::deserializeFromPresentationFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownBitmapMnemonic(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        NSEC::deserializeFromPresentationFormat('host.example.com. NON-EXISTING');
    }

}