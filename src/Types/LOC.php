<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class LOC extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==7){
            throw new DNSTypeException('Only seven fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger8)){
            throw new DNSTypeException('First field should be an UInt8.');
        }
        if($fields[0]->getValue()!==0){
            throw new DNSTypeException('Version should be zero.');
        }
        if(!($fields[1] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Second field should be an UInt8.');
        }
        if(!($fields[2] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Third field should be an UInt8.');
        }
        if(!($fields[3] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Fourth field should be an UInt8.');
        }
        if(!($fields[4] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Fifth field should be an UInt32.');
        }
        if(!($fields[5] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Sixth field should be an UInt32.');
        }
        if(!($fields[6] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Seventh field should be an UInt32.');
        }
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        $latitudeValue = $this->getFields()[4]->getValue()-0x80000000;
        $latitude = [
            $latitudeValue/(60*60*1000),
        ];
        if($latitudeValue%(60*60*1000)!==0){
            $latitude[] = ($latitudeValue%(60*60*1000))/60*1000;
        }
        if((($latitudeValue%(60*60*1000))%60*1000)!==0){
            $latitude[] = (($latitudeValue%(60*60*1000))%60*1000)/1000;
        }
        if($latitudeValue>0){
            $latitude[] = 'N';
        }else{
            $latitude[] = 'S';
        }

        $longitudeValue = $this->getFields()[5]->getValue()-0x80000000;
        $longitude = [
            $longitudeValue/(60*60*1000),
        ];
        if($longitudeValue%(60*60*1000)!==0){
            $longitude[] = ($longitudeValue%(60*60*1000))/60*1000;
        }
        if((($longitudeValue%(60*60*1000))%60*1000)!==0){
            $longitude[] = (($longitudeValue%(60*60*1000))%60*1000)/1000;
        }
        if($longitudeValue>0){
            $longitude[] = 'E';
        }else{
            $longitude[] = 'W';
        }

        $other = [
            ($this->getFields()[6]->getValue()/100)-10000000,
            self::fromCompactOctet($this->getFields()[1]->getValue()),
            self::fromCompactOctet($this->getFields()[2]->getValue()),
            self::fromCompactOctet($this->getFields()[3]->getValue()),
        ];
        for($i=count($other)-1;$i>=0;$i--){
            if($other[$i]==0){
                unset($other[$i]);
            }else{
                break;
            }
        }

        return implode(' ',[
            ...$latitude,
            ...$longitude,
            ...$other,
        ]);
    }

    /**
     * @param string $data
     * @return LOC
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): LOC{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<5 || count($tokens)>12){
            throw new DNSTypeException('LOC record should contain at least 5 fields or at most 12 fields.');
        }
        $latitude = [];
        $longitude = [];
        $other = [];
        foreach($tokens as $token){
            if(!in_array('N',$latitude) && !in_array('S',$latitude)){
                $latitude[] = $token;
                continue;
            }
            if(!in_array('E',$longitude) && !in_array('W',$longitude)){
                $longitude[] = $token;
                continue;
            }
            $other[] = $token;
        }
        if(count($latitude)<2 || count($latitude)>4){
            throw new DNSTypeException('Too less or much fields for latitude.');
        }
        $latitudeDirection = array_pop($latitude);
        if(($latitude[0] ?? null)!==null && !preg_match('/^\d+$/',$latitude[0])){
            throw new DNSTypeException('Invalid latitude degree format.');
        }
        if(($latitude[1] ?? null)!==null && !preg_match('/^\d+$/',$latitude[1])){
            throw new DNSTypeException('Invalid latitude minute format.');
        }
        if(($latitude[2] ?? null)!==null && !preg_match('/\d+(\.\d{1,3})?/',$latitude[2])){
            throw new DNSTypeException('Invalid latitude seconds format.');
        }
        $latitudeValue = (intval($latitude[0])*60*60*1000) + (intval($latitude[1])*60*1000) + intval(floatval($latitude[2])*1000);
        if($latitudeDirection==='N'){
            $latitudeValue += 0x80000000;
        }
        if($latitudeDirection==='S'){
            $latitudeValue = 0x80000000 - $latitudeValue;
        }
        if(count($longitude)<2 || count($longitude)>4){
            throw new DNSTypeException('Too less or much fields for longitude.');
        }
        $longitudeDirection = array_pop($longitude);
        if(($longitude[0] ?? null)!==null && !preg_match('/^\d+$/',$longitude[0])){
            throw new DNSTypeException('Invalid longitude degree format.');
        }
        if(($longitude[1] ?? null)!==null && !preg_match('/^\d+$/',$longitude[1])){
            throw new DNSTypeException('Invalid longitude minute format.');
        }
        if(($longitude[2] ?? null)!==null && !preg_match('/\d+(\.\d{1,3})?/',$longitude[2])){
            throw new DNSTypeException('Invalid longitude seconds format.');
        }
        $longitudeValue = (intval($longitude[0])*60*60*1000) + (intval($longitude[1])*60*1000) + intval(floatval($longitude[2])*1000);
        if($longitudeDirection==='E'){
            $longitudeValue += 0x80000000;
        }
        if($longitudeDirection==='W'){
            $longitudeValue = 0x80000000 - $longitudeValue;
        }
        if(count($other)>4){
            throw new DNSTypeException('Too much fields after latitude and longitude.');
        }
        if(($other[0] ?? null)!==null && !preg_match('/-?\d+(\.\d{1,2})?m?/',$other[0])){
            throw new DNSTypeException('Invalid altitude format.');
        }
        $altitude = floatval(str_replace('m','',$other[0]));
        if(($other[1] ?? null)!==null && !preg_match('/-?\d+(\.\d{1,2})?m?/',$other[1])){
            throw new DNSTypeException('Invalid size format.');
        }
        $size = self::toCompactOctet(intval(str_replace(['.','m'],['',''],$other[1] ?? '1m')));
        if(($other[2] ?? null)!==null && !preg_match('/-?\d+(\.\d{1,2})?m?/',$other[2])){
            throw new DNSTypeException('Invalid horizontal precision format.');
        }
        $horizontalPrecision = self::toCompactOctet(intval(str_replace(['.','m'],['',''],$other[1] ?? '10000m')));
        if(($other[3] ?? null)!==null && !preg_match('/-?\d+(\.\d{1,2})?m?/',$other[3])){
            throw new DNSTypeException('Invalid vertical precision format.');
        }
        $verticalPrecision = self::toCompactOctet(intval(str_replace(['.','m'],['',''],$other[1] ?? '10m')));
        return new self([
            new UnsignedInteger8(0),
            new UnsignedInteger8($size),
            new UnsignedInteger8($horizontalPrecision),
            new UnsignedInteger8($verticalPrecision),
            new UnsignedInteger32($latitudeValue),
            new UnsignedInteger32($longitudeValue),
            new UnsignedInteger32(intval(10000000+(100*$altitude))),
        ]);
    }

    /**
     * @param string $data
     * @return LOC
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): LOC{
        $offset = 0;

        $version = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($version);

        $size = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($size);

        $horizontalPrecision = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($horizontalPrecision);

        $verticalPrecision = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($verticalPrecision);

        $latitude = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($latitude);

        $longitude = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($longitude);

        $altitude = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($altitude);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            UnsignedInteger8::deserializeFromWireFormat($version),
            UnsignedInteger8::deserializeFromWireFormat($size),
            UnsignedInteger8::deserializeFromWireFormat($horizontalPrecision),
            UnsignedInteger8::deserializeFromWireFormat($verticalPrecision),
            UnsignedInteger32::deserializeFromWireFormat($latitude),
            UnsignedInteger32::deserializeFromWireFormat($longitude),
            UnsignedInteger32::deserializeFromWireFormat($altitude),
        ]);
    }

    private static function fromCompactOctet(string $value): string{
        $poweroften = [1,10,100,1000,10000,100000,1000000,10000000,100000000,1000000000];

        $prec = ord($value);

        $mantissa = (($prec >> 4) & 0x0F) % 10;
        $exponent = (($prec >> 0) & 0x0F) % 10;

        $val = $mantissa * $poweroften[$exponent];

        return sprintf('%d.%.2d',$val/100,$val%100);
    }

    private static function toCompactOctet(int $value): string{
        $poweroften = [1,10,100,1000,10000,100000,1000000,10000000,100000000,1000000000];

        for ($exponent=0;$exponent<9;$exponent++){
            if ($value<$poweroften[$exponent+1]){
                break;
            }
        }

        $mantissa = intval($value / $poweroften[$exponent]);
        if($mantissa>9){
            $mantissa = 9;
        }

        return ($mantissa << 4) | $exponent;
    }

}