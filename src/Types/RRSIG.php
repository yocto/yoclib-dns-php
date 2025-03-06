<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class RRSIG extends Type{

    /**
     * @param string $data
     * @return RRSIG
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): RRSIG{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return RRSIG
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): RRSIG{
        throw new \RuntimeException('Type not implemented');
    }

}