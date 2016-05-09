<?php
/**
 * Created by PhpStorm.
 * User: hesc
 * Date: 16/5/6
 * Time: 下午4:07
 */
namespace App\Http\Validator;

use App;

class Signature{
    public $iss;   // clientinfo中的appnm－clientType－appVer
    public $iat;   // 请求的发起时间
    public $jti;   // JWT ID (unique)
    public $uri;   // 要请求的URI
    public $rbd;   // substr(md5(base64_URLSafe(request_body) ＋ jti), 0, 8) 将request的body按照URL安全的base64方法编码之后加上jti然后md5的值取前8位（注意，request的Content-Type将只接受application/json形式，body可以为空）
    public $ttu;   // 解析自token的claims中的ttu，未登录的情况下为空

    const RSA_PUBLIC_KEY = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDGNWE82si0rD+Btip8w360oK8L
BUL0cdxrNKzY8n2Le0t1VfyEL9Z5MUBtZ8zHZTeEZMSy88HRfTe4A3TTpIgtKmCW
6d4GeMfhZYrBBFOi/eO7mqIxSREFUDMMtWq0qO1a+PcOxq0zoa9Hu0VPHMZxthbm
4Fu4A27AToILL1lCJwIDAQAB
-----END PUBLIC KEY-----';

    const RSA_PRIVATE_KEY = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDGNWE82si0rD+Btip8w360oK8LBUL0cdxrNKzY8n2Le0t1VfyE
L9Z5MUBtZ8zHZTeEZMSy88HRfTe4A3TTpIgtKmCW6d4GeMfhZYrBBFOi/eO7mqIx
SREFUDMMtWq0qO1a+PcOxq0zoa9Hu0VPHMZxthbm4Fu4A27AToILL1lCJwIDAQAB
AoGBALgrW1mvNLTkI/JDsMDS6cWuVFdaITd/IL8gZ8cBsnPLMXcqWYL97DwZ7nJ5
84YG34oOE9LvudUMk5xQ4dnR1JD4NL5cLV4qk7Idk866nB4Ef2ulmu9+hJMCcLLM
9mb7JUQgfUdktNkWUZpOxJSoSiVk40U307jvmsgZQ9UyOtARAkEA6CZlTKnlMrg/
AhnOF9SkGwbLnghjXj1fmMsyOjWDIk+2ITLYRafqI+X9Ykg+xvVvobKXBYkS8lHH
vuW2rsN5bwJBANqSUJXATADAQamikA/RgQvpr/xN17b93Y66urAsL0MGBdiSaLuh
l1GKewyE3d4EX5ekDDVfQ5Ng0M7jkHAWtskCQErjH+hkIeiDoOe3lVEAqlOBlKuM
/ykGWVE7sx8t5fhqiFEbSsLlkNU1utA6h+28fN9Hcgo6Fp+OnAXLqmuj6QcCQCrI
UPW++iF7gF7P7xrpizTlvQjJw9uRvXhenIQ3YdjgqOxHXDC95HyVephsuXDnsj5g
YfNgfj2uybB1YqRODeECQGsl8LFhHX3vHtxtgFomgx1xSWIFYe4ugp1EFIc3t1/5
LlR6TYqLz0/WUlPjWkStbFq0UYfnIJ0vsKIZZLVbOms=
-----END RSA PRIVATE KEY-----';

    public function handle($attribute, $value, $parameters){
        if(!is_array($value)){
            $array = explode('.', $value, 2);
        } else {
            $array = explode('.', $value[0], 2);
        }

        if(count($array) !== 2) return false;

        //数组的第一段是签名,第二段是json
        $signature = $array[0];
        $claim = $array[1];

        $public_key_resource = openssl_get_publickey(self::RSA_PUBLIC_KEY);
        $public_key_details = openssl_pkey_get_details($public_key_resource);
        if (!isset($public_key_details['key']) || $public_key_details['type'] !== OPENSSL_KEYTYPE_RSA) {
            return false;
        }

        $signature = base64url_decode($signature);
        $verify = openssl_verify($claim, $signature, $public_key_resource, OPENSSL_ALGO_SHA256) === 1;
        openssl_free_key($public_key_resource);
        return $verify;
    }

    private static function parse($signature_string){
        if(!isset($signature_string)) return null;
        $array = explode('.', $signature_string, 2);
        if(count($array) !== 2) return null;

        return json_decode($array[1]);
    }

    public static function verify($signature, $uri, $body, $did, $token_string){
        $claim = self::parse($signature);
        $claim_uri = strpos($claim->uri, '/')===0?substr($claim->uri, 1):$claim->uri;
        if(isset($uri) && strcasecmp($claim_uri ,$uri)!==0) return false;
        //校验token
        if(isset($token_string)){
            $token = Token::parse($token_string);
            if($token->ttu != $claim->ttu) return false;
        }
        //校验body
        if(isset($body)){
            $rbd = strtolower(substr(md5(base64url_encode($body).$claim->jti.$did), 0, 8));
            if($claim->rbd != $rbd) return false;
        }

        return true;
    }
}