docker build -t wpum:latest .
docker run -id --name runwpum -p 30005:80 -e FLAG=flag{flag} wpum:latest