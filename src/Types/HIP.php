<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class HIP extends Type{

    /**
     * @param string $data
     * @return HIP
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): HIP{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return HIP
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): HIP{
        //TODO Implement
        return new self([]);
    }

}