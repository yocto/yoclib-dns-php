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
        throw new \RuntimeException('Type not implemented');
    }

    /**
     * @param string $data
     * @return DSYNC
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): DSYNC{
        throw new \RuntimeException('Type not implemented');
    }

}