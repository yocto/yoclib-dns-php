<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class CharacterString implements Field{

    private const BACKSLASH = '\\';
    private const QUOTE = '"';

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
        $backslashEscapedValue = str_replace(self::BACKSLASH,self::BACKSLASH.self::BACKSLASH,$this->value);
        //TODO Check only spaces, or also other whitespaces
        if(str_contains($this->value,' ')){
            $escapedValue = str_replace(self::QUOTE,self::BACKSLASH.self::QUOTE,$backslashEscapedValue);
            return self::QUOTE.($escapedValue).self::QUOTE;
        }
        return $backslashEscapedValue;
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
        $isQuoted = ($data[0] ?? null)===self::QUOTE;
        if($isQuoted){
            $unquotedValue = substr($data,1,strlen($data)-2);
            $quoteUnescapedValue = str_replace(self::BACKSLASH.self::QUOTE,self::QUOTE,$unquotedValue);
            $backslashUnescapedValue = str_replace(self::BACKSLASH.self::BACKSLASH,self::BACKSLASH,$quoteUnescapedValue);
            return new self($backslashUnescapedValue);
        }
        $backslashUnescapedValue = str_replace(self::BACKSLASH.self::BACKSLASH,self::BACKSLASH,$data);
        return new self($backslashUnescapedValue);
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
        $length = ord($data[0]);
        if(strlen($data)<1+$length){
            throw new DNSFieldException("A character string length is higher than the available bytes.");
        }
        return new self(substr($data,1,$length));
    }

}