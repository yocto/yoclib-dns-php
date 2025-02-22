<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class FQDN implements Field{

    private array $value;

    /**
     * @param array|string[] $value
     * @throws DNSFieldException
     */
    public function __construct(string... $value){
        $totalLength = 0;
        foreach($value AS $label){
            if(strlen($label)>=64){
                throw new DNSFieldException('Label too long.');
            }
            $totalLength += 1 + strlen($label);
        }
        if($totalLength>=256){
            throw new DNSFieldException('Domain name too long.');
        }
        $this->value = $value;
    }

    /**
     * @return array|string[]
     */
    public function getValue(): array{
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isAbsolute(): bool{
        return $this->value[count($this->value)-1]==='';
    }

    /**
     * @return bool
     */
    public function isRelative(): bool{
        return !$this->isAbsolute();
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return implode('.',array_map(static function($label){
            return str_replace('.','\.',$label);
        },$this->value));
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        $output = '';
        foreach($this->value AS $label){
            $output .= chr(strlen($label)).$label;
        }
        if($this->value[count($this->value)-1]!==''){
            $output .= chr(0x40);
        }
        return $output;
    }

    /**
     * @param string $data
     * @return FQDN
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): FQDN{
        $labels = preg_split('/(?<!\\\\)\\./',$data);
        return new self(...$labels);
    }

    /**
     * @param string $data
     * @return FQDN
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): FQDN{
        $labels = [];
        for($i=0;$i<strlen($data);$i++){
            $length = ord($data[$i]);
            if($length===0x40){
                break;
            }
            $labels[] = substr($data,$i+1,$length);
            $i += $length;
        }
        return new self(...$labels);
    }

}