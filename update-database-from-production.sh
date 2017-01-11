if [ ! -f "database-latest.sql.xz" ]; then
    ssh OraWellness 'DATE=$(date +"%Y-%m-%d-%H%M") && mysqldump --defaults-file=/home/wwwcus/.mysqldump.conf orawellnesscom | xz > /home/wwwcus/database-backups/database-$DATE.sql.xz && ln -f -s /home/wwwcus/database-backups/database-$DATE.sql.xz /home/wwwcus/database-latest.sql.xz' && scp OraWellness:/home/wwwcus/database-latest.sql.xz ./
fi

# import
xzcat -c database-latest.sql.xz | mysql --defaults-file=~/.mysql_credentials orawelln_wp && cd ~/Sites/orawellness/ && wp search-replace 'https://www.orawellness.com' 'https://orawellness.dev' && wp plugin deactivate mailgun && cd - && mv database-latest.sql.xz ~/.Trash/
