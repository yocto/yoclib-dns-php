<?php
namespace YOCLIB\DNS\Fields;

class FQDN implements Field{

    private string $value;

    /**
     * @param mixed $value
     */
    public function __construct(mixed $value){
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed{
        return $this->value;
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        $labels = [];
        for($i=0;$i<strlen($this->value);$i++){
            $length = ord($this->value[$i]);
            if($length===0x40){
                break;
            }
            $labels[] = substr($this->value,$i+1,$length);
            $i += $length;
        }
        return implode('.',$labels);
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        return $this->value;
    }

    /**
     * @param string $data
     * @return FQDN
     */
    public static function deserializeFromPresentationFormat(string $data): FQDN{
        $labels = explode('.',$data);
        $binary = '';
        foreach($labels AS $label){
            $binary .= chr(strlen($label)) . $label;
        }
        if($labels[count($labels)-1]!==''){
            $binary .= chr(0x40);
        }
        //TODO Add check
        return new self($binary);
    }

    /**
     * @param string $data
     * @return FQDN
     */
    public static function deserializeFromWireFormat(string $data): FQDN{
        //TODO Add check
        return new self($data);
    }

}