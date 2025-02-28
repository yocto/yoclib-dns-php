<?php
namespace YOCLIB\DNS\Fields;

interface Field{

    public function getValue(): mixed;

    public function serializeToPresentationFormat(): string;

    public function serializeToWireFormat(): string;

    public static function calculateLength(string $data): int;

    public static function deserializeFromPresentationFormat(string $data): self;

    public static function deserializeFromWireFormat(string $data): self;

}