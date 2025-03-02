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
    public const RP = 17;
    public const AFSDB = 18;
    public const X25 = 19;
    public const ISDN = 20;
    public const RT = 21;
    public const NSAP = 22;
    public const NSAP_PTR = 23;
    public const SIG = 24;
    public const KEY = 25;
    public const PX = 26;
    public const GPOS = 27;
    public const AAAA = 28;
    public const LOC = 29;
    public const NXT = 30;
    public const EID = 31;
    public const NIMLOC = 32;
    public const SRV = 33;
    public const ATMA = 34;
    public const NAPTR = 35;
    public const KX = 36;
    public const CERT = 37;
    public const A6 = 38;
    public const DNAME = 39;
    public const SINK = 40;
    public const OPT = 41;
    public const APL = 42;
    public const DS = 43;
    public const SSHFP = 44;
    public const IPSECKEY = 45;
    public const RRSIG = 46;
    public const NSEC = 47;
    public const DNSKEY = 48;
    public const DHCID = 49;
    public const NSEC3 = 50;
    public const NSEC3PARAM = 51;
    public const TLSA = 52;
    public const SMIMEA = 53;

    public const HIP = 55;
    public const NINFO = 56;
    public const RKEY = 57;
    public const TALINK = 58;
    public const CDS = 59;
    public const CDNSKEY = 60;
    public const OPENPGPKEY = 61;
    public const CSYNC = 62;
    public const ZONEMD = 63;
    public const SVCB = 64;
    public const HTTPS = 65;
    public const DSYNC = 66;

    public const SPF = 99;
    public const UNINFO = 100;
    public const UID = 101;
    public const GID = 102;
    public const UNSPEC = 103;
    public const NID = 104;
    public const L32 = 105;
    public const L64 = 106;
    public const LP = 107;
    public const EUI48 = 108;
    public const EUI64 = 109;

    public const NXNAME = 128;

    public const TKEY = 249;
    public const TSIG = 250;
    public const IXFR = 251;
    public const AXFR = 252;
    public const MAILB = 253;
    public const MAILA = 254;
    public const ANY = 255;
    public const URI = 256;
    public const CAA = 257;
    public const AVC = 258;
    public const DOA = 259;
    public const AMTRELAY = 260;
    public const RESINFO = 261;
    public const WALLET = 262;
    public const CLA = 263;
    public const IPN = 264;

    public const TA = 32768;
    public const DLV = 32769;

}