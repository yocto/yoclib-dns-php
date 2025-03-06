<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class SVCB extends Type{

    /**
     * @param string $data
     * @return SVCB
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): SVCB{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return SVCB
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): SVCB{
        throw new \RuntimeException('Type not implemented');
    }

}