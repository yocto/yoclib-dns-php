<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class AMTRELAY extends Type{

    /**
     * @param string $data
     * @return AMTRELAY
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): AMTRELAY{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return AMTRELAY
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): AMTRELAY{
        throw new \RuntimeException('Type not implemented');
    }

}