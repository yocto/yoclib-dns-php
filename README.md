# yocLib - DNS (PHP)

This yocLibrary enables your project to do DNS-specific operations.

## Status

[![PHP Composer](https://github.com/yocto/yoclib-dns-php/actions/workflows/php.yml/badge.svg)](https://github.com/yocto/yoclib-dns-php/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/yocto/yoclib-dns-php/graph/badge.svg)](https://codecov.io/gh/yocto/yoclib-dns-php)

## Installation

`composer require yocto/yoclib-dns`

## Usage

### Deserializing

```php
use YOCLIB\DNS\Helpers\TypeHelper;

// Deserialize from presentation format
$aRecord = TypeHelper::deserializeFromPresentationFormatByClassAndType('127.0.0.1',DNSClass::IN,DNSType::A);

// Deserialize from wire format
$aRecord = TypeHelper::deserializeFromWireFormatByClassAndType("\x7F\x00\x00\x01",DNSClass::IN,DNSType::A);
```

### Serializing

```php
use YOCLIB\DNS\Types\A;

$aRecord = new A('127.0.0.1');

// Serialize to presentation format
$aRecord->serializeToPresentationFormat();

// Serialize to wire format
$aRecord->serializeToWireFormat();
```