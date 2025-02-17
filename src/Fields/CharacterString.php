<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSConverterException;

class CharacterString implements Field{

    private string $value;

    public function serializeToPresentationFormat(): string{
        $escapedValue = $this->value;
        $escapedValue = str_replace("\\","\\\\",$escapedValue);
        if(strpos($this->value,' ')!==false || strpos($this->value,'"')){
            $escapedValue = str_replace("\"","\\\"",$escapedValue);
            return '"'.$escapedValue.'"';
        }
        return $escapedValue;
    }

    public function serializeToWireFormat(): string{
        return chr(strlen($this->value)).$this->value;
    }

    public static function deserializeFromPresentationFormat(string $data): CharacterString{
        $isQuoted = ($data[0] ?? null)==='"';
        $obj = new self;
        if($isQuoted){
            $obj->value = substr($data,1,strlen($data)-2);
            $obj->value = str_replace("\\\"","\"",$obj->value);
            return $obj;
        }
        $obj->value = $data;
        return $obj;
    }

    /**
     * @param string $data
     * @return CharacterString
     * @throws DNSConverterException
     */
    public static function deserializeFromWireFormat(string $data): CharacterString{
        if(strlen($data)<=0){
            throw new DNSConverterException("A character string should have at least one octet of data to indicate the length.");
        }
        $obj = new self;
        $obj->value = substr($data,1,strlen($data));
        return $obj;
    }

}