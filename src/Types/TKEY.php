<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class TKEY extends Type{

    /**
     * @param string $data
     * @return TKEY
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): TKEY{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return TKEY
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): TKEY{
        throw new \RuntimeException('Type not implemented');
    }

}