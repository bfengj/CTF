#!/bin/sh

ls /home/vpopmail/domains >$confdir/isoqlog.domains

isoqlog -f /etc/isoqlog/isoqlog.conf >/dev/null

chown -R apache:apache /usr/share/isoqlog/htdocs
