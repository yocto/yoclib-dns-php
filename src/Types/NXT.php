<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class NXT extends Type{

    /**
     * @param string $data
     * @return NXT
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NXT{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return NXT
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NXT{
        throw new \RuntimeException('Type not implemented');
    }

}