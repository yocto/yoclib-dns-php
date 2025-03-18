<?php
namespace YOCLIB\DNS\Types;

use DateTime;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;
use YOCLIB\DNS\MnemonicMapper;

class SIG extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==9){
            throw new DNSTypeException('Only nine fields allowed.');
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
        if(!($fields[3] instanceof UnsignedInteger32) && !($fields[3] instanceof Binary && $fields[3]->getValue()==='')){
            throw new DNSTypeException('Fourth field should be an UInt32 or an empty binary.');
        }
        if(!($fields[4] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Fifth field should be an UInt32.');
        }
        if(!($fields[5] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Sixth field should be an UInt32.');
        }
        if(!($fields[6] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Seventh field should be an UInt16.');
        }
        if(!($fields[7] instanceof FQDN)){
            throw new DNSTypeException('Eighth field should be a FQDN.');
        }
        if(!($fields[8] instanceof Binary)){
            throw new DNSTypeException('Ninth field should be binary.');
        }
    }

    /**
     * @return string
     * @throws DNSMnemonicException
     */
    public function serializeToPresentationFormat(): string{
        return implode(' ',[
            (new MnemonicMapper(MnemonicMapper::MAPPING_DNS_TYPES))->serializeMnemonic($this->getFields()[0]->getValue()),
            $this->getFields()[1]->serializeToPresentationFormat(),
            $this->getFields()[2]->serializeToPresentationFormat(),
            $this->getFields()[3]->getValue()===''?'-':$this->getFields()[3]->serializeToPresentationFormat(),
            (new DateTime)->setTimestamp($this->getFields()[4]->getValue())->format('YmdHis'),
            (new DateTime)->setTimestamp($this->getFields()[5]->getValue())->format('YmdHis'),
            $this->getFields()[6]->serializeToPresentationFormat(),
            $this->getFields()[7]->serializeToPresentationFormat(),
            base64_encode($this->getFields()[8]->getValue()),
        ]);
    }

    /**
     * @param string $data
     * @return SIG
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): SIG{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<8){
            throw new DNSTypeException('SIG record should contain at least 8 fields.');
        }
        $omitTTL = !preg_match('/^\d+$/',$tokens[6]);
        $output = '';
        for($i=($omitTTL?7:8);$i<count($tokens);$i++){
            $token = $tokens[$i];
            $output .= $token;
        }
        if($omitTTL){
            return new self([
                new UnsignedInteger16((new MnemonicMapper(MnemonicMapper::MAPPING_DNS_TYPES))->deserializeMnemonic($tokens[0])),
                UnsignedInteger8::deserializeFromPresentationFormat($tokens[1]),
                UnsignedInteger8::deserializeFromPresentationFormat($tokens[2]),
                new Binary(''),
                UnsignedInteger32::deserializeFromPresentationFormat(DateTime::createFromFormat('YmdHis',$tokens[3])->getTimestamp()),
                UnsignedInteger32::deserializeFromPresentationFormat(DateTime::createFromFormat('YmdHis',$tokens[4])->getTimestamp()),
                UnsignedInteger16::deserializeFromPresentationFormat($tokens[5]),
                FQDN::deserializeFromPresentationFormat($tokens[6]),
                new Binary(base64_decode($output)),
            ]);
        }
        return new self([
            new UnsignedInteger16((new MnemonicMapper(MnemonicMapper::MAPPING_DNS_TYPES))->deserializeMnemonic($tokens[0])),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[1]),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[2]),
            UnsignedInteger32::deserializeFromPresentationFormat($tokens[3]), //TODO Make TTL signed
            UnsignedInteger32::deserializeFromPresentationFormat(DateTime::createFromFormat('YmdHis',$tokens[4])->getTimestamp()),
            UnsignedInteger32::deserializeFromPresentationFormat(DateTime::createFromFormat('YmdHis',$tokens[5])->getTimestamp()),
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[6]),
            FQDN::deserializeFromPresentationFormat($tokens[7]),
            new Binary(base64_decode($output)),
        ]);
    }

    /**
     * @param string $data
     * @return SIG
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): SIG{
        $offset = 0;

        $typeCovered = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($typeCovered);

        $algorithm = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($algorithm);

        $labels = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($labels);

        $originalTTL = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($originalTTL);

        $signatureExpiration = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($signatureExpiration);

        $signatureInception = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($signatureInception);

        $keyTag = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($keyTag);

        $signersName = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($signersName);

        $signature = substr($data,$offset);
        return new self([
            UnsignedInteger16::deserializeFromWireFormat($typeCovered),
            UnsignedInteger8::deserializeFromWireFormat($algorithm),
            UnsignedInteger8::deserializeFromWireFormat($labels),
            UnsignedInteger32::deserializeFromWireFormat($originalTTL),
            UnsignedInteger32::deserializeFromWireFormat($signatureExpiration),
            UnsignedInteger32::deserializeFromWireFormat($signatureInception),
            UnsignedInteger16::deserializeFromWireFormat($keyTag),
            FQDN::deserializeFromWireFormat($signersName),
            Binary::deserializeFromWireFormat($signature),
        ]);
    }

}