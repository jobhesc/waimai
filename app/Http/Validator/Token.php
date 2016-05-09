<?php
/**
 * Created by PhpStorm.
 * User: hesc
 * Date: 16/5/6
 * Time: 下午4:07
 */
namespace App\Http\Validator;


use App\User;

class Token{
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


    public $aud;  //发送者
    public $jti;  //JWT ID
    public $iat;  //请求的发起时间
    public $ttu;  //ticket
    public $uid;  //用户id

    /**
     * 创建token,token第一部分是ticket的json,第二部分是ticket的签名
     * @param User $user
     * @return string
     */
    public static function create(User $user){
        $token = new Token();
        $token->uid = $user->id;
        $token->ttu = $user->token;
        $token->aud = 'www.waimai.com';
        $token->jti = hash("crc32b", uniqid());
        $token->iat = time();

        $json = json_encode($token);
        $private_key_resource = openssl_get_privatekey(self::RSA_PRIVATE_KEY);

        $signature = '';
        openssl_sign($json, $signature, $private_key_resource, OPENSSL_ALGO_SHA256);
        openssl_free_key($private_key_resource);
        return base64url_encode($json).'.'.base64url_encode($signature);
    }

    public static function parse($token_string){
        if(!isset($token_string)) return null;
        $array = explode('.', $token_string, 2);
        if(count($array) !== 2) return null;

        $json = base64url_decode($array[0]);
        return json_decode($json);
    }

    private function verify($token){
        if(!isset($token)) return true;
        $uid = $token->uid;
        if(!isset($uid)) return false;

        $user = User::find($uid);
        if(!isset($user)) return false;

        $ttu = $token->ttu;
        if(!isset($ttu)) return false;

        return  $user->token == $ttu;
    }

    public function handle($attribute, $value){
        if(!isset($value)) return true;

        if(!is_array($value)){
            $array = explode('.', $value, 2);
        } else {
            $array = explode('.', $value[0], 2);
        }

        if(count($array) !== 2) return false;

        $json = base64url_decode($array[0]);
        $signature = base64url_decode($array[1]);

        $public_key_resource = openssl_get_publickey(self::RSA_PUBLIC_KEY);

        $verify = openssl_verify($json, $signature, $public_key_resource, OPENSSL_ALGO_SHA256) === 1;
        openssl_free_key($public_key_resource);

        if(!$verify) return false;

        $token = json_decode($json);
        return static::verify($token);
    }
}