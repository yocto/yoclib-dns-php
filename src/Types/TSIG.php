<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class TSIG extends Type{

    /**
     * @param string $data
     * @return TSIG
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): TSIG{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return TSIG
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): TSIG{
        //TODO Implement
        return new self([]);
    }

}