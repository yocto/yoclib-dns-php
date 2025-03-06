<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class TSIG extends Type{

    /**
     * @param string $data
     * @return TSIG
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): TSIG{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return TSIG
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): TSIG{
        throw new \RuntimeException('Type not implemented');
    }

}