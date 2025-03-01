<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

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
     * @param ?array|null $mapping
     * @return string
     */
    public function serializeToPresentationFormat(?array $mapping=null): string{
        $items = [];
        foreach($this->value AS $bit){
            $items[] = $mapping[$bit] ?? strval($bit);
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
     * @param string $data
     * @param ?array|null $mapping
     * @return Bitmap
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data,?array $mapping=null): Bitmap{
        $bits = [];
        foreach(explode(' ',$data) AS $item){
            foreach($mapping AS $bit=>$map){
                if($map===$item){
                    $bits[] = $bit;
                    break;
                }
            }
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