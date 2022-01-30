#!/bin/sh

mkdir -p logs
gunicorn -c gunicorn.conf.py main:app
