#!/usr/bin/env bash

for i in "$@"
do
case $i in
    -db=*|--database=*)
    DB_NAME="${i#*=}"

    ;;
    -p=*|--password=*)
    MYSQL_ROOT_PASS="${i#*=}"

    ;;
    *)
            # unknown option
    ;;
esac
done

if [ -z ${DB_NAME} ] || [ -z ${MYSQL_ROOT_PASS} ]; then
    echo "use ./create.db.sh -db=database_name -p=root_password"
    echo "database name not set or root password is empty"
    exit
fi

mysqladmin -u root --password=${MYSQL_ROOT_PASS} create ${DB_NAME}
