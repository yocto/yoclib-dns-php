<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class HIP extends Type{

    /**
     * @param string $data
     * @return HIP
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): HIP{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return HIP
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): HIP{
        throw new \RuntimeException('Type not implemented');
    }

}