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
        return ($this->value[count($this->value)-1] ?? null)==='';
    }

    /**
     * @return bool
     */
    public function isApex(): bool{
        return count($this->value)===0;
    }

    /**
     * @return bool
     */
    public function isRelative(): bool{
        return !$this->isAbsolute();
    }

    /**
     * @param FQDN $origin
     * @param ?bool|null $ignoreCurrentState
     * @return FQDN
     * @throws DNSFieldException
     */
    public function makeAbsolute(FQDN $origin,?bool $ignoreCurrentState=false): FQDN{
        if($origin->isRelative()){
            throw new DNSFieldException('Origin FQDN cannot be relative.');
        }
        if($this->isAbsolute()){
            if($ignoreCurrentState){
                return $this;
            }
            throw new DNSFieldException("FQDN already absolute.");
        }
        return new self(...array_merge($this->value,$origin->value));
    }

    /**
     * @param FQDN $origin
     * @param ?bool|null $ignoreCurrentState
     * @return FQDN
     * @throws DNSFieldException
     */
    public function makeRelative(FQDN $origin,?bool $ignoreCurrentState=false): FQDN{
        if($origin->isRelative()){
            throw new DNSFieldException('Origin FQDN cannot be relative.');
        }
        if($this->isRelative()){
            if($ignoreCurrentState){
                return $this;
            }
            throw new DNSFieldException("FQDN already relative.");
        }
        for($i=0;$i<count($origin->value);$i++){
            if(($origin->value[count($origin->value)-$i-1] ?? null)!==($this->value[count($this->value)-$i-1] ?? null)){
                if($ignoreCurrentState){
                    return $this;
                }
                throw new DNSFieldException("FQDN is not subordinate to origin.");
            }
        }
        return new self(...array_slice($this->value,0,count($this->value)-count($origin->value)));
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        if($this->isApex()){
            return '@';
        }
        return implode('.',array_map(static function($label){
            return str_replace(['@','.'],['\@','\.'],$label);
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
        if($this->isRelative()){
            $output .= chr(0x40);
        }
        return $output;
    }

    /**
     * @param string $data
     * @return int
     */
    public static function calculateLength(string $data): int{
        $totalLength = 0;
        for($i=0;$i<strlen($data);){
            $length = ord($data[$i]);
            $i++;
            $totalLength++;
            if($length===0x40){
                break;
            }
            $i += $length;
            $totalLength += $length;
            if($length===0x00){
                break;
            }
        }
        return $totalLength;
    }

    /**
     * @param FQDN $a
     * @param FQDN $b
     * @return int
     */
    public static function compare(FQDN $a,FQDN $b): int{
        if($a===$b){
            return 0;
        }
        $labelsA = $a->getValue();
        $labelsB = $b->getValue();

        $compares = min(count($labelsA),count($labelsB));
        for($i=1;$i<=$compares;$i++){
            $startA = count($labelsA) - $i;
            $startB = count($labelsB) - $i;

            $labelA = $labelsA[$startA];
            $labelB = $labelsB[$startB];

            for($j=0;($j<strlen($labelA) && $j<strlen($labelB));$j++){
                $diff = strcmp($labelA[$j],$labelB[$j]);
                if($diff!=0){
                    return $diff;
                }
            }

            if(strlen($labelA)!=strlen($labelB)){
                return strlen($labelA) - strlen($labelB);
            }
        }
        return count($labelsA) - count($labelsB);
    }

    /**
     * @param string $data
     * @return FQDN
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): FQDN{
        $labels = preg_split('/(?<!\\\\)\\./',$data);
        if(count($labels)===1 && ($labels[0] ?? null)==='@'){
            return new self();
        }
        foreach($labels AS &$label){
            if($label==='@'){
                throw new DNSFieldException('At-sign cannot appear without backslash when having multiple labels.');
            }
            $label = str_replace(['\@','\.'],['@','.'],$label);
        }
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
            if($length===0x00){
                break;
            }
            $i += $length;
        }
        return new self(...$labels);
    }

}