<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class Binary implements Field{

    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value){
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string{
        return $this->value;
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return '\# '.strlen($this->value).' '.strtoupper(bin2hex($this->value));
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        return $this->value;
    }

    /**
     * @param string $data
     * @return Binary
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): Binary{
        $parts = explode(' ',$data);
        if($parts[0]!=='\#'){
            throw new DNSFieldException('Binary should start with \# characters.');
        }
        if(!preg_match('/\d+/',$parts[1] ?? '')){
            throw new DNSFieldException('Binary length should only contain digits.');
        }
        $length = intval($parts[1]) ?? -1;
        $output = '';
        for($i=2;$i<count($parts);$i++){
            $word = $parts[$i];
            if(strlen($word)%2!==0){
                throw new DNSFieldException('Every part of hexadecimal data should come in pairs of two.');
            }
            $output .= hex2bin($word);
        }
        if(strlen($output)!==$length){
            throw new DNSFieldException('Binary length is not same as actual data.');
        }
        return new Binary($output);
    }

    /**
     * @param string $data
     * @return Binary
     */
    public static function deserializeFromWireFormat(string $data): Binary{
        return new self($data);
    }

}