<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\LineLexer;

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

    protected static function getMapping(): array{
        //TODO Add mapping
        return [
            DNSType::A => 'A',
        ];
    }

    /**
     * @param string $data
     * @return NXT
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NXT{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<2){
            throw new DNSTypeException('NXT record should contain at least 2 fields.');
        }
        return new self([
            FQDN::deserializeFromPresentationFormat($tokens[0]),
            Bitmap::deserializeFromPresentationFormat(implode(' ',array_slice($tokens,1)),self::getMapping()),
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