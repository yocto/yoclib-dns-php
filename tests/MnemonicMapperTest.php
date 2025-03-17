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

    public function testConstructorInvalidValue(): void{
        self::expectException(DNSMnemonicException::class);
        self::expectExceptionMessage('All mapping values should be integers.');

        new MnemonicMapper([
            'abc' => 'def',
        ]);
    }

}