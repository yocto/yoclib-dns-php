<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class NIMLOC extends Type{

    /**
     * @param string $data
     * @return NIMLOC
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NIMLOC{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return NIMLOC
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NIMLOC{
        throw new \RuntimeException('Type not implemented');
    }

}