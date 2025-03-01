<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\LineLexer;

class Unknown extends Type{

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
        return '\# '.strlen($binary).(strlen($binary)!==0?(' '.strtoupper(bin2hex($binary))):'');
    }

    /**
     * @param string $data
     * @return Unknown
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): Unknown{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)>=3){
            throw new DNSTypeException('Unknown record should contain at least 3 fields.');
        }
        if($tokens[0]!=='\#'){
            throw new DNSTypeException('Binary should start with \# characters.');
        }
        if(!preg_match('/\d+/',$tokens[1] ?? '')){
            throw new DNSTypeException('Length should only contain digits.');
        }
        $length = intval($tokens[1]) ?? -1;
        $output = '';
        for($i=2;$i<count($tokens);$i++){
            $token = $tokens[$i];
            if(strlen($token)%2!==0){
                throw new DNSTypeException('Every part of hexadecimal data should come in pairs of two.');
            }
            $output .= hex2bin($token);
        }
        if(strlen($output)!==$length){
            throw new DNSTypeException('Length is not same as actual data.');
        }
        return new self([
            new Binary($output),
        ]);
    }

    /**
     * @param string $data
     * @return Unknown
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): Unknown{
        return new self([
            new Binary($data),
        ]);
    }

}