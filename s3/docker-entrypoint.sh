#!/usr/bin/env bash

set -e

until nc -z -v -w30 localhost 9000; do
  echo "Waiting MinIO..."
  sleep 2
done

declare -r endpoint='--endpoint-url=http://localhost:9000'
declare -r exists=$(aws s3 ls $endpoint | grep "$S3_BUCKET")

[ -n "$exists" ] && exit 0

aws configure set default.s3.signature_version s3v4
aws configure set region "$MINIO_REGION"
aws configure set aws_access_key_id "$MINIO_ROOT_USER"
aws configure set aws_secret_access_key "$MINIO_ROOT_PASSWORD"
mc alias set local http://localhost:9000 "$MINIO_ROOT_USER" "$MINIO_ROOT_PASSWORD"
aws s3 mb "s3://$S3_BUCKET" $endpoint
mc anonymous set download local/"$S3_BUCKET"

