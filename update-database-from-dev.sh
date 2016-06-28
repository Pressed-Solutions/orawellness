if [ ! -f "database-latest.sql.xz" ]; then
    ssh PressedSolutions 'cd /home/orawellness/ && DATE=$(date +"%Y-%m-%d-%H%M") && mysqldump --defaults-file=/home/orawellness/.mysqldump.conf orawelln_wp | xz > /home/orawellness/database-backups/database-$DATE.sql.xz && ln -f -s /home/orawellness/database-backups/database-$DATE.sql.xz /home/orawellness/database-latest.sql.xz' && scp PressedSolutions:/home/orawellness/database-latest.sql.xz ./
fi

# import
xzcat -c database-latest.sql.xz | mysql --defaults-file=~/.mysql_credentials orawelln_wp && mysql --defaults-file=~/.mysql_credentials --execute="USE orawelln_wp; UPDATE wpora_options SET option_value = 'http://orawellness.dev' WHERE option_value = 'http://dev.orawellness.com';" && mv database-latest.sql.xz ~/.Trash/
