<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class CharacterString implements Field{

    private const BACKSLASH = '\\';
    private const QUOTE = '"';

    private string $value;

    /**
     * @param string $value
     * @throws DNSFieldException
     */
    public function __construct(string $value){
        if(strlen($value)>255){
            throw new DNSFieldException("Character string can have 255 characters at most.");
        }
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string{
        return $this->value;
    }

    /**
     * @param ?bool|null $alwaysQuoted
     * @return string
     */
    public function serializeToPresentationFormat(?bool $alwaysQuoted=false): string{
        $backslashEscapedValue = str_replace(self::BACKSLASH,self::BACKSLASH.self::BACKSLASH,$this->value);
        $escapedValue = str_replace(self::QUOTE,self::BACKSLASH.self::QUOTE,$backslashEscapedValue);
        //TODO Check only spaces, or also other whitespaces
        if($alwaysQuoted || $this->value==='' || str_contains($this->value,' ')){
            return self::QUOTE.($escapedValue).self::QUOTE;
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
        $isQuoted = ($data[0] ?? null)===self::QUOTE;
        if($isQuoted){
            $unquotedValue = substr($data,1,strlen($data)-2);
            $quoteUnescapedValue = str_replace(self::BACKSLASH.self::QUOTE,self::QUOTE,$unquotedValue);
            $backslashUnescapedValue = str_replace(self::BACKSLASH.self::BACKSLASH,self::BACKSLASH,$quoteUnescapedValue);
            return new self($backslashUnescapedValue);
        }
        $quoteUnescapedValue = str_replace(self::BACKSLASH.self::QUOTE,self::QUOTE,$data);
        $backslashUnescapedValue = str_replace(self::BACKSLASH.self::BACKSLASH,self::BACKSLASH,$quoteUnescapedValue);
        return new self($backslashUnescapedValue);
    }

    /**
     * @param string $data
     * @return CharacterString
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): CharacterString{
        if(strlen($data)<=0){
            throw new DNSFieldException('A character string should have at least one octet of data to indicate the length.');
        }
        $length = ord($data[0]);
        if(strlen($data)<1+$length){
            throw new DNSFieldException('The character string length is higher than the available bytes.');
        }
        return new self(substr($data,1,$length));
    }

}