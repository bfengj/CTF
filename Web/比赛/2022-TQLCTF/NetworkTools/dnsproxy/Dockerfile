FROM python:3.6
WORKDIR /app

COPY src .
RUN pip install -r requirements.txt -i https://pypi.tuna.tsinghua.edu.cn/simple \
    && pip install pycares-4.1.3.dev0-cp36-cp36m-linux_x86_64.whl

RUN chmod -R 755 /app

EXPOSE 53/udp
CMD ["python", "-u", "dnschef.py"]
