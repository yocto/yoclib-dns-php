<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\LineLexer;

class DHCID extends Type{

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
        return base64_encode($this->getFields()[0]->getValue());
    }

    /**
     * @param string $data
     * @return DHCID
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): DHCID{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<1){
            throw new DNSTypeException('DHCID record should contain at least 1 field.');
        }
        $output = '';
        for($i=0;$i<count($tokens);$i++){
            $token = $tokens[$i];
            $output .= $token;
        }
        return new self([
            new Binary(base64_decode($output)),
        ]);
    }

    /**
     * @param string $data
     * @return DHCID
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): DHCID{
        return new self([
            Binary::deserializeFromWireFormat($data),
        ]);
    }

}