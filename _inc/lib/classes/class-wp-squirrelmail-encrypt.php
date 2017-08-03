<?php
/*
 * Copyright (C) 2016 Edgar Hernandez
 *
 * WP-SquirrelMail is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Enrcypt and/or decrypt strings.
 *
 * @author Edgar Hernandez
 */
class WP_Squirrelmail_Encrypt {
    protected $plainText;
    protected $encryptedText;
    protected $iv_size;
    protected $key;
    
    private function setEncrypt($plainText) {
        $iv = mcrypt_create_iv($this->getIvSize(), MCRYPT_RAND);
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->getKey(),
            utf8_encode($plainText), MCRYPT_MODE_CBC, $iv);
        
        $ciphertext = $iv . $ciphertext;
        $ciphertext_base64 = base64_encode($ciphertext);
        
        $this->encryptedText = $ciphertext_base64;
    }
    
    public function getEncrypt($plainText) {
        if($this->emptyString($plainText)) {
            return;
        }
        $this->setEncrypt($plainText);
        
        return $this->encryptedText;
    }
    
    private function setDecrypt($encryptedText) {
        $ciphertext_dec = base64_decode($encryptedText);
        $iv_size = $this->getIvSize();
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext = substr($ciphertext_dec, $iv_size);
        
        $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->getKey(),
            $ciphertext, MCRYPT_MODE_CBC, $iv_dec);
        
        $plaintext = rtrim($plaintext_dec);
        
        $this->plainText = $plaintext;
    }
    
    public function getDecrypt($encryptedText) {
        if($this->emptyString($encryptedText)) {
            return;
        }
        $this->setDecrypt($encryptedText);
        
        return $this->plainText;
    }

    private function setIvSize() {
        $this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    }
    
    private function getIvSize() {
        if(!$this->iv_size) {
            $this->setIvSize();
        }
        return $this->iv_size;
    }
    
    private function setKey($key) {
        $this->key = pack('H*', $key);
    }
    
    private function getKey() {
        if(!$this->key) {
            $this->setKey('bcb04b7e103a0cd8b54763051cef08b55cabe029fdebae5e1d417e2ffb2a00a3');
        }
        return $this->key;
    }
    
    private function emptyString($str) {
        $valid = trim($str);
        
        if(!empty($valid)) {
            return false;
        }
        return true;
    }
}
