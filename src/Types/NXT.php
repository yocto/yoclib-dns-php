<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\LineLexer;
use YOCLIB\DNS\MnemonicMapper;

class NXT extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==2){
            throw new DNSTypeException('Only two fields allowed.');
        }
        if(!($fields[0] instanceof FQDN)){
            throw new DNSTypeException('First field should be a FQDN.');
        }
        if(!($fields[1] instanceof Bitmap)){
            throw new DNSTypeException('Second field should be a bitmap.');
        }
    }

    /**
     * @return MnemonicMapper
     */
    protected static function getMapper(): MnemonicMapper{
        return new MnemonicMapper(MnemonicMapper::MAPPING_DNS_TYPES);
    }

    /**
     * @param string $data
     * @return NXT
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NXT{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<1){
            throw new DNSTypeException('NXT record should contain at least 1 field.');
        }
        return new self([
            FQDN::deserializeFromPresentationFormat($tokens[0]),
            Bitmap::deserializeFromPresentationFormat(array_slice($tokens,1),self::getMapper()),
        ]);
    }

    /**
     * @param string $data
     * @return NXT
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NXT{
        $offset = 0;

        $nextDomainName = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($nextDomainName);

        $typeBitmap = substr($data,$offset);

        return new self([
            FQDN::deserializeFromWireFormat($nextDomainName),
            Bitmap::deserializeFromWireFormat($typeBitmap),
        ]);
    }

}