#! /bin/bash
docker system prune -f
docker run -d --name sonarqube \
    -p 9000:9000 \
    -v /var/lib/sonarqube/data:/opt/sonarqube/data \
    -v /var/lib/sonarqube/extensions:/opt/sonarqube/extensions \
    sonarqube
docker run --detach --name zap -u zap -v "/opt/dast/reports":/zap/reports/:rw \
  -i owasp/zap2docker-stable zap.sh -daemon -host 0.0.0.0 -port 8080 \
  -config api.addrs.addr.name=.* -config api.addrs.addr.regex=true \
  -config api.disablekey=true