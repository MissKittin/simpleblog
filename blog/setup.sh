#!/bin/sh

while true; do
	echo -n 'install cron? [y/n] '
	read cronanswer
	if [ "$cronanswer" = 'y' ]; then
		mkdir cron
		break
	fi
	if [ "$cronanswer" = 'n' ]; then
		rm ./lib/cron.php
		break
	fi
done

echo ''
while true; do
	echo -n 'install maintenace break pattern? [y/n] '
	read mbanswer
	[ "$mbanswer" = 'y' ] && break
	if [ "$mbanswer" = 'n' ]; then
		rm ./lib/maintenace-break.php
		break
	fi
done

echo ''
while true; do
	echo -n 'install adminpanel? [y/n] '
	read apanswer
	if [ "$apanswer" = 'y' ]; then
		echo 'admin/prevent-index'
		cd ./admin/lib
		ln -s ../../lib/prevent-index.php ./prevent-index.php
		ln -s ./prevent-index.php ./index.php

		echo 'admin/core'
		ln -s ../../lib/core.php ./core.php

		echo 'admin/login'
		cd login
		ln -s ../prevent-index.php ./index.php
		cd ..

		echo 'admin/menu'
		cd menu
		ln -s ../prevent-index.php ./index.php
		cd ..

		echo 'admin/skins'
		cd ../skins
		ln -s ../lib/prevent-index.php ./index.php
		cd ..

		echo 'admin/disabled'
		ln -s ./lib/prevent-index.php ./disabled.php

		cd ..
		break
	fi
	if [ "$apanswer" = 'n' ]; then
		rm -r ./admin
		break
	fi
done

echo ''; echo 'lib'
cd lib
ln -s prevent-index.php index.php
cd ..

echo 'lib/favicon'
cd lib/favicon
ln -s ../prevent-index.php index.php
cd ../..

echo 'media'
mkdir media
cd media
ln -s ../lib/prevent-index.php index.php
cd ..

echo 'skins'
cd skins
ln -s ../lib/prevent-index.php index.php
cd ..

echo 'pages'
cd pages
ln -s ../lib/prevent-index.php index.php
cd ..

echo ''
while true; do
	echo -n 'will you use simpleblog on php built-in server? [y/n] '
	read answer
	if [ "$answer" = 'y' ]; then
		echo 'htaccess'
		rm htaccess
		echo 'settings.php'
		rm settings.php
		echo; echo 'fill the settings in router.php'
		break
	fi
	if [ "$answer" = 'n' ]; then
		echo 'router.php'
		rm router.php
		echo '.htaccess'
		mv htaccess .htaccess
		echo; echo 'fill the settings in settings.php and .htaccess'
		break
	fi
done

echo; echo 'setup.bat'
rm setup.bat

echo 'setup-links.bat'
rm setup-links.bat

echo 'setup.sh'
rm setup.sh

exit 0
