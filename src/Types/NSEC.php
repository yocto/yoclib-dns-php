<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\WindowBlockBitmap;
use YOCLIB\DNS\LineLexer;
use YOCLIB\DNS\MnemonicMapper;

class NSEC extends Type{

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
        if(!($fields[1] instanceof WindowBlockBitmap)){
            throw new DNSTypeException('Second field should be a window block bitmap.');
        }
    }

    /**
     * @param string $data
     * @return NSEC
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NSEC{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<2){
            throw new DNSTypeException('NSEC record should contain at least 2 fields.');
        }
        return new self([
            FQDN::deserializeFromPresentationFormat($tokens[0]),
            WindowBlockBitmap::deserializeFromPresentationFormat(array_slice($tokens,1),new MnemonicMapper(MnemonicMapper::MAPPING_DNS_TYPES)),
        ]);
    }

    /**
     * @param string $data
     * @return NSEC
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NSEC{
        $offset = 0;

        $nextDomainName = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($nextDomainName);

        $typeBitmap = substr($data,$offset);

        return new self([
            FQDN::deserializeFromWireFormat($nextDomainName),
            WindowBlockBitmap::deserializeFromWireFormat($typeBitmap),
        ]);
    }

}