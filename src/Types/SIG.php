<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class SIG extends Type{

    /**
     * @param string $data
     * @return SIG
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): SIG{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return SIG
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): SIG{
        //TODO Implement
        return new self([]);
    }

}