<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class HIP extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)<3){
            throw new DNSTypeException('At least three fields required.');
        }
        if(!($fields[0] instanceof UnsignedInteger8)){
            throw new DNSTypeException('First field should be an UInt8.');
        }
        if(!($fields[1] instanceof Binary)){
            throw new DNSTypeException('Second field should be binary.');
        }
        if(!($fields[2] instanceof Binary)){
            throw new DNSTypeException('Third field should be binary.');
        }
        for($i=3;$i<count($fields);$i++){
            if(!($fields[$i] instanceof FQDN)){
                throw new DNSTypeException('Every remaining field should be a FQDN.');
            }
        }
        $totalLength = 0;
        foreach($fields as $field){
            $totalLength += strlen($field->serializeToWireFormat());
        }
        if($totalLength>65536){
            throw new DNSTypeException('Maximum size exceeded.');
        }
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return implode(' ',[
            $this->getFields()[0]->serializeToPresentationFormat(),
            strtoupper(bin2hex($this->getFields()[1]->getValue())),
            base64_encode($this->getFields()[2]->getValue()),
            ...array_map(function(Field $field){
                return $field->serializeToPresentationFormat();
            },array_slice($this->getFields(),3)),
        ]);
    }

    /**
     * @return string
     * @throws DNSFieldException
     */
    public function serializeToWireFormat(): string{
        return implode([
            (new UnsignedInteger8(strlen($this->getFields()[1]->getValue())))->serializeToWireFormat(),
            $this->getFields()[0]->serializeToWireFormat(),
            (new UnsignedInteger16(strlen($this->getFields()[2]->getValue())))->serializeToWireFormat(),
            $this->getFields()[1]->serializeToWireFormat(),
            $this->getFields()[2]->serializeToWireFormat(),
            ...array_map(function(Field $field){
                return $field->serializeToWireFormat();
            },array_slice($this->getFields(),3)),
        ]);
    }

    /**
     * @param string $data
     * @return HIP
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): HIP{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<3){
            throw new DNSTypeException('HIP record should contain at least 3 fields.');
        }
        $rendezvousServers = [];
        for($i=3;$i<count($tokens);$i++){
            $token = $tokens[$i];
            $rendezvousServers[] = FQDN::deserializeFromPresentationFormat($token);
        }
        return new self([
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[0]),
            new Binary(hex2bin($tokens[1])),
            new Binary(base64_decode($tokens[2])),
            ...$rendezvousServers,
        ]);
    }

    /**
     * @param string $data
     * @return HIP
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): HIP{
        $offset = 0;

        $hitLength = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($hitLength);

        $pkAlgorithm = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($pkAlgorithm);

        $pkLength = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($pkLength);

        $hit = substr($data,$offset,Binary::calculateLength(substr($data,$offset,UnsignedInteger8::deserializeFromWireFormat($hitLength)->getValue())));
        $offset += strlen($hit);

        $publicKey = substr($data,$offset,Binary::calculateLength(substr($data,$offset,UnsignedInteger16::deserializeFromWireFormat($pkLength)->getValue())));
        $offset += strlen($publicKey);

        $rendezvousServers = [];
        while($offset<strlen($data)){
            $rendezvousServer = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
            $offset += strlen($rendezvousServer);
            $rendezvousServers[] = FQDN::deserializeFromWireFormat($rendezvousServer);
        }
        return new self([
            UnsignedInteger8::deserializeFromWireFormat($pkAlgorithm),
            Binary::deserializeFromWireFormat($hit),
            Binary::deserializeFromWireFormat($publicKey),
            ...$rendezvousServers,
        ]);
    }

}