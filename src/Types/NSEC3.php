<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class NSEC3 extends Type{

    /**
     * @param string $data
     * @return NSEC3
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NSEC3{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return NSEC3
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NSEC3{
        //TODO Implement
        return new self([]);
    }

}