<?php
namespace YOCLIB\DNS\Fields;

class Bitmap implements Field{

    private string $value;

    /**
     * @param ?array|null $mapping
     * @return string
     */
    public function serializeToPresentationFormat(?array $mapping=null): string{
        $items = [];
        //TODO Implement
        return implode(' ',$items);
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        return $this->value;
    }

    /**
     * @param string $data
     * @param ?array|null $mapping
     * @return Bitmap
     */
    public static function deserializeFromPresentationFormat(string $data,?array $mapping=null): Bitmap{
        $obj = new self;
        //TODO Implement
        return $obj;
    }

    /**
     * @param string $data
     * @return Bitmap
     */
    public static function deserializeFromWireFormat(string $data): Bitmap{
        $obj = new self;
        $obj->value = $data;
        return $obj;
    }

}