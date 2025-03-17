<?php
namespace YOCLIB\DNS\Tests;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\MnemonicMapper;

class MnemonicMapperTest extends TestCase{

    /**
     * @return void
     * @throws DNSMnemonicException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([]));
        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([],false));

        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([
            'abc' => 123,
        ]));
        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([
            'abc' => 123,
        ],false));
    }

    /**
     * @return void
     * @throws DNSMnemonicException
     */
    public function testConstructorInvalidKey(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('All mapping keys should be strings.');

        new MnemonicMapper([
            123 => 456,
        ]);
    }

    /**
     * @return void
     * @throws DNSMnemonicException
     */
    public function testConstructorInvalidValue(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('All mapping values should be integers.');

        new MnemonicMapper([
            'abc' => 'def',
        ]);
    }

    /**
     * @return void
     * @throws DNSMnemonicException
     */
    public function testDeserializeMnemonic(): void{
        $mapper = new MnemonicMapper([
            'abc' => 123,
        ]);

        $mapperNoInteger = new MnemonicMapper([
            'abc' => 123,
        ],false);

        self::assertSame(123,$mapper->deserializeMnemonic('abc'));
        self::assertSame(123,$mapperNoInteger->deserializeMnemonic('abc'));

        self::assertSame(456,$mapper->deserializeMnemonic('456'));
    }

    public function testDeserializeMnemonicUnknownKey(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic key during deserialization.');

        $mapperNoInteger = new MnemonicMapper([
            'abc' => 123,
        ],false);

        $mapperNoInteger->deserializeMnemonic('456');
    }

    /**
     * @return void
     * @throws DNSMnemonicException
     */
    public function testSerializeMnemonic(): void{
        $mapper = new MnemonicMapper([
            'abc' => 123,
        ]);

        $mapperNoInteger = new MnemonicMapper([
            'abc' => 123,
        ],false);

        self::assertSame('abc',$mapper->serializeMnemonic(123));
        self::assertSame('abc',$mapperNoInteger->serializeMnemonic(123));

        self::assertSame('456',$mapper->serializeMnemonic(456));
    }

    /**
     * @return void
     * @throws DNSMnemonicException
     */
    public function testSerializeMnemonicUnknownValue(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('Invalid mnemonic value during serialization.');

        $mapperNoInteger = new MnemonicMapper([
            'abc' => 123,
        ],false);

        $mapperNoInteger->serializeMnemonic(456);
    }

}