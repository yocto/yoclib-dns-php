<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\IPv6Address;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class IPSECKEY extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==5){
            throw new DNSTypeException('Only five fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger8)){
            throw new DNSTypeException('First field should be an UInt8.');
        }
        if(!($fields[1] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Second field should be an UInt8.');
        }
        if(!($fields[2] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Third field should be an UInt8.');
        }
        if(!($fields[3] instanceof IPv4Address) && !($fields[3] instanceof IPv6Address) && !($fields[3] instanceof FQDN)){
            throw new DNSTypeException('Fourth field should be an IPv4 address, IPv6 address or a FQDN.');
        }
        if(!($fields[4] instanceof Binary)){
            throw new DNSTypeException('Fifth field should be binary.');
        }
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return implode(' ',[
            $this->getFields()[0]->serializeToPresentationFormat(),
            $this->getFields()[1]->serializeToPresentationFormat(),
            $this->getFields()[2]->serializeToPresentationFormat(),
            $this->getFields()[3]->serializeToPresentationFormat(),
            base64_encode($this->getFields()[4]->getValue()),
        ]);
    }

    /**
     * @param string $data
     * @return IPSECKEY
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): IPSECKEY{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<4){
            throw new DNSTypeException('IPSECKEY record should contain at least 4 fields.');
        }
        $gatewayTypeValue = UnsignedInteger8::deserializeFromPresentationFormat($tokens[1])->getValue();
        switch($gatewayTypeValue){
            case 0:{
                if($tokens[3]!=='.'){
                    throw new DNSTypeException('Gateway should be a dot when type is zero.');
                }
                $gatewayValue = FQDN::deserializeFromWireFormat($tokens[3]);
                break;
            }
            case 1:{
                $gatewayValue = IPv4Address::deserializeFromPresentationFormat($tokens[3]);
                break;
            }
            case 2:{
                $gatewayValue = IPv6Address::deserializeFromPresentationFormat($tokens[3]);
                break;
            }
            case 3:{
                $gatewayValue = FQDN::deserializeFromPresentationFormat($tokens[3]);
                break;
            }
            default:{
                throw new DNSTypeException('Invalid gateway type.');
            }
        }
        $output = '';
        for($i=4;$i<count($tokens);$i++){
            $token = $tokens[$i];
            $output .= $token;
        }
        return new self([
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[0]),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[1]),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[2]),
            $gatewayValue,
            new Binary(base64_decode($output)),
        ]);
    }

    /**
     * @param string $data
     * @return IPSECKEY
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): IPSECKEY{
        $offset = 0;

        $precedence = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($precedence);

        $gatewayType = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $gatewayTypeValue = UnsignedInteger8::deserializeFromWireFormat($gatewayType)->getValue();
        $offset += strlen($gatewayType);

        $algorithm = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($algorithm);

        switch($gatewayTypeValue){
            case 0:{
                $gatewayValue = FQDN::deserializeFromWireFormat("\x00");
                break;
            }
            case 1:{
                $gateway = substr($data,$offset,IPv4Address::calculateLength(substr($data,$offset)));
                $offset += strlen($gateway);
                $gatewayValue = IPv4Address::deserializeFromWireFormat($gateway);
                break;
            }
            case 2:{
                $gateway = substr($data,$offset,IPv6Address::calculateLength(substr($data,$offset)));
                $offset += strlen($gateway);
                $gatewayValue = IPv6Address::deserializeFromWireFormat($gateway);
                break;
            }
            case 3:{
                $gateway = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
                $offset += strlen($gateway);
                $gatewayValue = FQDN::deserializeFromWireFormat($gateway);
                break;
            }
            default:{
                throw new DNSTypeException('Invalid gateway type.');
            }
        }

        $publicKey = substr($data,$offset);
        return new self([
            UnsignedInteger8::deserializeFromWireFormat($precedence),
            UnsignedInteger8::deserializeFromWireFormat($gatewayType),
            UnsignedInteger8::deserializeFromWireFormat($algorithm),
            $gatewayValue,
            Binary::deserializeFromWireFormat($publicKey),
        ]);
    }

}