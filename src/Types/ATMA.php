<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class ATMA extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==1){
            throw new DNSTypeException('Only one field allowed.');
        }
        if(!($fields[0] instanceof Binary)){
            throw new DNSTypeException('First field should be binary.');
        }
    }

    /**
     * @return string
     * @throws DNSFieldException
     */
    public function serializeToWireFormat(): string{
        return implode([
            (new UnsignedInteger8(str_starts_with($this->getFields()[0]->getValue(),'+')?0:1))->serializeToWireFormat(),
            $this->getFields()[0]->serializeToWireFormat(),
        ]);
    }

    /**
     * @param string $data
     * @return ATMA
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): ATMA{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==1){
            throw new DNSTypeException('ATMA record should contain 1 field.');
        }
        return new self([
            new Binary($tokens[0]),
        ]);
    }

    /**
     * @param string $data
     * @return ATMA
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): ATMA{
        $offset = 0;

        $format = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($format);
        $formatValue = UnsignedInteger8::deserializeFromWireFormat($format)->getValue();

        $address = substr($data,$offset);
        if($formatValue===0 && !str_starts_with($address,'+')){
            throw new DNSTypeException('Address of format 0 does not start with plus sign.');
        }
        if($formatValue===1 && str_starts_with($address,'+')){
            throw new DNSTypeException('Address of format 1 cannot start with plus sign.');
        }

        return new self([
            Binary::deserializeFromWireFormat($address),
        ]);
    }

}