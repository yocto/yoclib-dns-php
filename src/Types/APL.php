<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class APL extends Type{

    /**
     * @param string $data
     * @return APL
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): APL{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return APL
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): APL{
        //TODO Implement
        return new self([]);
    }

}