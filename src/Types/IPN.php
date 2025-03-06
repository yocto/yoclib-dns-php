<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class IPN extends Type{

    /**
     * @param string $data
     * @return IPN
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): IPN{
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return IPN
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): IPN{
        throw new \RuntimeException('Type not implemented');
    }

}