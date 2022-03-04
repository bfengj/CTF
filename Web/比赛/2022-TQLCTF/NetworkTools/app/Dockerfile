FROM python:3.8.8
WORKDIR /app

RUN sed -i 's/deb.debian.org/mirrors.ustc.edu.cn/g' /etc/apt/sources.list \
    && sed -i 's/security.debian.org/mirrors.ustc.edu.cn/g' /etc/apt/sources.list \
    && apt-get update \
    && apt-get install -y inetutils-ping traceroute

COPY src/requirements.txt .
RUN pip install -r requirements.txt -i https://pypi.tuna.tsinghua.edu.cn/simple

COPY src .
COPY flag.sh /usr/bin

RUN echo 'TQLCTF{test}' > /flag \
    && chmod 644 /flag \
    && chmod -R 755 /app \
    && chmod +x /usr/bin/flag.sh \
    && useradd -m tqlctf \
    && chown tqlctf:tqlctf /app

USER tqlctf

EXPOSE 8080
CMD ["gunicorn", "app:app", "-c", "./gunicorn.conf.py"]
