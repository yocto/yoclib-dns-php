<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class NSEC extends Type{

    /**
     * @param string $data
     * @return NSEC
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NSEC{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return NSEC
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NSEC{
        //TODO Implement
        return new self([]);
    }

}