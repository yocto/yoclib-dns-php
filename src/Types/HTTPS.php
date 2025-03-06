<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class HTTPS extends Type{

    /**
     * @param string $data
     * @return HTTPS
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): HTTPS{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return HTTPS
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): HTTPS{
        //TODO Implement
        return new self([]);
    }

}