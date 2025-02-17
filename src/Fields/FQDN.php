<?php
namespace YOCLIB\DNS\Fields;

class FQDN implements Field{

    private string $value;

    public function serializeToPresentationFormat(): string{
        $labels = [];
        for($i=0;$i<strlen($this->value);$i++){
            $length = ord($this->value[$i]);
            if($length==0x40){
                break;
            }
            $labels[] = substr($this->value,$i+1,$length);
            $i += $length;
        }
        return implode('.',$labels);
    }

    public function serializeToWireFormat(): string{
        return $this->value;
    }

    public static function deserializeFromPresentationFormat(string $data): FQDN{
        $labels = explode('.',$data);
        $binary = '';
        foreach($labels AS $label){
            $binary .= chr(strlen($label)) . $label;
        }
        if($labels[count($labels)-1]!==''){
            $binary .= "\x40";
        }
        //TODO Add check
        $obj = new self;
        $obj->value = $binary;
        return $obj;
    }

    public static function deserializeFromWireFormat(string $data): FQDN{
        //TODO Add check
        $obj = new self;
        $obj->value = $data;
        return $obj;
    }

}