<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class LOC extends Type{

    /**
     * @param string $data
     * @return LOC
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): LOC{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return LOC
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): LOC{
        throw new \RuntimeException('Type not implemented');
    }

}