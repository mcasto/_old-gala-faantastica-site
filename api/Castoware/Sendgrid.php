<?php
/* composer require sendgrid/sendgrid */
/* make sure to set up & verify sender email at https://app.sendgrid.com/settings/sender_auth */

namespace Castoware;

class Sendgrid
{
  private $key, $cipher;

  function __construct($key = null)
  {
    $this->key = $key ?? bin2hex(openssl_random_pseudo_bytes(256));
    $this->cipher = "aes-256-gcm";
  }

  function encrypt($plaintext)
  {
    $cipher = $this->cipher;
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext = openssl_encrypt($plaintext, $cipher, $this->key, $options = 0, $iv, $tag);
    return $this->key . bin2hex($tag) . bin2hex($iv) . $ciphertext;
  }

  function decrypt($cipherHash)
  {
    $key = substr($cipherHash, 0, 512);
    $tag = hex2bin(substr($cipherHash, 512, 32));
    $iv = hex2bin(substr($cipherHash, 544, 24));
    $ciphertext = substr($cipherHash, 568);

    return openssl_decrypt($ciphertext, $this->cipher, $key, $options = 0, $iv, $tag);
  }

  function setupKeyFile($apiKey, $fileLocation)
  {
    $hash = $this->encrypt($apiKey);
    file_put_contents($fileLocation, $hash);
  }

  function apiKey($keyFile)
  {
    return $this->decrypt(file_get_contents($keyFile));
  }

  function sendEmail($config, $replyTo, $replyToName, $to, $toName, $subject, $body, $contactID)
  {
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom('contacts@castoware.com', 'CastoWare Development');
    $email->setReplyTo($replyTo, $replyToName);
    $email->setSubject($subject);
    $email->addTo($to, $toName);
    $email->addContent('text/html', $body);

    $sendgrid = new \SendGrid($this->apiKey($config->keyFile));

    try {
      $response = $sendgrid->send($email);

      $sendStatus = [
        'statusCode' => $response->statusCode(),
        'headers' => $response->headers(),
        'body' => $response->body()
      ];

      $util = new util();

      $config->db->update('contacts')
        ->where('_id')->is($contactID)
        ->set(['send_status' => json_encode(['success' => $sendStatus])]);

      $util->success($sendStatus);
    } catch (Exception $e) {
      $config->db->update('contacts')
        ->where('_id')->is($contactID)
        ->set(['send_status' => json_encode(['fail' => $e->getMessage()])]);

      $util->fail($e->getMessage());
    }
  }
}

/*
  1. get api key from sendgrid
  2. uncomment following code
  3. set $apiKey = api key
  4. set $fileLocation = the location of the sendgrid.key file (as specified in config)
  5. run this script
  6. recomment code below
*/

// $apiKey="";
// $fileLocation="";
// $crypt = new sendgrid();
// $crypt->setupKeyFile($apiKey, $fileLocation);
