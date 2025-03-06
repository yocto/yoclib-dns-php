<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class DSYNC extends Type{

    /**
     * @param string $data
     * @return DSYNC
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): DSYNC{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return DSYNC
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): DSYNC{
        //TODO Implement
        return new self([]);
    }

}