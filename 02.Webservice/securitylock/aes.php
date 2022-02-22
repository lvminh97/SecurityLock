<?php 

define('KEY_SCHEDULE_BYTES', 240);
define('N_BLOCK', 16);

$s_fwd = array(
  0x63, 0x7c, 0x77, 0x7b, 0xf2, 0x6b, 0x6f, 0xc5, 0x30, 0x01, 0x67, 0x2b, 0xfe, 0xd7, 0xab, 0x76,
  0xca, 0x82, 0xc9, 0x7d, 0xfa, 0x59, 0x47, 0xf0, 0xad, 0xd4, 0xa2, 0xaf, 0x9c, 0xa4, 0x72, 0xc0,
  0xb7, 0xfd, 0x93, 0x26, 0x36, 0x3f, 0xf7, 0xcc, 0x34, 0xa5, 0xe5, 0xf1, 0x71, 0xd8, 0x31, 0x15,
  0x04, 0xc7, 0x23, 0xc3, 0x18, 0x96, 0x05, 0x9a, 0x07, 0x12, 0x80, 0xe2, 0xeb, 0x27, 0xb2, 0x75,
  0x09, 0x83, 0x2c, 0x1a, 0x1b, 0x6e, 0x5a, 0xa0, 0x52, 0x3b, 0xd6, 0xb3, 0x29, 0xe3, 0x2f, 0x84,
  0x53, 0xd1, 0x00, 0xed, 0x20, 0xfc, 0xb1, 0x5b, 0x6a, 0xcb, 0xbe, 0x39, 0x4a, 0x4c, 0x58, 0xcf,
  0xd0, 0xef, 0xaa, 0xfb, 0x43, 0x4d, 0x33, 0x85, 0x45, 0xf9, 0x02, 0x7f, 0x50, 0x3c, 0x9f, 0xa8,
  0x51, 0xa3, 0x40, 0x8f, 0x92, 0x9d, 0x38, 0xf5, 0xbc, 0xb6, 0xda, 0x21, 0x10, 0xff, 0xf3, 0xd2,
  0xcd, 0x0c, 0x13, 0xec, 0x5f, 0x97, 0x44, 0x17, 0xc4, 0xa7, 0x7e, 0x3d, 0x64, 0x5d, 0x19, 0x73,
  0x60, 0x81, 0x4f, 0xdc, 0x22, 0x2a, 0x90, 0x88, 0x46, 0xee, 0xb8, 0x14, 0xde, 0x5e, 0x0b, 0xdb,
  0xe0, 0x32, 0x3a, 0x0a, 0x49, 0x06, 0x24, 0x5c, 0xc2, 0xd3, 0xac, 0x62, 0x91, 0x95, 0xe4, 0x79,
  0xe7, 0xc8, 0x37, 0x6d, 0x8d, 0xd5, 0x4e, 0xa9, 0x6c, 0x56, 0xf4, 0xea, 0x65, 0x7a, 0xae, 0x08,
  0xba, 0x78, 0x25, 0x2e, 0x1c, 0xa6, 0xb4, 0xc6, 0xe8, 0xdd, 0x74, 0x1f, 0x4b, 0xbd, 0x8b, 0x8a,
  0x70, 0x3e, 0xb5, 0x66, 0x48, 0x03, 0xf6, 0x0e, 0x61, 0x35, 0x57, 0xb9, 0x86, 0xc1, 0x1d, 0x9e,
  0xe1, 0xf8, 0x98, 0x11, 0x69, 0xd9, 0x8e, 0x94, 0x9b, 0x1e, 0x87, 0xe9, 0xce, 0x55, 0x28, 0xdf,
  0x8c, 0xa1, 0x89, 0x0d, 0xbf, 0xe6, 0x42, 0x68, 0x41, 0x99, 0x2d, 0x0f, 0xb0, 0x54, 0xbb, 0x16
);
$s_inv = array(
  0x52, 0x09, 0x6a, 0xd5, 0x30, 0x36, 0xa5, 0x38, 0xbf, 0x40, 0xa3, 0x9e, 0x81, 0xf3, 0xd7, 0xfb,
  0x7c, 0xe3, 0x39, 0x82, 0x9b, 0x2f, 0xff, 0x87, 0x34, 0x8e, 0x43, 0x44, 0xc4, 0xde, 0xe9, 0xcb,
  0x54, 0x7b, 0x94, 0x32, 0xa6, 0xc2, 0x23, 0x3d, 0xee, 0x4c, 0x95, 0x0b, 0x42, 0xfa, 0xc3, 0x4e,
  0x08, 0x2e, 0xa1, 0x66, 0x28, 0xd9, 0x24, 0xb2, 0x76, 0x5b, 0xa2, 0x49, 0x6d, 0x8b, 0xd1, 0x25,
  0x72, 0xf8, 0xf6, 0x64, 0x86, 0x68, 0x98, 0x16, 0xd4, 0xa4, 0x5c, 0xcc, 0x5d, 0x65, 0xb6, 0x92,
  0x6c, 0x70, 0x48, 0x50, 0xfd, 0xed, 0xb9, 0xda, 0x5e, 0x15, 0x46, 0x57, 0xa7, 0x8d, 0x9d, 0x84,
  0x90, 0xd8, 0xab, 0x00, 0x8c, 0xbc, 0xd3, 0x0a, 0xf7, 0xe4, 0x58, 0x05, 0xb8, 0xb3, 0x45, 0x06,
  0xd0, 0x2c, 0x1e, 0x8f, 0xca, 0x3f, 0x0f, 0x02, 0xc1, 0xaf, 0xbd, 0x03, 0x01, 0x13, 0x8a, 0x6b,
  0x3a, 0x91, 0x11, 0x41, 0x4f, 0x67, 0xdc, 0xea, 0x97, 0xf2, 0xcf, 0xce, 0xf0, 0xb4, 0xe6, 0x73,
  0x96, 0xac, 0x74, 0x22, 0xe7, 0xad, 0x35, 0x85, 0xe2, 0xf9, 0x37, 0xe8, 0x1c, 0x75, 0xdf, 0x6e,
  0x47, 0xf1, 0x1a, 0x71, 0x1d, 0x29, 0xc5, 0x89, 0x6f, 0xb7, 0x62, 0x0e, 0xaa, 0x18, 0xbe, 0x1b,
  0xfc, 0x56, 0x3e, 0x4b, 0xc6, 0xd2, 0x79, 0x20, 0x9a, 0xdb, 0xc0, 0xfe, 0x78, 0xcd, 0x5a, 0xf4,
  0x1f, 0xdd, 0xa8, 0x33, 0x88, 0x07, 0xc7, 0x31, 0xb1, 0x12, 0x10, 0x59, 0x27, 0x80, 0xec, 0x5f,
  0x60, 0x51, 0x7f, 0xa9, 0x19, 0xb5, 0x4a, 0x0d, 0x2d, 0xe5, 0x7a, 0x9f, 0x93, 0xc9, 0x9c, 0xef,
  0xa0, 0xe0, 0x3b, 0x4d, 0xae, 0x2a, 0xf5, 0xb0, 0xc8, 0xeb, 0xbb, 0x3c, 0x83, 0x53, 0x99, 0x61,
  0x17, 0x2b, 0x04, 0x7e, 0xba, 0x77, 0xd6, 0x26, 0xe1, 0x69, 0x14, 0x63, 0x55, 0x21, 0x0c, 0x7d
);

