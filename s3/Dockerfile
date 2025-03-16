FROM alpine:3.21.3

ARG S3_BUCKET
ARG MINIO_ROOT_USER
ARG MINIO_ROOT_PASSWORD
ARG MINIO_REGION

COPY docker-entrypoint.sh .

RUN apk update && apk add --no-cache curl bash aws-cli \
    && aws configure set default.s3.signature_version s3v4 \   
    && aws configure set region $MINIO_REGION \
    && aws configure set aws_access_key_id $MINIO_ROOT_USER \
    && aws configure set aws_secret_access_key $MINIO_ROOT_PASSWORD \
    && curl -sS -o minio https://dl.min.io/server/minio/release/linux-amd64/minio \
    && chmod +x minio \
    && mv minio /usr/local/bin/ \
    && curl -sS -o mc https://dl.min.io/client/mc/release/linux-amd64/mc \
    && chmod +x mc \
    && mv mc /usr/local/bin/


CMD ./docker-entrypoint.sh & minio server /data/minio --console-address ':9090'
