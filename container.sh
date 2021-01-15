#!/bin/bash
docker exec -t -i `docker ps | grep "/run.sh" | awk '{print $1}'` bash