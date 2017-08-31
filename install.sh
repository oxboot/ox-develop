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
