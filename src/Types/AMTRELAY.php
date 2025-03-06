<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;

class AMTRELAY extends Type{

    /**
     * @param string $data
     * @return AMTRELAY
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): AMTRELAY{
        //TODO Implement
        return new self([]);
    }

    /**
     * @param string $data
     * @return AMTRELAY
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): AMTRELAY{
        //TODO Implement
        return new self([]);
    }

}