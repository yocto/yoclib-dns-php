<?php
namespace YOCLIB\DNS;

class LineLexer{

    /**
     * @param string $line
     * @return array|string[]
     */
    public static function tokenizeLine(string $line): array{
        $tokens = [];
        $token = '';
        $isEscaped = false;
        $isQuotes = false;
        for($i=0;$i<strlen($line);$i++){
            $c = $line[$i];
            if(!$isEscaped && $c==='"'){
                $isQuotes = !$isQuotes;
            }
            if($isEscaped){
                $isEscaped = false;
            }
            if(!$isQuotes && preg_match('/\s/',$c)){
                if($token!==''){
                    $tokens[] = $token;
                    $token = '';
                }
                continue;
            }
            if(!$isEscaped && $c==='\\'){
                $isEscaped = true;
            }
            $token .= $c;
        }
        $tokens[] = $token;
        return array_filter($tokens);
    }

}