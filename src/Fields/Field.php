<?php
namespace YOCLIB\DNS\Fields;

interface Field{

    public function serializeToPresentationFormat(): string;

    public function serializeToWireFormat(): string;

    public static function deserializeFromPresentationFormat(string $data): self;

    public static function deserializeFromWireFormat(string $data): self;

}