function s_box($x){
  global $s_fwd;
  return $s_fwd[$x];
}

function is_box($x){
  global $s_inv;
  return $s_inv[$x];
}

function f2($x){
  return ($x & 0x80) ? (($x << 1) ^ 0x011B) : ($x << 1);
}

function d2($x){
  return ($x >> 1) ^ (($x & 1) ? 0x008D : 0);
}

function xor_block($d, $s, $pos = 0){
  for($i = 0; $i < 16; $i++){
    $d[$i] ^= $s[$i + $pos];
  }
  return $d;
}

function copy_and_key($d, $s, $k, $start = 0){
  for($i = 0; $i < 16; $i++){
    $d[$i] = $s[$i] ^ $k[$i + $start];
  }
  return $d;
}

function shift_sub_rows($st){
  $st[0] = s_box ($st[0]) ; $st[4]  = s_box ($st[4]) ;
  $st[8] = s_box ($st[8]) ; $st[12] = s_box ($st[12]) ;
  $tt = $st [1] ;
  $st[1] = s_box ($st[5]) ;  $st[5]  = s_box ($st[9]) ;
  $st[9] = s_box ($st[13]) ; $st[13] = s_box ($tt) ;

  $tt = $st[2] ; $st[2] = s_box ($st[10]) ; $st[10] = s_box ($tt) ;
  $tt = $st[6] ; $st[6] = s_box ($st[14]) ; $st[14] = s_box ($tt) ;

  $tt = $st[15] ;
  $st[15] = s_box ($st[11]) ; $st[11] = s_box ($st[7]) ;
  $st[7]  = s_box ($st[3]) ;  $st[3]  = s_box ($tt) ;
  
  return $st;
}

