<?php
  include 'vendor/autoload.php';
  use ParagonIE\Paseto\Keys\Version1\AsymmetricPublicKey;
  use ParagonIE\Paseto\Protocol\Version1;

  function checkPASETOSignature($token, $publicKey) {
      $publicKey = new AsymmetricPublicKey($publicKey, new Version1);
      try {
        $decode = Version1::verify($token, $publicKey);
        return json_decode($decode);
      }
      catch(Exception $err) {
        return $err;
      }
  };

  //Testing
  $exampleToken = "v1.public.eyJlbWFpbCI6ImhpZXUuZG9Ac290YXRlay5jb20ifROx2esT-3PBKhg-BioAW6HZ7uCh6C6KF_k0HfjppnqzDfWf-dk87AiVujZydlGycg5He04aJ-YsYyIITRMZMwSJpYJoTquy1Rv_vOTOzJH-poXHJiSjzzfvumP4JYkt9V9OplHoKwQwdTZlmuYDHP1pxEKZCQo5BATojj-2oXZOf3SclP8NzrTolLd_yJl3xYeSk1zgc9CEbeKFCeZboRZxJJtjdJ1Rz4kWnzJDBT-iBtFvI8ptOMXLRAX7xhQ8pFK_yN8zyLMr0ZA-ubbYXa5ZC0UTmFaVHFvprGFc5E1nfFcYWGr66pO9FLnNEyAWsBM-Iux8eOBPC6u90H0WwEE";
  $examplePublicKey = "
  MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsihehxGmOL55OHROFseo
  hj2ktSNc8QraoC/de/o+DY+PFOkWm9TdfNqAk/Utt+zGVHTmP7bbzwRZGUrIsywX
  /nBopPdi77tXPqPpXTo5WiC+5SWMqsgkeffy4RvhwHW9McaJT09MED97W3knlILb
  lp29vzimmmhJNqQYhaI0zcgCxngekwxx/pkBNeecN0lClTcawxcXvw6GJhFv3Tpn
  Q0wand+4PIOb3ozdtaMv/tQgFXqS4hJok3PW0aU5YWoiN4/CrWUz3VOdcqv7Yt/f
  wMxWpHgWb3iQwYSNEWfecf5Sw35A53clyvb5wevhnr/1DdOhoTdI6N725BG1iGhT
  LwIDAQAB";

  print_r (checkPASETOSignature($exampleToken, $examplePublicKey));
  //it will return  [email] => hieu.do@sotatek.com
