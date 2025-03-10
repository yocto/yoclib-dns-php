<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\LineLexer;

class CSYNC extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==3){
            throw new DNSTypeException('Only three fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger32)){
            throw new DNSTypeException('First field should be an UInt32.');
        }
        if(!($fields[1] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Second field should be an UInt16.');
        }
        if(!($fields[2] instanceof Bitmap)){
            throw new DNSTypeException('Third field should be a bitmap.');
        }
    }

    protected static function getMapping(): array{
        //TODO Add mapping
        return [
            DNSType::A => 'A',
        ];
    }

    /**
     * @param string $data
     * @return CSYNC
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): CSYNC{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<2){
            throw new DNSTypeException('CSYNC record should contain at least 2 fields.');
        }
        return new self([
            UnsignedInteger32::deserializeFromPresentationFormat($tokens[0]),
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[1]),
            Bitmap::deserializeFromPresentationFormat(implode(' ',array_slice($tokens,2)),self::getMapping()),
        ]);
    }

    /**
     * @param string $data
     * @return CSYNC
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): CSYNC{
        $offset = 0;

        $soaSerial = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($soaSerial);

        $flags = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($flags);

        $bitmap = substr($data,$offset);

        return new self([
            UnsignedInteger32::deserializeFromWireFormat($soaSerial),
            UnsignedInteger16::deserializeFromWireFormat($flags),
            Bitmap::deserializeFromWireFormat($bitmap),
        ]);
    }

}