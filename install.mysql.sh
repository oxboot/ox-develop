#!/usr/bin/env bash

function stop_mysql_server()
{
    /etc/init.d/mysql stop
    kill -9 $(pgrep mysql)
}

for i in "$@"
do
case $i in
    -p=*|--password=*)
    MYSQL_ROOT_PASS="${i#*=}"

    ;;
    *)
            # unknown option
    ;;
esac
done

if [ -z ${MYSQL_ROOT_PASS} ]; then
    echo "use ./install.mysql.sh -p=root_password"
    echo "use ./install.mysql.sh --password=root_password"
    echo "password not set"
    exit
fi

apt-get -y -q install mariadb-server

stop_mysql_server

mysqld_safe --skip-grant-tables &

while ! [[ "$mysqld_process_pid" =~ ^[0-9]+$ ]]; do
  mysqld_process_pid=$(echo "$(ps -C mysqld -o pid=)" | sed -e 's/^ *//g' -e 's/ *$//g')
  sleep 1
done

mysql -u root -e 'update user set password=PASSWORD("'${MYSQL_ROOT_PASS}'") where user="root";update user set plugin="mysql_native_password";' mysql

stop_mysql_server

/etc/init.d/mysql start
