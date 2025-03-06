<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class SVCB extends Type{

    /**
     * @param string $data
     * @return SVCB
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): SVCB{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return SVCB
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): SVCB{
        //TODO Implement
        return new self([]);
    }

}