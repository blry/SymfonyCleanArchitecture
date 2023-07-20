echo "Waiting for MySQL"

maxcounter=20

counter=1
while ! mysql -u"$DB_APP_USER" -p"$(cat $DB_APP_PASSWORD_FILE)" -e "show databases;" > /dev/null 2>&1; do
    sleep 1
    counter=`expr $counter + 1`
    if [ $counter -gt $maxcounter ]; then
        >&2 echo "We have been waiting for MySQL too long already; failing."
        exit 1
    fi;
done

echo "Finished."
