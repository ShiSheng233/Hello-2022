FROM golang:alpine as builder

WORKDIR /

ADD . .

ENV CGO_ENABLED=0

RUN go build -o app -trimpath -ldflags "-s -w -buildid=" .

FROM alpine:latest

WORKDIR /

COPY --from=builder /app /

EXPOSE 8080

CMD [ "/app" ]