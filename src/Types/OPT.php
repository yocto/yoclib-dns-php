<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class OPT extends Type{

    /**
     * @param string $data
     * @return OPT
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): OPT{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return OPT
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): OPT{
        throw new \RuntimeException('Type not implemented');
    }

}