FROM python:3.6




COPY ./run.py /web/run.py
COPY ./app /web/app/
RUN pip install -r /web/app/requirements.txt

EXPOSE 80

WORKDIR /web/

CMD ["python", "run.py"]

