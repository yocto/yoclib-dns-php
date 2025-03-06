<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class APL extends Type{

    /**
     * @param string $data
     * @return APL
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): APL{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return APL
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): APL{
        throw new \RuntimeException('Type not implemented');
    }

}