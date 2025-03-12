<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\ServiceParameter;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\LineLexer;

class HTTPS extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)<2){
            throw new DNSTypeException('At least two fields required.');
        }
        if(!($fields[0] instanceof UnsignedInteger16)){
            throw new DNSTypeException('First field should be an UInt16.');
        }
        if(!($fields[1] instanceof FQDN)){
            throw new DNSTypeException('Second field should be a FQDN.');
        }
        $totalLength = strlen($fields[0]->serializeToWireFormat()) + strlen($fields[1]->serializeToWireFormat());
        foreach(array_slice($fields,2) as $field){
            if(!($field instanceof ServiceParameter)){
                throw new DNSTypeException('Every field after second field should be a service parameter.');
            }
            $totalLength += strlen($field->serializeToWireFormat());
        }
        if($totalLength>65536){
            throw new DNSTypeException('Maximum size exceeded.');
        }
    }

    /**
     * @param string $data
     * @return HTTPS
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): HTTPS{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<2){
            throw new DNSTypeException('HTTPS record should have at least two fields.');
        }
        return new self([
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[0]),
            FQDN::deserializeFromPresentationFormat($tokens[1]),
            ...array_map(static function(string $token){
                return ServiceParameter::deserializeFromPresentationFormat($token);
            },array_slice($tokens,2)),
        ]);
    }

    /**
     * @param string $data
     * @return HTTPS
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): HTTPS{
        $offset = 0;

        $priority = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($priority);

        $target = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($target);

        $parameters = [];
        while($offset<strlen($data)){
            $parameter = substr($data,$offset,ServiceParameter::calculateLength(substr($data,$offset)));
            $parameters[] = $parameter;
            $offset += strlen($parameter);
        }
        return new self([
            UnsignedInteger16::deserializeFromWireFormat($priority),
            FQDN::deserializeFromWireFormat($target),
            ...array_map(static function(string $parameter){
                return ServiceParameter::deserializeFromWireFormat($parameter);
            },$parameters),
        ]);
    }

}