<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class WindowBlockBitmap implements Field{

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
     * @param ?array|string[]|null $mapping
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
        $windowBlockBytes = [];
        foreach($this->value AS $bit){
            $windowBlock = intval($bit/256);
            $byte = intval(($bit-(256*$windowBlock))/8);
            if(!array_key_exists($byte,$windowBlockBytes[$windowBlock] ?? [])){
                $windowBlockBytes[$windowBlock][$byte] = 0x00;
            }
            $windowBlockBytes[$windowBlock][$byte] |= 0x80>>($bit%8);
        }
        $bitmap = '';
        foreach($windowBlockBytes AS $windowBlock=>$bytes){
            $windowBlockBitmap = '';
            if(count($bytes)){
                for($i=0;$i<max(array_keys($bytes))+1;$i++){
                    $windowBlockBitmap[$i] = chr(0x00 | ($bytes[$i] ?? 0x00));
                }
                $bitmap .= chr($windowBlock).chr(strlen($windowBlockBitmap)).$windowBlockBitmap;
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
     * @param ?array|string[]|null $mapping
     * @return WindowBlockBitmap
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data,?array $mapping=null): WindowBlockBitmap{
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
     * @return WindowBlockBitmap
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): WindowBlockBitmap{
        $bits = [];
        for($i=0;$i<strlen($data);){
            $windowBlock = ord($data[$i]);
            $i++;
            if($i>=strlen($data)){
                throw new DNSFieldException('Too less data to read window block length.');
            }
            $bitmapLength = ord($data[$i]);
            $i++;
            $bitmapData = substr($data,$i,$bitmapLength);
            $i += strlen($bitmapData);
            if(strlen($bitmapData)!==$bitmapLength){
                throw new DNSFieldException('Too less data to read window block bytes.');
            }
            $bytes = str_split($bitmapData);
            for($j=0;$j<count($bytes);$j++){
                $octet = ord($bytes[$j]);
                for($k=0;$k<8;$k++){
                    $bit = ($octet>>$k) & 0b1;
                    if($bit){
                        $bits[] = $windowBlock*256 + $j*8 + (7-$k);
                    }
                }
            }
        }
        return new self($bits);
    }

}