function inv_shift_sub_rows ($st){
  $st[0] = is_box ($st[0]) ; $st[4] = is_box ($st[4]);
  $st[8] = is_box ($st[8]) ; $st[12] = is_box ($st[12]);

  $tt = $st[13];
  $st[13] = is_box ($st[9]) ; $st[9] = is_box ($st[5]) ;
  $st[5]  = is_box ($st[1]) ; $st[1] = is_box ($tt) ;

  $tt = $st[2] ; $st[2] = is_box ($st[10]) ; $st[10] = is_box ($tt) ;
  $tt = $st[6] ; $st[6] = is_box ($st[14]) ; $st[14] = is_box ($tt) ;

  $tt = $st [3] ;
  $st[3]  = is_box ($st[7])  ; $st[7]  = is_box ($st[11]) ;
  $st[11] = is_box ($st[15]) ; $st[15] = is_box ($tt) ;

  return $st;
}

function mix_sub_columns ($st){
  $dt = array();
  $j = 5 ;
  $k = 10 ;
  $l = 15 ;
  for ($i = 0 ; $i < 16 ; $i += 4){
    $a = $st [$i] ;
    $b = $st [$j] ;  $j = ($j+4) & 15 ;
    $c = $st [$k] ;  $k = ($k+4) & 15 ;
    $d = $st [$l] ;  $l = ($l+4) & 15 ;
    $a1 = s_box ($a); $b1 = s_box ($b); $c1 = s_box ($c); $d1 = s_box ($d) ;
    $a2 = f2($a1);    $b2 = f2($b1);    $c2 = f2($c1);    $d2 = f2($d1) ;
    $dt[$i]   = $a2     ^  $b2^$b1  ^  $c1     ^ $d1 ;
    $dt[$i+1] = $a1     ^  $b2     ^  $c2^$c1  ^  $d1 ;
    $dt[$i+2] = $a1     ^  $b1     ^  $c2     ^  $d2^$d1 ;
    $dt[$i+3] = $a2^$a1  ^  $b1     ^  $c1     ^  $d2 ;
  }
  return $dt;
}

function inv_mix_sub_columns ($st){
  $dt = array();
  for ($i = 0 ; $i < 16 ; $i += 4){
    $a1 = $st [$i] ;
    $b1 = $st [$i+1] ;
    $c1 = $st [$i+2] ;
    $d1 = $st [$i+3] ;
    $a2 = f2($a1); $b2 = f2($b1); $c2 = f2($c1); $d2 = f2($d1) ;
    $a4 = f2($a2); $b4 = f2($b2); $c4 = f2($c2); $d4 = f2($d2) ;
    $a8 = f2($a4); $b8 = f2($b4); $c8 = f2($c4); $d8 = f2($d4) ;
    $a9 = $a8 ^ $a1; $b9 = $b8 ^ $b1; $c9 = $c8 ^ $c1; $d9 = $d8 ^ $d1 ;
    $ac = $a8 ^ $a4; $bc = $b8 ^ $b4; $cc = $c8 ^ $c4; $dc = $d8 ^ $d4 ;

    $dt[$i]         = is_box ($ac^$a2  ^  $b9^$b2  ^  $cc^$c1  ^  $d9) ;
    $dt[($i+5)&15]  = is_box ($a9     ^  $bc^$b2  ^  $c9^$c2  ^  $dc^$d1) ;
    $dt[($i+10)&15] = is_box ($ac^$a1  ^  $b9     ^  $cc^$c2  ^  $d9^$d2) ;
    $dt[($i+15)&15] = is_box ($a9^$a2  ^  $bc^$b1  ^  $c9     ^  $dc^$d2) ;
  }
  return $dt;
}

function copy_n_bytes($s, $n){
  $d = array();
  for($i = 0; $i < $n; $i++){
    $d[$i] = $s[$i];
  }
  return $d;
}

function addPaddingZero($data, $blocksize){
  $padLen = ($blocksize - count($data) % $blocksize) % $blocksize;
  for($i = 0; $i < $padLen; $i++){
    array_push($data, 0);
  }
  return $data;
}

class AES{
  private $round;
  private $key_sched;

