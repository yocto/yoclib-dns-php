<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class A6 extends Type{

    /**
     * @param string $data
     * @return A6
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): A6{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return A6
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): A6{
        //TODO Implement
        return new self([]);
    }

}