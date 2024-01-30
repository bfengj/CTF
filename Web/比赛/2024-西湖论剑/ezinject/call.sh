#!/usr/bin/tclsh

set password [lindex $argv 0]
set host [lindex $argv 1]
set port [lindex $argv 2]
set dir [lindex $argv 3]
puts $argv



eval spawn ssh -p $port $host test -d $dir && echo exists
expect "*(yes/no*)?*$" { send "yes\n" }
set timeout 1
expect "*assword:*$" { send "$password\n" } \
timeout { exit 1 }
set timeout -1
expect "\\$ $"
