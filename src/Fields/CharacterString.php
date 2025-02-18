<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class CharacterString implements Field{

    private string $value;

    public function __construct(string $value){
        $this->value = $value;
    }

    public function getValue(): string{
        return $this->value;
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        $escapedValue = $this->value;
        $escapedValue = str_replace("\\","\\\\",$escapedValue);
        if(strpos($this->value,' ')!==false || strpos($this->value,'"')!==false){
            $escapedValue = str_replace("\"","\\\"",$escapedValue);
            return '"'.$escapedValue.'"';
        }
        return $escapedValue;
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        return chr(strlen($this->value)).$this->value;
    }

    /**
     * @param string $data
     * @return CharacterString
     */
    public static function deserializeFromPresentationFormat(string $data): CharacterString{
        $isQuoted = ($data[0] ?? null)==='"';
        if($isQuoted){
            $value = substr($data,1,strlen($data)-2);
            $value = str_replace("\\\"","\"",$value);
            return new self($value);
        }
        return new self($data);
    }

    /**
     * @param string $data
     * @return CharacterString
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): CharacterString{
        if(strlen($data)<=0){
            throw new DNSFieldException("A character string should have at least one octet of data to indicate the length.");
        }
        return new self(substr($data,1,strlen($data)));
    }

}