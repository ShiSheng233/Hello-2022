FROM python:3.10.2-alpine
WORKDIR /Hello-2022

COPY requirements.txt ./
RUN pip install -r requirements.txt

COPY . .

RUN mkdir -p logs
EXPOSE 2333
CMD ["gunicorn", "-c", "./gunicorn.conf.py", "main:app"]
