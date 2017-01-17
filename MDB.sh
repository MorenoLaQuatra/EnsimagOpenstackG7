#!/bin/bash

#Add the MariaDB Repository
sudo apt-get install software-properties-common
sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xcbcb082a1bb943db
sudo add-apt-repository 'deb http://mirror.jmu.edu/pub/mariadb/repo/5.5/ubuntu trusty main'
sudo apt-get update

#Install MariaDB
sudo apt-get install mariadb-server
sudo service mysql stop

#Configure and Secure MariaDB for Use
sudo mysql_install_db
sudo service mysql start
sudo mysql_secure_installation

#Verify MariaDB Installation
mysql -V
mysql -p

#Modify folder
sudo service mysqld stop
sudo mkdir /data/mysql
sudo cp -rap /var/lib/mysql /data
sudo chown mysql.mysql /data/mysql


sudo -rm /etc/mysql/my.cnf
sudo cp -rap ./my.cnf  /etc/mysql/

sudo service mysqld start

#Load DB
scp ./prestashop_fullcustomer.dump.sql ubuntu@10.11.50.109:/home/ubuntu
sudo mysql db < ./prestashop_fullcustomer.dump.sql

exit
