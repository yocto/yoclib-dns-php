<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\MnemonicMapper;

class BitmapTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(Bitmap::class,new Bitmap([1,2,3,4]));
        self::assertInstanceOf(Bitmap::class,new Bitmap([1,2,3,4,5]));
        self::assertInstanceOf(Bitmap::class,new Bitmap([1,2,3,4,5,6]));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorDuplicateBits(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('No duplicate bits allowed.');

        new Bitmap([1,2,3,3,4,5,6]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorNonIntegerBits(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Only integers allowed.');

        new Bitmap([1,2,'3',4,5,6]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorNegativeIntegers(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Only positive integers allowed.');

        new Bitmap([1,2,-3,4,5,6]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame([1,2,3,4],(new Bitmap([1,2,3,4]))->getValue());
        self::assertSame([1,2,3,4,5],(new Bitmap([1,2,3,4,5]))->getValue());
        self::assertSame([1,2,3,4,5,6],(new Bitmap([1,2,3,4,5,6]))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     */
    public function testSerializeToPresentationFormat(): void{
        $mapper = new MnemonicMapper([
            'ONE' => 1,
            'TWO' => 2,
            'THREE' => 3,
            'FOUR' => 4,
            'FIVE' => 5,
            'SIX' => 6,
        ]);

        self::assertSame('ONE TWO THREE FOUR',(new Bitmap([1,2,3,4]))->serializeToPresentationFormat($mapper));
        self::assertSame('ONE TWO THREE FOUR FIVE',(new Bitmap([1,2,3,4,5]))->serializeToPresentationFormat($mapper));
        self::assertSame('ONE TWO THREE FOUR FIVE SIX',(new Bitmap([1,2,3,4,5,6]))->serializeToPresentationFormat($mapper));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x1E",(new Bitmap([1,2,3,4]))->serializeToWireFormat());
        self::assertSame("\x3E",(new Bitmap([1,2,3,4,5]))->serializeToWireFormat());
        self::assertSame("\x7E",(new Bitmap([1,2,3,4,5,6]))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(1,Bitmap::calculateLength("\x1E"));
        self::assertSame(14,Bitmap::calculateLength("\x1EtrailingBytes"));
        self::assertSame(1,Bitmap::calculateLength("\x3E"));
        self::assertSame(14,Bitmap::calculateLength("\x3EtrailingBytes"));
        self::assertSame(1,Bitmap::calculateLength("\x7E"));
        self::assertSame(14,Bitmap::calculateLength("\x7EtrailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     */
    public function testDeserializeFromPresentationFormat(): void{
        $mapper = new MnemonicMapper([
            'ONE' => 1,
            'TWO' => 2,
            'THREE' => 3,
            'FOUR' => 4,
            'FIVE' => 5,
            'SIX' => 6,
        ]);

        self::assertSame([1,2,3,4],Bitmap::deserializeFromPresentationFormat('ONE TWO THREE FOUR',$mapper)->getValue());
        self::assertSame([1,2,3,4,5],Bitmap::deserializeFromPresentationFormat('ONE TWO THREE FOUR FIVE',$mapper)->getValue());
        self::assertSame([1,2,3,4,5,6],Bitmap::deserializeFromPresentationFormat('ONE TWO THREE FOUR FIVE SIX',$mapper)->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame([1,2,3,4],Bitmap::deserializeFromWireFormat("\x1E")->getValue());
        self::assertSame([1,2,3,4,5],Bitmap::deserializeFromWireFormat("\x3E")->getValue());
        self::assertSame([1,2,3,4,5,6],Bitmap::deserializeFromWireFormat("\x7E")->getValue());
    }

}