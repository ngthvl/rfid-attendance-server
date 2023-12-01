FROM node:alpine as builder

RUN mkdir -p /home/app/

WORKDIR /home/app/

COPY . .

COPY .env.prod .env

RUN npm i
