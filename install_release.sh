KEEP_RELEASES=5
BASE=/var/www/kantoortuin
RELEASES="$BASE"/releases
mkdir -p $RELEASES

NOW=$(date +%F_%T)
mv /tmp/release "$RELEASES"/"$NOW"

cd "$BASE"
rm -f current
ln -sv releases/"$NOW" current

cd releases/"$NOW"
ln -sv ../../storage storage
ln -sv "$BASE"/.env .env

cd database
ln -sv ../../../database.sqlite database.sqlite
cd ..

echo "Running migrations"
sudo -u www-data php artisan migrate --force --no-interaction
echo "Reloading Apache"
sudo service apache2 reload

echo "Reloading supervisor"
# workaround supervisor bug http://stackoverflow.com/questions/32738415/supervisor-fails-to-restart-half-of-the-time
sudo /etc/init.d/supervisor force-stop && \
sudo /etc/init.d/supervisor stop && \
sudo /etc/init.d/supervisor start

cd $RELEASES
echo "Deleting old releases"
ls -1d 20* | head -n -"$KEEP_RELEASES" | xargs -d "\n" rm -Rf;
