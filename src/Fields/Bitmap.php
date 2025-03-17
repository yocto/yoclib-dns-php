<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\LineLexer;
use YOCLIB\DNS\MnemonicMapper;

class Bitmap implements Field{

    private array $value;

    /**
     * @param array|int[] $value
     * @throws DNSFieldException
     */
    public function __construct(array $value){
        $foundBits = [];
        foreach($value AS $bit){
            if(in_array($bit,$foundBits)){
                throw new DNSFieldException('No duplicate bits allowed.');
            }
            $foundBits[] = $bit;
            if(!is_int($bit)){
                throw new DNSFieldException('Only integers allowed.');
            }
            if($bit<0){
                throw new DNSFieldException('Only positive integers allowed.');
            }
        }
        $this->value = $value;
        sort($this->value);
    }

    /**
     * @return array|int[]
     */
    public function getValue(): array{
        return $this->value;
    }

    /**
     * @param ?MnemonicMapper|null $mapper
     * @return string
     * @throws DNSMnemonicException
     */
    public function serializeToPresentationFormat(?MnemonicMapper $mapper=null): string{
        $mapper = $mapper ?? new MnemonicMapper([]);
        $items = [];
        foreach($this->value AS $bit){
            $items[] = $mapper->serializeMnemonic($bit);
        }
        return implode(' ',$items);
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        $bytes = [];
        foreach($this->value AS $bit){
            $byte = intval($bit/8);
            if(!array_key_exists($byte,$bytes)){
                $bytes[$byte] = 0x00;
            }
            $bytes[$byte] |= 1<<($bit%8);
        }

        $bitmap = '';
        if(count($bytes)){
            for($i=0;$i<max(array_keys($bytes))+1;$i++){
                $bitmap[$i] = chr(0x00 | ($bytes[$i] ?? 0x00));
            }
        }
        return $bitmap;
    }

    /**
     * @param string $data
     * @return int
     */
    public static function calculateLength(string $data): int{
        return strlen($data);
    }

    /**
     * @param string|array|string[] $data
     * @param ?MnemonicMapper|null $mapper
     * @return Bitmap
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     */
    public static function deserializeFromPresentationFormat(string|array $data,?MnemonicMapper $mapper=null): Bitmap{
        if(is_string($data)){
            $data = LineLexer::tokenizeLine($data);
        }
        if(!is_array($data)){
            throw new DNSFieldException('Bitmap only accepts string or array.');
        }
        foreach($data AS $token){
            if(!is_string($token)){
                throw new DNSFieldException('Bitmap only supports string elements.');
            }
        }
        $mapper = $mapper ?? new MnemonicMapper([]);
        $bits = [];
        foreach($data AS $item){
            $bits[] = $mapper->deserializeMnemonic($item);
        }
        return new self($bits);
    }

    /**
     * @param string $data
     * @return Bitmap
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): Bitmap{
        $bytes = str_split($data);
        $bits = [];
        for($i=0;$i<count($bytes);$i++){
            $octet = ord($bytes[$i]);
            for($j=0;$j<8;$j++){
                $bit = ($octet>>$j) & 0b1;
                if($bit){
                    $bits[] = $i*8 + $j;
                }
            }
        }
        return new self($bits);
    }

}