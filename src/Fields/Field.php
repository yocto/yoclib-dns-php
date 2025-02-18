<?php
namespace YOCLIB\DNS\Fields;

use Serializable;

interface Field{

    public function getValue(): mixed;

    public function serializeToPresentationFormat(): string;

    public function serializeToWireFormat(): string;

    public static function deserializeFromPresentationFormat(string $data): self;

    public static function deserializeFromWireFormat(string $data): self;

}