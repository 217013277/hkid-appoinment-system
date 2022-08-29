<?php


echo $hkid = "A123456(7)";

echo "<br><br>";

echo $iv = bin2hex(openssl_random_pseudo_bytes(16));

echo "<br><br>";

echo $length = strlen(bin2hex(openssl_random_pseudo_bytes(16)));

echo "<br><br>";

echo $cipherhkid = openssl_encrypt($hkid, "aes-256-cbc", "+MbQeThWmZq4t7w!z%C*F)J@NcRfUjXn", $options=0, $iv, $tag);

echo "<br><br>";

$cipherhkidwithiv = $iv . $cipherhkid;

echo $cipherhkidwithiv;
echo "<br><br>";

echo $iv_ = substr($cipherhkidwithiv, 0, 32);
echo "<br><br>";
echo $cipherhkid_ = substr($cipherhkidwithiv, 32);
echo "<br><br>";
echo $original = openssl_decrypt($cipherhkid_, "aes-256-cbc", "+MbQeThWmZq4t7w!z%C*F)J@NcRfUjXn", $options=0, $iv_, $tag);
echo "<br><br>";

// function encrypt_my($plaintext, $cipher_ = "aes-256-cbc", $key = "+MbQeThWmZq4t7w!z%C*F)J@NcRfUjXn"){

    
//     $cipher = "aes-256-cbc";

//     $key = "+MbQeThWmZq4t7w!z%C*F)J@NcRfUjXn";

//     // Declare the length of IV
//     $ivlen = openssl_cipher_iv_length($cipher);
    
//     // Generate random IV
//     $iv = openssl_random_pseudo_bytes($ivlen);

//     $uniqid=bin2hex($iv);
// //     echo $uniqid ;

//     // Encrypt plaintext to ciphertext
//     $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
//     echo "Ciphetext: " . $ciphertext . "<br>";
//     echo "iv"
// }
// $plaintext = "Confidential";

// encrypt_my($plaintext);





// if (in_array($cipher, openssl_get_cipher_methods()))
// {
    
//     $uniqid=bin2hex($iv);
//     echo $uniqid ;
    
//     echo "<br>";
    
//     // Encrypt plaintext to ciphertext
//     $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
//     echo "Ciphetext: " . $ciphertext . "<br>";
    
//     // Decrypt ciphertext to plaintext
//     $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $tag);
//     echo "PlainText: " . $original_plaintext."\n";
// }

?>