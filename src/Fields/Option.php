<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class Option implements Field{

    private int $valueCode;
    private string $valueData;

    /**
     * @param int $code
     * @param string $data
     * @throws DNSFieldException
     */
    public function __construct(int $code,string $data){
        if($code<0 || $code>65535){
            throw new DNSFieldException('Option code should be between 0 and 65535.');
        }
        $this->valueCode = $code;
        $this->valueData = $data;
    }

    /**
     * @return array
     */
    public function getValue(): array{
        return [$this->valueCode,$this->valueData];
    }

    /**
     * @return string
     * @throws DNSFieldException
     */
    public function serializeToPresentationFormat(): string{
        throw new DNSFieldException('Cannot serialize option.');
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        return pack('n',$this->valueCode).pack('n',strlen($this->valueData)).$this->valueData;
    }

    /**
     * @param string $data
     * @return int
     */
    public static function calculateLength(string $data): int{
        return 4 + unpack('n',str_pad(substr($data,2,2),2,"\x00",STR_PAD_LEFT))[1];
    }

    /**
     * @param string $data
     * @return Option
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): Option{
        throw new DNSFieldException('Cannot deserialize option.');
    }

    /**
     * @param string $data
     * @return Option
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): Option{
        if(strlen($data)<4){
            throw new DNSFieldException('Option should be at least 4 octets.');
        }

        $code = unpack('n',substr($data,0,2))[1];
        $length = unpack('n',substr($data,2,2))[1];

        $data = substr($data,4,$length);
        if(strlen($data)!==$length){
            throw new DNSFieldException('Too less data available to read option.');
        }

        return new self($code,$data);
    }

}