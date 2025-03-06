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
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return IPN
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): IPN{
        //TODO Implement
        return new self([]);
    }

}