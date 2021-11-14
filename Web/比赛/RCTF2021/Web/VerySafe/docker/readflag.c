#include <stdio.h>

int main () {
  char flag[256];
  FILE* fp = fopen("/flag", "r");
  fread(flag, 1, 256, fp);
  puts(flag);
  fclose(fp);
  return 0;
}