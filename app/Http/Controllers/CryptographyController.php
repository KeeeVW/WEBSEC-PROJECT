<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CryptographyController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->data??"Welcome to Cryptography";
        $action = $request->action??"Encrypt";
        $result = $request->result??"";
        $status = "Failed";
        $size = 0;

        if($request->action=="Encrypt") {

            $temp = openssl_encrypt($request->data, 'aes-128-ecb', 'thisisasecretkey', OPENSSL_RAW_DATA, '');
            if($temp) {
                $status = 'Encrypted Successfully';
                $result = base64_encode($temp);
            }
        }
        else if($request->action=="Decrypt") {

            $temp = base64_decode($request->data);

            $result = openssl_decrypt($temp, 'aes-128-ecb',  'thisisasecretkey', OPENSSL_RAW_DATA, '');

            if($result) $status = 'Decrypted Successfully';
        }
        else if($request->action=="Hash") {

            $temp = hash('sha256', $request->data);

            $result = base64_encode($temp);

            $status = 'Hashed Successfully';
        }

        return view('cryptography', compact('data', 'result', 'action', 'status'));
    }
}
