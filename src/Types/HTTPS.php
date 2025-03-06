<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class HTTPS extends Type{

    /**
     * @param string $data
     * @return HTTPS
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): HTTPS{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return HTTPS
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): HTTPS{
        throw new \RuntimeException('Type not implemented');
    }

}