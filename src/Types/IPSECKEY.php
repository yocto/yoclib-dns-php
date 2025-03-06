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
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return IPSECKEY
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): IPSECKEY{
        //TODO Implement
        return new self([]);
    }

}