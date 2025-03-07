<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\LineLexer;

class NIMLOC extends Type{

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
            throw new DNSTypeException('Field should be binary.');
        }
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        $binary = $this->getFields()[0]->getValue();
        return strtoupper(bin2hex($binary));
    }

    /**
     * @param string $data
     * @return NIMLOC
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NIMLOC{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<1){
            throw new DNSTypeException('NIMLOC record should contain at least 1 field.');
        }
        $output = '';
        for($i=0;$i<count($tokens);$i++){
            $token = $tokens[$i];
            if(strlen($token)%2!==0){
                throw new DNSTypeException('Every part of hexadecimal data should come in pairs of two.');
            }
            $output .= hex2bin($token);
        }
        return new static([
            new Binary($output),
        ]);
    }

    /**
     * @param string $data
     * @return NIMLOC
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NIMLOC{
        return new static([
            Binary::deserializeFromWireFormat($data),
        ]);
    }

}