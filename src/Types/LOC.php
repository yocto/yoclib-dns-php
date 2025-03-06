<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class LOC extends Type{

    /**
     * @param string $data
     * @return LOC
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): LOC{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return LOC
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): LOC{
        //TODO Implement
        return new self([]);
    }

}