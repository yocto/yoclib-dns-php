<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class Binary implements Field{

    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value){
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string{
        return $this->value;
    }

    /**
     * @return string
     * @throws DNSFieldException
     */
    public function serializeToPresentationFormat(): string{
        throw new DNSFieldException('Cannot serialize binary. The presentation format is type dependent.');
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        return $this->value;
    }

    /**
     * @param string $data
     * @return int
     */
    public static function calculateLength(string $data): int{
        return strlen($data);
    }

    /**
     * @param string $data
     * @return Binary
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): Binary{
        throw new DNSFieldException('Cannot deserialize binary. The presentation format is type dependent.');
    }

    /**
     * @param string $data
     * @return Binary
     */
    public static function deserializeFromWireFormat(string $data): Binary{
        return new self($data);
    }

}