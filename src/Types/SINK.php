<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class SINK extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==3){
            throw new DNSTypeException('Only three fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger8)){
            throw new DNSTypeException('First field should be an UInt8.');
        }
        if(!($fields[1] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Second field should be an UInt8.');
        }
        if(!($fields[2] instanceof Binary)){
            throw new DNSTypeException('Third field should be binary.');
        }
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return implode(' ',[
            $this->getFields()[0]->serializeToPresentationFormat(),
            $this->getFields()[1]->serializeToPresentationFormat(),
            base64_encode($this->getFields()[2]->getValue()),
        ]);
    }

    /**
     * @param string $data
     * @return SINK
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): SINK{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<2){
            throw new DNSTypeException('SINK record should contain at least 2 fields.');
        }
        $output = '';
        for($i=2;$i<count($tokens);$i++){
            $token = $tokens[$i];
            $output .= $token;
        }
        return new self([
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[0]),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[1]),
            new Binary(base64_decode($output)),
        ]);
    }

    /**
     * @param string $data
     * @return SINK
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): SINK{
        $offset = 0;

        $coding = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($coding);

        $subcoding = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($subcoding);

        $remainingData = substr($data,$offset);

        return new self([
            UnsignedInteger8::deserializeFromWireFormat($coding),
            UnsignedInteger8::deserializeFromWireFormat($subcoding),
            Binary::deserializeFromWireFormat($remainingData),
        ]);
    }

}