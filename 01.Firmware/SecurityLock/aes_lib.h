#ifndef _AES_LIB_H
#define _AES_LIB_H

#include <AES.h>

AES aes;
int tmp_iv[16] = {0};

void AES_Init(char key[], int size){
  aes.set_key(key, size);
}

int AES_Encrypt(char plaintext[], int len, char ciphertext[], char iv[]){
  int padLen = (16 - len % 16);
  for(int i = 0; i < padLen; i++){
    plaintext[len] = 0;
    len++;
  }
  
  int blocks = len / 16;

  if(blocks == 1){
    aes.encrypt((byte*) plaintext, (byte*) ciphertext);
  }
  else{
    memcpy(tmp_iv, iv, 16);
    aes.cbc_encrypt((byte*) plaintext, (byte*) ciphertext, blocks, (byte*) tmp_iv);
  }
  return 16 * blocks;
}

void AES_Decrypt(char ciphertext[], int len, char plaintext[], char iv[]){
  int blocks = len / 16;

  if(blocks == 1){
    aes.decrypt((byte*) ciphertext, (byte*) plaintext);
  }
  else{
    memcpy(tmp_iv, iv, 16);
    aes.cbc_decrypt((byte*) ciphertext, (byte*) plaintext, blocks, (byte*) tmp_iv);
  }
}

#endif
