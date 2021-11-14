FROM python:3.9-alpine
COPY ./main /opt/app/main
RUN chmod +x /opt/app/main
COPY ./admin/ /opt/admin/
RUN pip install -r /opt/admin/requirements.txt
COPY ./start.sh /start.sh

ENTRYPOINT [ "sh", "/start.sh" ]