<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class ATMA extends Type{

    /**
     * @param string $data
     * @return ATMA
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): ATMA{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return ATMA
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): ATMA{
        //TODO Implement
        return new self([]);
    }

}