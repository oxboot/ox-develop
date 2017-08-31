#!/usr/bin/env bash

# Define echo function
# Blue color
function ox_lib_echo()
{
  echo $(tput setaf 4)$@$(tput sgr0)
}
# White color
function ox_lib_echo_info()
{
  echo $(tput setaf 7)$@$(tput sgr0)
}
# Red color
function ox_lib_echo_fail()
{
  echo $(tput setaf 1)$@$(tput sgr0)
}

# Checking permissions
function ox_lib_check_sudo()
{
  if [[ $EUID -ne 0 ]]; then
    ox_lib_echo_fail "Sudo privileges required..."
    exit 100
  fi
}

# Install Ox
function ox_install()
{
  ox_lib_echo "Install pre depedencies, please wait..."
  apt-get -y install git curl gzip tar || ee_lib_error "Unable to install pre depedencies, exit status " 1
  ox_lib_echo "Install PHP 7.1, please wait..."
  add-apt-repository -y 'ppa:ondrej/php' -y
  apt-get update &>> /dev/null
  apt-get -y install php7.1-fpm php7.1-curl php7.1-gd php7.1-imap php7.1-mcrypt php7.1-readline php7.1-common php7.1-recode php7.1-mysql php7.1-cli php7.1-curl php7.1-mbstring php7.1-bcmath php7.1-mysql php7.1-opcache php7.1-zip php7.1-xml php-memcached php-imagick php-memcache memcached graphviz php-pear php-xdebug php-msgpack  php7.1-soap || ox_lib_error "Unable to install PHP 7.1 packages, exit status " 1
  service php7.1-fpm restart &>> /dev/null
  ox_lib_echo "Install NGINX, please wait..."
  apt-add-repository 'ppa:nginx/stable' -y
  apt-get update &>> /dev/null
  apt-get -y install nginx || ox_lib_error "Unable to install NGINX, exit status " 1
  service nginx restart &>> /dev/null
}

# Starting script point
ox_lib_echo_info "Starting Ox install process..."

ox_lib_check_sudo

# Execute: apt-get update
ox_lib_echo "Executing apt-get update, please wait..."
apt-get update &>> /dev/null

# Checking lsb_release package
if [ ! -x /usr/bin/lsb_release ]; then
  ox_lib_echo "Installing lsb-release, please wait..."
  apt-get -y install lsb-release &>> /dev/null
fi

# Checking linux distro
lsb_release -d | egrep -e "Ubuntu 16.04" &>> /dev/null
if [ "$?" -ne "0" ]; then
    ox_lib_echo_fail "Ox only supports Ubuntu 16.04"
    exit 100
fi

ox_install
