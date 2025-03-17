<?php
namespace YOCLIB\DNS\Tests\Types;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Types\NXT;

class NXTTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(NXT::class,new NXT([
            new FQDN('medium','foo','nil',''),
            new Bitmap([DNSType::A,DNSType::MX,DNSType::SIG,DNSType::NXT]),
        ]));
    }

    /**
     * @return void
     * @throws DNSTypeException
     */
    public function testConstructorTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Only two fields allowed.');

        new NXT([
            new FQDN('medium','foo','nil',''),
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

        new NXT([
            new IPv4Address('127.0.0.1'),
            new Bitmap([DNSType::A,DNSType::MX,DNSType::SIG,DNSType::NXT]),
        ]);
    }

    /**
     * @return void
     * @throws DNSTypeException
     */
    public function testConstructorInvalidSecondField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Second field should be a bitmap.');

        new NXT([
            new FQDN('medium','foo','nil',''),
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
        self::assertSame('medium.foo.nil.',(new NXT([
            new FQDN('medium','foo','nil',''),
            new Bitmap([]),
        ]))->serializeToPresentationFormat());

//        self::assertSame('medium.foo.nil. 1 15 24 30',(new NXT([
//            new FQDN('medium','foo','nil',''),
//            new Bitmap([DNSType::A,DNSType::MX,DNSType::SIG,DNSType::NXT]),
//        ]))->serializeToPresentationFormat());
        self::assertSame('medium.foo.nil. A MX SIG NXT',(new NXT([
            new FQDN('medium','foo','nil',''),
            new Bitmap([DNSType::A,DNSType::MX,DNSType::SIG,DNSType::NXT]),
        ]))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame([],NXT::deserializeFromPresentationFormat('medium.foo.nil.')->getFields()[1]->getValue());

        self::assertSame([DNSType::A,DNSType::MX,DNSType::SIG,DNSType::NXT],NXT::deserializeFromPresentationFormat('medium.foo.nil. 1 15 24 30')->getFields()[1]->getValue());
        self::assertSame([DNSType::A,DNSType::MX,DNSType::SIG,DNSType::NXT],NXT::deserializeFromPresentationFormat('medium.foo.nil. A MX SIG NXT')->getFields()[1]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('NXT record should contain at least 1 field.');

        NXT::deserializeFromPresentationFormat('');
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatUnknownProtocol(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        NXT::deserializeFromPresentationFormat('medium.foo.nil. NON-EXISTING');
    }

}