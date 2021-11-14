#!/bin/sh
cd /var/www/html
sudo -u www-data hhvm -m server -dhhvm.server.thread_count=100 -dhhvm.http.default_timeout=1 -dhhvm.server.connection_timeout_seconds=1 -dhhvm.debugger.vs_debug_enable=1 -dhhvm.server.port=8080 -dhhvm.repo.central.path=/tmp/hhvm.hhbc -dhhvm.pid_file=/tmp/hhvm.pid -dhhvm.server.whitelist_exec=true -dhhvm.server.allowed_exec_cmds[]= -dhhvm.server.request_timeout_seconds=1 -dopen_basedir=/var/www/html
