<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;

abstract class Type{

    private array $fields;

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        foreach($fields as $field){
            if(!($field instanceof Field)){
                throw new DNSTypeException('Array should only contain field types.');
            }
        }
        $this->fields = $fields;
    }

    /**
     * @return array|Field[]
     */
    public function getFields(): array{
        return $this->fields;
    }

    /**
     * @return bool
     */
    public function hasFQDNs(): bool{
        foreach($this->fields as $field){
            if($field instanceof FQDN){
                return true;
            }
        }
        return false;
    }

    /**
     * @param FQDN $origin
     * @param ?bool|null $ignoreCurrentState
     * @return self
     * @throws DNSFieldException
     */
    public function makeAbsolute(FQDN $origin,?bool $ignoreCurrentState=false): self{
        if(!$this->hasFQDNs()){
            return $this;
        }
        $newFields = [];
        foreach($this->getFields() as $field){
            if($field instanceof FQDN){
                $newFields[] = $field->makeAbsolute($origin,$ignoreCurrentState);
                continue;
            }
            $newFields[] = $field;
        }
        /**@var Type $typeObj*/
        return new (self::class)($newFields);
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        $output = [];
        foreach($this->fields as $field){
            if($field instanceof Bitmap){
                $mapping = null;
                if(method_exists($this,'getMapping')){
                    $mapping = $this->getMapping();
                }
                $output[] = $field->serializeToPresentationFormat($mapping);
                continue;
            }
            $output[] = $field->serializeToPresentationFormat();
        }
        return implode(' ',$output);
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        $output = [];
        foreach($this->fields as $field){
            $output[] = $field->serializeToWireFormat();
        }
        return implode('',$output);
    }

    public abstract static function deserializeFromPresentationFormat(string $data): self;

    public abstract static function deserializeFromWireFormat(string $data): self;

}