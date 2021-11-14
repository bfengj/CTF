FROM python:3.9-slim
WORKDIR /RCTF_CoolCat
COPY requirements.txt requirements.txt
RUN pip install --upgrade pip setuptools
RUN pip install -r requirements.txt
COPY static static
COPY templates templates
COPY flag.jpg flag.jpg
EXPOSE 9999
COPY app.py app.py
ENTRYPOINT ["python", "app.py"]
