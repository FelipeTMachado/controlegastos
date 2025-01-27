#!/bin/bash

sudo rm -r -v site/vendor site/composer.* banco/vendor banco/composer.* microservico/vendor microservico/composer.* mysql/mysql rabbitmq/ 
sudo docker rm -f $(sudo docker ps -aq) 
sudo docker rmi $(sudo docker images -aq) --force 
sudo docker system prune --all --volumes --force