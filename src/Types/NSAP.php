<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\LineLexer;

class NSAP extends Type{

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
     */
    public function serializeToPresentationFormat(): string{
        return '0x'.strtoupper(bin2hex($this->getFields()[0]->getValue()));
    }

    /**
     * @param string $data
     * @return NSAP
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NSAP{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==1){
            throw new DNSTypeException('A NSAP record should contain 1 field.');
        }
        if(!preg_match('/0x[A-Fa-f0-9]+(\\.[A-Fa-f0-9]+)/',$tokens[0])){
            throw new DNSTypeException('NSAP address not valid.');
        }
        $output = hex2bin(str_replace(['0x','.'],['',''],$tokens[0]));
        return new self([
            new Binary($output),
        ]);
    }

    /**
     * @param string $data
     * @return NSAP
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NSAP{
        return new self([
            Binary::deserializeFromWireFormat($data),
        ]);
    }

}