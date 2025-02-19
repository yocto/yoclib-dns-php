<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;

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
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        $output = [];
        foreach($this->fields as $field){
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

}