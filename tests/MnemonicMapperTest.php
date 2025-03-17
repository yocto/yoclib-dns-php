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
        $fallbackDeserializer = static function($value){
            if(preg_match('/^TYPE\d{1,5}$/',$value)){
                return intval(substr($value,4));
            }
            return null;
        };
        $fallbackSerializer = static function($key){
            return 'TYPE'.$key;
        };

        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([]));
        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([],false));
        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([],false,$fallbackDeserializer));
        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([],false,$fallbackDeserializer,$fallbackSerializer));

        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([
            'abc' => 123,
        ]));
        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([
            'abc' => 123,
        ],false));
        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([
            'abc' => 123,
        ],false,$fallbackDeserializer));
        self::assertInstanceOf(MnemonicMapper::class,new MnemonicMapper([
            'abc' => 123,
        ],false,$fallbackDeserializer,$fallbackSerializer));
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

        $fallbackDeserializer = static function($value){
            if(preg_match('/^TYPE\d{1,5}$/',$value)){
                return intval(substr($value,4));
            }
            return null;
        };
        $mapperNoIntegerWithFallback = new MnemonicMapper([
            'abc' => 123,
        ],false,$fallbackDeserializer);

        self::assertSame(123,$mapper->deserializeMnemonic('abc'));
        self::assertSame(123,$mapperNoInteger->deserializeMnemonic('abc'));
        self::assertSame(123,$mapperNoIntegerWithFallback->deserializeMnemonic('abc'));

        self::assertSame(456,$mapper->deserializeMnemonic('456'));
        self::assertSame(456,$mapperNoIntegerWithFallback->deserializeMnemonic('TYPE456'));
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

        $fallbackSerializer = static function($key){
            return 'TYPE'.$key;
        };
        $mapperNoIntegerWithFallback = new MnemonicMapper([
            'abc' => 123,
        ],false,null,$fallbackSerializer);

        self::assertSame('abc',$mapper->serializeMnemonic(123));
        self::assertSame('abc',$mapperNoInteger->serializeMnemonic(123));
        self::assertSame('abc',$mapperNoIntegerWithFallback->serializeMnemonic(123));

        self::assertSame('456',$mapper->serializeMnemonic(456));
        self::assertSame('TYPE456',$mapperNoIntegerWithFallback->serializeMnemonic(456));
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