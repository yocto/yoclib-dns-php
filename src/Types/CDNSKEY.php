<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;
use YOCLIB\DNS\MnemonicMapper;

class CDNSKEY extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==4){
            throw new DNSTypeException('Only four fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger16)){
            throw new DNSTypeException('First field should be an UInt16.');
        }
        if(!($fields[1] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Second field should be an UInt8.');
        }
        if(!($fields[2] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Third field should be an UInt8.');
        }
        if(!($fields[3] instanceof Binary)){
            throw new DNSTypeException('Fourth field should be binary.');
        }
    }

    /**
     * @return string
     * @throws DNSMnemonicException
     */
    public function serializeToPresentationFormat(): string{
        return implode(' ',[
            $this->getFields()[0]->serializeToPresentationFormat(),
            $this->getFields()[1]->serializeToPresentationFormat(),
            (new MnemonicMapper([
                'RSAMD5' => 1,
                'DH' => 2,
                'DSA' => 3,
                'ECC' => 4,
                'RSASHA1' => 5,
                'INDIRECT' => 252,
                'PRIVATEDNS' => 253,
                'PRIVATEOID' => 254,
            ]))->serializeMnemonic($this->getFields()[2]->getValue()),
            base64_encode($this->getFields()[3]->getValue()),
        ]);
    }

    /**
     * @param string $data
     * @return CDNSKEY
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): CDNSKEY{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<3){
            throw new DNSTypeException('CDNSKEY record should contain at least 3 fields.');
        }
        $output = '';
        for($i=3;$i<count($tokens);$i++){
            $token = $tokens[$i];
            $output .= $token;
        }
        return new self([
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[0]),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[1]),
            new UnsignedInteger8((new MnemonicMapper([
                'RSAMD5' => 1,
                'DH' => 2,
                'DSA' => 3,
                'ECC' => 4,
                'RSASHA1' => 5,
                'INDIRECT' => 252,
                'PRIVATEDNS' => 253,
                'PRIVATEOID' => 254,
            ]))->deserializeMnemonic($tokens[2])),
            new Binary(base64_decode($output)),
        ]);
    }

    /**
     * @param string $data
     * @return CDNSKEY
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): CDNSKEY{
        $offset = 0;

        $flags = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($flags);

        $protocol = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($protocol);

        $algorithm = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($algorithm);

        $publicKey = substr($data,$offset);
        return new self([
            UnsignedInteger16::deserializeFromWireFormat($flags),
            UnsignedInteger8::deserializeFromWireFormat($protocol),
            UnsignedInteger8::deserializeFromWireFormat($algorithm),
            Binary::deserializeFromWireFormat($publicKey),
        ]);
    }

}