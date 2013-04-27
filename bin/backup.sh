#!/bin/sh

/usr/bin/rsync  --password-file=/etc/rsyncd.passwd -av --delete --exclude=config.ini rsyncbackup@pinewell.no-ip.info::var/www/motion/ /var/www/motion/ > /tmp/backup.log
/usr/bin/mysql -u gettemp -p --password=gettemp gettemp < /var/www/motion/gettemp.dmp  >> /tmp/backup.log
