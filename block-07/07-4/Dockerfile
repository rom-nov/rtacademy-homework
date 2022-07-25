FROM ubuntu:22.04
ENV TZ=Europe/Kiev
RUN apt update -y
RUN apt upgrade -y
RUN apt install -y nginx-light
COPY index.html /var/www/html/
EXPOSE 80
ENTRYPOINT ["nginx", "-g", "daemon off;"]
