<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class EID extends Type{

    /**
     * @param string $data
     * @return EID
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): EID{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return EID
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): EID{
        throw new \RuntimeException('Type not implemented');
    }

}