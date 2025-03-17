<?php
namespace YOCLIB\DNS\Tests\Types;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\Fields\WindowBlockBitmap;
use YOCLIB\DNS\Types\NSEC3;

class NSEC3Test extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(NSEC3::class,new NSEC3([
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new UnsignedInteger16(12),
            new CharacterString('aabbccdd'),
            new CharacterString('2vptu5timamqttgl4luu9kg21e0aor3s'),
            new WindowBlockBitmap([DNSType::A,DNSType::RRSIG]),
        ]));
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Only two fields allowed.');

        new NSEC3([
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new UnsignedInteger16(12),
            new CharacterString('aabbccdd'),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidFirstField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('First field should be an UInt8.');

        new NSEC3([
            new UnsignedInteger16(1),
            new UnsignedInteger8(1),
            new UnsignedInteger16(12),
            new CharacterString('aabbccdd'),
            new CharacterString('2vptu5timamqttgl4luu9kg21e0aor3s'),
            new WindowBlockBitmap([DNSType::A,DNSType::RRSIG]),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidSecondField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Second field should be an UInt8.');

        new NSEC3([
            new UnsignedInteger8(1),
            new UnsignedInteger16(1),
            new UnsignedInteger16(12),
            new CharacterString('aabbccdd'),
            new CharacterString('2vptu5timamqttgl4luu9kg21e0aor3s'),
            new WindowBlockBitmap([DNSType::A,DNSType::RRSIG]),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidThirdField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Third field should be an UInt16.');

        new NSEC3([
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new UnsignedInteger8(12),
            new CharacterString('aabbccdd'),
            new CharacterString('2vptu5timamqttgl4luu9kg21e0aor3s'),
            new WindowBlockBitmap([DNSType::A,DNSType::RRSIG]),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidFourthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Fourth field should be a character string.');

        new NSEC3([
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new UnsignedInteger16(12),
            new Binary('aabbccdd'),
            new CharacterString('2vptu5timamqttgl4luu9kg21e0aor3s'),
            new WindowBlockBitmap([DNSType::A,DNSType::RRSIG]),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidFifthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Fifth field should be a character string.');

        new NSEC3([
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new UnsignedInteger16(12),
            new CharacterString('aabbccdd'),
            new Binary('2vptu5timamqttgl4luu9kg21e0aor3s'),
            new WindowBlockBitmap([DNSType::A,DNSType::RRSIG]),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testConstructorInvalidSixthField(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Sixth field should be a window block bitmap.');

        new NSEC3([
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new UnsignedInteger16(12),
            new CharacterString('aabbccdd'),
            new CharacterString('2vptu5timamqttgl4luu9kg21e0aor3s'),
            new Bitmap([DNSType::A,DNSType::RRSIG]),
        ]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('1 1 12 aabbccdd 2vptu5timamqttgl4luu9kg21e0aor3s',(new NSEC3([
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new UnsignedInteger16(12),
            new CharacterString('aabbccdd'),
            new CharacterString('2vptu5timamqttgl4luu9kg21e0aor3s'),
            new WindowBlockBitmap([]),
        ]))->serializeToPresentationFormat());

//        self::assertSame('1 1 12 aabbccdd 2vptu5timamqttgl4luu9kg21e0aor3s 1 46',(new NSEC3([
//            new UnsignedInteger8(1),
//            new UnsignedInteger8(1),
//            new UnsignedInteger16(12),
//            new CharacterString('aabbccdd'),
//            new CharacterString('2vptu5timamqttgl4luu9kg21e0aor3s'),
//            new WindowBlockBitmap([DNSType::A,DNSType::RRSIG]),
//        ]))->serializeToPresentationFormat());
        self::assertSame('1 1 12 aabbccdd 2vptu5timamqttgl4luu9kg21e0aor3s A RRSIG',(new NSEC3([
            new UnsignedInteger8(1),
            new UnsignedInteger8(1),
            new UnsignedInteger16(12),
            new CharacterString('aabbccdd'),
            new CharacterString('2vptu5timamqttgl4luu9kg21e0aor3s'),
            new WindowBlockBitmap([DNSType::A,DNSType::RRSIG]),
        ]))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame([],NSEC3::deserializeFromPresentationFormat('1 1 12 aabbccdd 2vptu5timamqttgl4luu9kg21e0aor3s')->getFields()[5]->getValue());

        self::assertSame([DNSType::A,DNSType::RRSIG],NSEC3::deserializeFromPresentationFormat('1 1 12 aabbccdd 2vptu5timamqttgl4luu9kg21e0aor3s A RRSIG')->getFields()[5]->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatTooLessFields(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('NSEC3 record should contain at least 5 fields.');

        NSEC3::deserializeFromPresentationFormat('');
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

        NSEC3::deserializeFromPresentationFormat('1 1 12 aabbccdd 2vptu5timamqttgl4luu9kg21e0aor3s NON-EXISTING');
    }

}