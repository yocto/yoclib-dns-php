<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\Fields\WindowBlockBitmap;
use YOCLIB\DNS\LineLexer;
use YOCLIB\DNS\MnemonicMapper;

class NSEC3 extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==6){
            throw new DNSTypeException('Only two fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger8)){
            throw new DNSTypeException('First field should be an UInt8.');
        }
        if(!($fields[1] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Second field should be an UInt8.');
        }
        if(!($fields[2] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Third field should be an UInt16.');
        }
        if(!($fields[3] instanceof CharacterString)){
            throw new DNSTypeException('Fourth field should be a character string.');
        }
        if(!($fields[4] instanceof CharacterString)){
            throw new DNSTypeException('Fifth field should be a character string.');
        }
        if(!($fields[5] instanceof WindowBlockBitmap)){
            throw new DNSTypeException('Sixth field should be a window block bitmap.');
        }
    }

    /**
     * @return MnemonicMapper
     * @throws DNSMnemonicException
     */
    protected static function getMapper(): MnemonicMapper{
        return new MnemonicMapper(MnemonicMapper::MAPPING_DNS_TYPES,false,static function($value){
            if(preg_match('/^TYPE\d{1,5}$/',$value)){
                return intval(substr($value,4));
            }
            return null;
        },static function($key){
            return 'TYPE'.$key;
        });
    }

    /**
     * @param string $data
     * @return NSEC3
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NSEC3{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<5){
            throw new DNSTypeException('NSEC3 record should contain at least 5 fields.');
        }
        return new self([
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[0]),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[1]),
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[2]),
            CharacterString::deserializeFromPresentationFormat($tokens[3]),
            CharacterString::deserializeFromPresentationFormat($tokens[4]),
            WindowBlockBitmap::deserializeFromPresentationFormat(array_slice($tokens,5),self::getMapper()),
        ]);
    }

    /**
     * @param string $data
     * @return NSEC3
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NSEC3{
        $offset = 0;

        $hashAlgorithm = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($hashAlgorithm);

        $flags = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($flags);

        $iterations = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($iterations);

        $salt = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($salt);

        $hash = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($hash);

        $typeBitmap = substr($data,$offset);

        return new self([
            UnsignedInteger8::deserializeFromWireFormat($hashAlgorithm),
            UnsignedInteger8::deserializeFromWireFormat($flags),
            UnsignedInteger16::deserializeFromWireFormat($iterations),
            CharacterString::deserializeFromWireFormat($salt),
            CharacterString::deserializeFromWireFormat($hash),
            WindowBlockBitmap::deserializeFromWireFormat($typeBitmap),
        ]);
    }

}