#!/usr/bin/env bash

# generate random password

RND_PASS=`date +%s | sha256sum | base64 | head -c12 ; echo`

# save it to install.config for example

echo "MYSQL_ROOT_PASS=${RND_PASS}">./install.config

# install mysql with random password

./install.mysql.sh -p=${RND_PASS}