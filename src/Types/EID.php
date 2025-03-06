<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class EID extends Type{

    /**
     * @param string $data
     * @return EID
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): EID{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return EID
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): EID{
        //TODO Implement
        return new self([]);
    }

}