<?php
namespace YOCLIB\DNS;

class DNSType{

    public const A = 1;
    public const NS = 2;
    public const MD = 3;
    public const MF = 4;
    public const CNAME = 5;
    public const SOA = 6;
    public const MB = 7;
    public const MG = 8;
    public const MR = 9;
    public const NULL = 10;
    public const WKS = 11;
    public const PTR = 12;
    public const HINFO = 13;
    public const MINFO = 14;
    public const MX = 15;
    public const TXT = 16;

    public const AAAA = 28;

    public const SRV = 33;

    public const DS = 43;

    public const RRSIG = 46;
    public const NSEC = 47;
    public const DNSKEY = 48;

    public const NSEC3 = 50;
    public const NSEC3PARAM = 51;

    public const SPF = 99;

    public const CAA = 257;

}