  public function set_key($key, $keylen){
    switch($keylen){
      case 128:
        $keylen = 16;
        $this->round = 10;
        break;
      case 192:
        $keylen = 24;
        $this->round = 12;
        break;
      case 256:
        $keylen = 32;
        $this->round = 14;
        break;
      default:
        $this->round = 0;
        return false;
    };

    $hi = ($this->round + 1) * 16;
    $this->key_sched = copy_n_bytes($key, $keylen);
    $t = array(0, 0, 0, 0);
    $next = $keylen;
    for($cc = $keylen, $rc = 1; $cc < $hi; $cc += 4){
      for($i = 0; $i < 4; $i++){
        $t[$i] = $this->key_sched[$cc - 4 + $i];
      }
      if($cc == $next){
        $next += $keylen;
        $ttt = $t[0];
        $t[0] = s_box($t[1]) ^ $rc;
        $t[1] = s_box($t[2]);
        $t[2] = s_box($t[3]);
        $t[3] = s_box($ttt);
        $rc = f2($rc);
      }
      else if($keylen == 32 && ($cc & 31) == 16){
        for($i = 0; $i < 4; $i++){
          $t[$i] = s_box($t[$i]);
        }
      }
      $tt = $cc - $keylen;
      for($i = 0; $i < 4; $i++){
        $this->key_sched[$cc + $i] = $this->key_sched[$tt + $i] ^ $t[$i];
      }
    }
    return true;
  }

  public function clean(){
    for($i = 0; $i < KEY_SCHEDULE_BYTES; $i++){
      $this->key_sched[$i] = 0;
    }
    $this->round = 0;
  }

  public function encrypt($plain){
    $cipher = array();
    if ($this->round > 0){
      $s1 = array();
      $r = 0;
      $s1 = copy_and_key ($s1, $plain, $this->key_sched, 0);

      for ($r = 1 ; $r < $this->round ; $r++){
        $s2 = array();
        $s2 = mix_sub_columns ($s1) ;
        $s1 = copy_and_key ($s1, $s2, $this->key_sched, $r * 16);
      }
      $s1 = shift_sub_rows($s1) ;
      $cipher = copy_and_key ($cipher, $s1, $this->key_sched, $r * 16);
    }
    else
      return false;
    return $cipher;
  }

  public function cbc_encrypt($plain, $blocks, $iv){
    $cipher = array();
    $pos = 0;
    while($blocks > 0){
      $blocks--;
      $iv = xor_block($iv, $plain, $pos);
      $iv = $this->encrypt($iv);
      if($iv === false)
        return false;
      for($i = 0; $i < N_BLOCK; $i++){
        $cipher[$pos + $i] = $iv[$i];
      }
      $pos += N_BLOCK;
    }
    return $cipher;
  }

  public function decrypt ($cipher){
    $plain = array();
    if ($this->round > 0){
      $s1 = array();
      $s1 = copy_and_key($s1, $cipher, $this->key_sched, $this->round * 16);
      $s1 = inv_shift_sub_rows($s1);

      for ($r = $this->round ; --$r ; ){
        $s2 = array();
        $s2 = copy_and_key ($s2, $s1, $this->key_sched, $r * 16);
        $s1 = inv_mix_sub_columns ($s2) ;
      }
      $plain = copy_and_key ($plain, $s1, $this->key_sched, 0);
    }
    else
      return false ;
    return $plain;
  }

  public function cbc_decrypt($cipher, $blocks, $iv){
    $plain = array();
    $pos = 0;
    while($blocks > 0){
      $blocks--;
      $tmp = array();
      for($i = 0; $i < N_BLOCK; $i++){
        $tmp[$i] = $cipher[$i + $pos];
      }
      $tmp_cipher = array_slice($cipher, $pos, N_BLOCK);
      $tmp_plain = $this->decrypt($tmp_cipher);
      if($tmp_plain === false)
        return false;
      $tmp_plain = xor_block($tmp_plain, $iv, 0);
      $plain = array_merge($plain, $tmp_plain);
      $iv = copy_n_bytes($tmp, N_BLOCK);
      $pos += N_BLOCK;
    }
    return $plain;
  }

  public function do_encrypt($plain, $iv){
    $cipher = array();
    $plain = addPaddingZero($plain, 16);
    $blocks = intdiv(count($plain), N_BLOCK);
    if($blocks == 1){
      $cipher = $this->encrypt($plain);
    }
    else{
      $cipher = $this->cbc_encrypt($plain, $blocks, $iv);
    }
    return $cipher;
  }

  public function do_decrypt($cipher, $iv){
    $plain = array();
    $blocks = intdiv(count($cipher), N_BLOCK);
    if($blocks == 1){
      $plain = $this->decrypt($cipher);
    }
    else{
      $plain = $this->cbc_decrypt($cipher, $blocks, $iv);
    }
    return $plain;
  }
};

?>