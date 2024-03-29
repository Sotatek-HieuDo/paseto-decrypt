<?php
  include 'vendor/autoload.php';
  use ParagonIE\Paseto\Keys\Version1\AsymmetricPublicKey;
  use ParagonIE\Paseto\Keys\Version1\AsymmetricSecretKey;
  use ParagonIE\Paseto\Protocol\Version1;
  $payload = '{
  "request_created":"20190313112025";
  " channelUserId ":"UM18595";
  " idType":"N";
  " idNo":"431202105661";
  " nationality":"MY";
  " email":"mexxage@gmail.com";
  " telHP":"60167850145";
  " channel":"GOPAYZ";
  " name":"John Doe";
  " dob":"431202";
  " homeAddress1 ":"No 5 Jalan Maxwell";
  " homeAddress2 ":"Taman Majestics";
  " homeState ":"14";
  " homeZip ":"50200";
  " homeTown":"Kuala Lumpur";
  " homeCountry":"MY";
  " mailAddress1 ":"No 5 Jalan Maxwell";
  " mailAddress2 ":"Taman Majestics";
  " mailState ":"14";
  " mailZip ":"50200";
  " mailTown":"Kuala Lumpur";
  " mailCountry":"MY";
  "channelLoginToken":{"type":"Buffer","data":[4,4,208,99,104,183,169,153,12,123,186,169,192,138,253,17,37,99,241,76,211,139,88,189,217,144,86,58,184,230,109,53,209,251,36,220,249,209,75,7,86,72,61,41,7,148,91,57,73,171,155,55,229,146,65,31,227,249,75,94,128,36,12,12,90,81,81,167,170,3,50,75,240,183,25,208,27,97,158,233,127,15,133
  ,211,33,242,69,204,208,221,0,196,51,86,165,149,207,91,30,213,147,10,197,24,177,211,114,251,14,47,160,191,6,91,
  55,213,161,69,26,243,220,41,216,150,96,169,122,175,62,20,205,97,185,74,114,176,34,233,83,177,79,122,67,252,162,162,168,138,252,185,244,82,36,185,96,49,168,220,92,50,32,2,145,76,22,90,98,230,93,138,127,182,174,50,223,75,
  249,74,120,8,4,240,212,78,89,116,123,89,93,150,118,127,184,237,253,41,92,62,86,145,236,181,44,239,107,140,251
  ,19,110,162,66,152,14,26,242,183,115,212,251,12,160,106,178,220,149,130,47,87,249,1,114,148,26,155,111,224,228,190,194,145,69,111,41,26,176,229,255,255,164,41,45,185,178,30,170,147]};
  "partnerId":"PTC";
  }';
  function encryptPaseto($payload) {
    $rsa = Version1::getRsa();
    $keypair = $rsa->createKey(2048);
    $privateKey = new AsymmetricSecretKey($keypair['privatekey'], new Version1);
    $publicKey = new AsymmetricPublicKey($keypair['publickey'], new Version1);
    $token = Version1::sign($payload, $privateKey);
    return json_encode(["publicKey" => $keypair['publickey'], "token" => $token]);
  }

  function checkPASETOSignature($token, $publicKey) {

      $publicKey = new AsymmetricPublicKey($publicKey, new Version1);
      try {
        $decode = Version1::verify($token, $publicKey);
        $decode = str_replace(";", ",", $decode);
        $decode = substr($decode, 0, -1);
        $decode = rtrim(trim($decode), ",")."}";
        return json_encode(json_decode($decode));
      }
      catch(Exception $err) {
        return json_encode(["error" => $err->getMessage()]);
      }
  };
  //Test encrypt and decrypt function
  $encryted = json_decode(encryptPaseto($payload));
  print_r(checkPASETOSignature($encryted->token, $encryted->publicKey));

  //Test decrypt function
  $signed = "v1.public.ewoicmVxdWVzdF9jcmVhdGVkIjoiMjAxOTAzMTMxMTIwMjUiOwoiIGNoYW5uZWxVc2VySWQgIjoiVU0xODU5NSI7CiIgaWRUeXBlIjoiTiI7CiIgaWRObyI6IjQzMTIwMjEwNTY2MSI7CiIgbmF0aW9uYWxpdHkiOiJNWSI7CiIgZW1haWwiOiJtZXh4YWdlQGdtYWlsLmNvbSI7CiIgdGVsSFAiOiI2MDE2Nzg1MDE0NSI7CiIgY2hhbm5lbCI6IkdPUEFZWiI7CiIgbmFtZSI6IkpvaG4gRG9lIjsKIiBkb2IiOiI0MzEyMDIiOwoiIGhvbWVBZGRyZXNzMSAiOiJObyA1IEphbGFuIE1heHdlbGwiOwoiIGhvbWVBZGRyZXNzMiAiOiJUYW1hbiBNYWplc3RpY3MiOwoiIGhvbWVTdGF0ZSAiOiIxNCI7CiIgaG9tZVppcCAiOiI1MDIwMCI7CiIgaG9tZVRvd24iOiJLdWFsYSBMdW1wdXIiOwoiIGhvbWVDb3VudHJ5IjoiTVkiOwoiIG1haWxBZGRyZXNzMSAiOiJObyA1IEphbGFuIE1heHdlbGwiOwoiIG1haWxBZGRyZXNzMiAiOiJUYW1hbiBNYWplc3RpY3MiOwoiIG1haWxTdGF0ZSAiOiIxNCI7CiIgbWFpbFppcCAiOiI1MDIwMCI7CiIgbWFpbFRvd24iOiJLdWFsYSBMdW1wdXIiOwoiIG1haWxDb3VudHJ5IjoiTVkiOwoiY2hhbm5lbExvZ2luVG9rZW4iOnsidHlwZSI6IkJ1ZmZlciIsImRhdGEiOls0LDQsMjA4LDk5LDEwNCwxODMsMTY5LDE1MywxMiwxMjMsMTg2LDE2OSwxOTIsMTM4LDI1MywxNywzNyw5OSwyNDEsNzYsMjExLDEzOSw4OCwxODksMjE3LDE0NCw4Niw1OCwxODQsMjMwLDEwOSw1MywyMDksMjUxLDM2LDIyMCwyNDksMjA5LDc1LDcsODYsNzIsNjEsNDEsNywxNDgsOTEsNTcsNzMsMTcxLDE1NSw1NSwyMjksMTQ2LDY1LDMxLDIyNywyNDksNzUsOTQsMTI4LDM2LDEyLDEyLDkwLDgxLDgxLDE2NywxNzAsMyw1MCw3NSwyNDAsMTgzLDI1LDIwOCwyNyw5NywxNTgsMjMzLDEyNywxNSwxMzMKLDIxMSwzMywyNDIsNjksMjA0LDIwOCwyMjEsMCwxOTYsNTEsODYsMTY1LDE0OSwyMDcsOTEsMzAsMjEzLDE0NywxMCwxOTcsMjQsMTc3LDIxMSwxMTQsMjUxLDE0LDQ3LDE2MCwxOTEsNiw5MSwKNTUsMjEzLDE2MSw2OSwyNiwyNDMsMjIwLDQxLDIxNiwxNTAsOTYsMTY5LDEyMiwxNzUsNjIsMjAsMjA1LDk3LDE4NSw3NCwxMTQsMTc2LDM0LDIzMyw4MywxNzcsNzksMTIyLDY3LDI1MiwxNjIsMTYyLDE2OCwxMzgsMjUyLDE4NSwyNDQsODIsMzYsMTg1LDk2LDQ5LDE2OCwyMjAsOTIsNTAsMzIsMiwxNDUsNzYsMjIsOTAsOTgsMjMwLDkzLDEzOCwxMjcsMTgyLDE3NCw1MCwyMjMsNzUsCjI0OSw3NCwxMjAsOCw0LDI0MCwyMTIsNzgsODksMTE2LDEyMyw4OSw5MywxNTAsMTE4LDEyNywxODQsMjM3LDI1Myw0MSw5Miw2Miw4NiwxNDUsMjM2LDE4MSw0NCwyMzksMTA3LDE0MCwyNTEKLDE5LDExMCwxNjIsNjYsMTUyLDE0LDI2LDI0MiwxODMsMTE1LDIxMiwyNTEsMTIsMTYwLDEwNiwxNzgsMjIwLDE0OSwxMzAsNDcsODcsMjQ5LDEsMTE0LDE0OCwyNiwxNTUsMTExLDIyNCwyMjgsMTkwLDE5NCwxNDUsNjksMTExLDQxLDI2LDE3NiwyMjksMjU1LDI1NSwxNjQsNDEsNDUsMTg1LDE3OCwzMCwxNzAsMTQ3XX07CiJwYXJ0bmVySWQiOiJQVEMiOwogIH1UzrCXEtcnYGjMH6r6XlWT5qpppd7UiGYB2QOUM4vzPefjZk4dOAaXF5Yqt_aJl_dFyyR7ILinNLFZkp-_BJ2TlYHACRqHP2mEr2y9rGxVRoeWXxzPGVZyFZtvApcZQPGLog8qEZcCcFysNYhLZUR0XJHkuo3UYSfS1EtqWnFzuaQar7Q3yAE70WkBBV74NNMN7wY2y6vl95XNKbe6mhTZy5fHaXZGppLG1Zd7e8RFIw8UO63ZFju3tDUlKkLstxB5zLV5U-nVTH5BsfbJz86PZ-o_LiASx-AUMSyY0BOQUnau44issTMBjLVkPYe-dCgfMgS4T5Et8ly5TmbCvEDP";
  $publicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAumrowJWPl0sJO4eeQRau
  B5UdluPFRPzF+X8gydQn5UoyS4OxjHLqZQPv3cSupdr7k8aeJ4dT473Bc3f08Nlo
  QUqsijZ9eQCER0MDL1+3oSQEXfSAYzYxXjI66pDNV4/k9injv3IyKuyVsDf9fx2Y
  2Gu9y9HNrZ+13bu3Tg75qYCzI4BFSFUfH0eYHbwRCj0TBmva8P07rGIrM3RUzN00
  LmywVPb9rv2caDHLv7gtvCkVyr6Db6Igc1zo2Ga4Pzj36L9COLqWrLTcW2Fr41MX
  4vC46P6/AQCDBp9qFQrbN6okrjZWBQ0tPe/lCmiinlZ+gjo9CWRIwidSfNTTeUpr
  WwIDAQAB";

  //print_r (checkPASETOSignature($signed, $publicKey));
  /*
    Return data:
    {"request_created":"20190313112025"," channelUserId ":"UM18595"," idType":"N"," idNo":"431202105661"," nationality":"MY"," email":"mexxage@gmail.com"," telHP":"60167850145"," channel":"GOPAYZ"," name":"John Doe"," dob":"431202"," homeAddress1 ":"No 5 Jalan Maxwell"," homeAddress2 ":"Taman Majestics"," homeState ":"14"," homeZip ":"50200"," homeTown":"Kuala Lumpur"," homeCountry":"MY"," mailAddress1 ":"No 5 Jalan Maxwell"," mailAddress2 ":"Taman Majestics"," mailState ":"14"," mailZip ":"50200"," mailTown":"Kuala Lumpur"," mailCountry":"MY","channelLoginToken":{"type":"Buffer","data":[4,4,208,99,104,183,169,153,12,123,186,169,192,138,253,17,37,99,241,76,211,139,88,189,217,144,86,58,184,230,109,53,209,251,36,220,249,209,75,7,86,72,61,41,7,148,91,57,73,171,155,55,229,146,65,31,227,249,75,94,128,36,12,12,90,81,81,167,170,3,50,75,240,183,25,208,27,97,158,233,127,15,133,211,33,242,69,204,208,221,0,196,51,86,165,149,207,91,30,213,147,10,197,24,177,211,114,251,14,47,160,191,6,91,55,213,161,69,26,243,220,41,216,150,96,169,122,175,62,20,205,97,185,74,114,176,34,233,83,177,79,122,67,252,162,162,168,138,252,185,244,82,36,185,96,49,168,220,92,50,32,2,145,76,22,90,98,230,93,138,127,182,174,50,223,75,249,74,120,8,4,240,212,78,89,116,123,89,93,150,118,127,184,237,253,41,92,62,86,145,236,181,44,239,107,140,251,19,110,162,66,152,14,26,242,183,115,212,251,12,160,106,178,220,149,130,47,87,249,1,114,148,26,155,111,224,228,190,194,145,69,111,41,26,176,229,255,255,164,41,45,185,178,30,170,147]},"partnerId":"PTC"}
  */
  $signed = "v1.public.ewoicmVxdWVzdF9jcmVhdGVkIjoiMjAxOTAzMTMxMTIwMjUiOwoiIGNoYW5uZWxVc2VySWQgIjoiVU0xODU5NSI7CiIgaWRUeXBlIjoiTiI7CiIgaWRObyI6IjQzMTIwMjEwNTY2MSI7CiIgbmF0aW9uYWxpdHkiOiJNWSI7CiIgZW1haWwiOiJtZXh4YWdlQGdtYWlsLmNvbSI7CiIgdGVsSFAiOiI2MDE2Nzg1MDE0NSI7CiIgY2hhbm5lbCI6IkdPUEFZWiI7CiIgbmFtZSI6IkpvaG4gRG9lIjsKIiBkb2IiOiI0MzEyMDIiOwoiIGhvbWVBZGRyZXNzMSAiOiJObyA1IEphbGFuIE1heHdlbGwiOwoiIGhvbWVBZGRyZXNzMiAiOiJUYW1hbiBNYWplc3RpY3MiOwoiIGhvbWVTdGF0ZSAiOiIxNCI7CiIgaG9tZVppcCAiOiI1MDIwMCI7CiIgaG9tZVRvd24iOiJLdWFsYSBMdW1wdXIiOwoiIGhvbWVDb3VudHJ5IjoiTVkiOwoiIG1haWxBZGRyZXNzMSAiOiJObyA1IEphbGFuIE1heHdlbGwiOwoiIG1haWxBZGRyZXNzMiAiOiJUYW1hbiBNYWplc3RpY3MiOwoiIG1haWxTdGF0ZSAiOiIxNCI7CiIgbWFpbFppcCAiOiI1MDIwMCI7CiIgbWFpbFRvd24iOiJLdWFsYSBMdW1wdXIiOwoiIG1haWxDb3VudHJ5IjoiTVkiOwoiY2hhbm5lbExvZ2luVG9rZW4iOnsidHlwZSI6IkJ1ZmZlciIsImRhdGEiOls0LDQsMjA4LDk5LDEwNCwxODMsMTY5LDE1MywxMiwxMjMsMTg2LDE2OSwxOTIsMTM4LDI1MywxNywzNyw5OSwyNDEsNzYsMjExLDEzOSw4OCwxODksMjE3LDE0NCw4Niw1OCwxODQsMjMwLDEwOSw1MywyMDksMjUxLDM2LDIyMCwyNDksMjA5LDc1LDcsODYsNzIsNjEsNDEsNywxNDgsOTEsNTcsNzMsMTcxLDE1NSw1NSwyMjksMTQ2LDY1LDMxLDIyNywyNDksNzUsOTQsMTI4LDM2LDEyLDEyLDkwLDgxLDgxLDE2NywxNzAsMyw1MCw3NSwyNDAsMTgzLDI1LDIwOCwyNyw5NywxNTgsMjMzLDEyNywxNSwxMzMKLDIxMSwzMywyNDIsNjksMjA0LDIwOCwyMjEsMCwxOTYsNTEsODYsMTY1LDE0OSwyMDcsOTEsMzAsMjEzLDE0NywxMCwxOTcsMjQsMTc3LDIxMSwxMTQsMjUxLDE0LDQ3LDE2MCwxOTEsNiw5MSwKNTUsMjEzLDE2MSw2OSwyNiwyNDMsMjIwLDQxLDIxNiwxNTAsOTYsMTY5LDEyMiwxNzUsNjIsMjAsMjA1LDk3LDE4NSw3NCwxMTQsMTc2LDM0LDIzMyw4MywxNzcsNzksMTIyLDY3LDI1MiwxNjIsMTYyLDE2OCwxMzgsMjUyLDE4NSwyNDQsODIsMzYsMTg1LDk2LDQ5LDE2OCwyMjAsOTIsNTAsMzIsMiwxNDUsNzYsMjIsOTAsOTgsMjMwLDkzLDEzOCwxMjcsMTgyLDE3NCw1MCwyMjMsNzUsCjI0OSw3NCwxMjAsOCw0LDI0MCwyMTIsNzgsODksMTE2LDEyMyw4OSw5MywxNTAsMTE4LDEyNywxODQsMjM3LDI1Myw0MSw5Miw2Miw4NiwxNDUsMjM2LDE4MSw0NCwyMzksMTA3LDE0MCwyNTEKLDE5LDExMCwxNjIsNjYsMTUyLDE0LDI2LDI0MiwxODMsMTE1LDIxMiwyNTEsMTIsMTYwLDEwNiwxNzgsMjIwLDE0OSwxMzAsNDcsODcsMjQ5LDEsMTE0LDE0OCwyNiwxNTUsMTExLDIyNCwyMjgsMTkwLDE5NCwxNDUsNjksMTExLDQxLDI2LDE3NiwyMjksMjU1LDI1NSwxNjQsNDEsNDUsMTg1LDE3OCwzMCwxNzAsMTQ3XX07CiJwYXJ0bmVySWQiOiJQVEMiOwogIH1UzrCXEtcnYGjMH6r6XlWT5qpppd7UiGYB2QOUM4vzPefjZk4dOAaXF5Yqt_aJl_dFyyR7ILinNLFZkp-_BJ2TlYHACRqHP2mEr2y9rGxVRoeWXxzPGVZyFZtvApcZQPGLog8qEZcCcFysNYhLZUR0XJHkuo3UYSfS1EtqWnFzuaQar7Q3yAE70WkBBV74NNMN7wY2y6vl95XNKbe6mhTZy5fHaXZGppLG1Zd7e8RFIw8UO63ZFju3tDUlKkLstxB5zLV5U-nVTH5BsfbJz86PZ-o_LiASx-AUMSyY0BOQUnau44issTMBjLVkPYe-dCgfMgS4T5Et8ly5TmbCvEDP";
  $publicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8MIIBCgKCAQEAumrowJWPl0sJO4eeQRau
  B5UdluPFRPzF+X8gydQn5UoyS4OxjHLqZQPv3cSupdr7k8aeJ4dT473Bc3f08Nlo
  QUqsijZ9eQCER0MDL1+3oSQEXfSAYzYxXjI66pDNV4/k9injv3IyKuyVsDf9fx2Y
  2Gu9y9HNrZ+13bu3Tg75qYCzI4BFSFUfH0eYHbwRCj0TBmva8P07rGIrM3RUzN00
  LmywVPb9rv2caDHLv7gtvCkVyr6Db6Igc1zo2Ga4Pzj36L9COLqWrLTcW2Fr41MX
  4vC46P6/AQCDBp9qFQrbN6okrjZWBQ0tPe/lCmiinlZ+gjo9CWRIwidSfNTTeUpr
  WwIDAQAB";

  //print_r (checkPASETOSignature($signed, $publicKey));
  /*$
      Return data:
      {"error":"Invalid signature for this message"}
  */
