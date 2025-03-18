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

class CERT extends Type{

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
        if(!($fields[1] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Second field should be an UInt16.');
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
            (new MnemonicMapper([
                'PKIX' => 1,
                'SPKI' => 2,
                'PGP' => 3,
                'IPKIX' => 4,
                'ISPKI' => 5,
                'IPGP' => 6,
                'ACPKIX' => 7,
                'IACPKIX' => 8,
                'URI' => 253,
                'OID' => 254,
            ]))->serializeMnemonic($this->getFields()[0]->getValue()),
            $this->getFields()[1]->serializeToPresentationFormat(),
            (new MnemonicMapper(MnemonicMapper::MAPPING_ALGORITHMS))->serializeMnemonic($this->getFields()[2]->getValue()),
            base64_encode($this->getFields()[3]->getValue()),
        ]);
    }

    /**
     * @param string $data
     * @return CERT
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): CERT{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<3){
            throw new DNSTypeException('CERT record should contain at least 3 fields.');
        }
        $output = '';
        for($i=3;$i<count($tokens);$i++){
            $token = $tokens[$i];
            $output .= $token;
        }
        return new self([
            new UnsignedInteger16((new MnemonicMapper([
                'PKIX' => 1,
                'SPKI' => 2,
                'PGP' => 3,
                'IPKIX' => 4,
                'ISPKI' => 5,
                'IPGP' => 6,
                'ACPKIX' => 7,
                'IACPKIX' => 8,
                'URI' => 253,
                'OID' => 254,
            ]))->deserializeMnemonic($tokens[0])),
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[1]),
            new UnsignedInteger8((new MnemonicMapper(MnemonicMapper::MAPPING_ALGORITHMS))->deserializeMnemonic($tokens[2])),
            new Binary(base64_decode($output)),
        ]);
    }

    /**
     * @param string $data
     * @return CERT
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): CERT{
        $offset = 0;

        $type = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($type);

        $keyTag = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($keyTag);

        $algorithm = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($algorithm);

        $certificate = substr($data,$offset);
        return new self([
            UnsignedInteger16::deserializeFromWireFormat($type),
            UnsignedInteger16::deserializeFromWireFormat($keyTag),
            UnsignedInteger8::deserializeFromWireFormat($algorithm),
            Binary::deserializeFromWireFormat($certificate),
        ]);
    }

}