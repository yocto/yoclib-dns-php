<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class NSEC extends Type{

    /**
     * @param string $data
     * @return NSEC
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NSEC{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return NSEC
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NSEC{
        throw new \RuntimeException('Type not implemented');
    }

}