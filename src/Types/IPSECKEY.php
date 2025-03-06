<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class IPSECKEY extends Type{

    /**
     * @param string $data
     * @return IPSECKEY
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): IPSECKEY{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return IPSECKEY
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): IPSECKEY{
        throw new \RuntimeException('Type not implemented');
    }

}