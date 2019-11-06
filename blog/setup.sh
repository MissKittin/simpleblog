#!/bin/sh

echo 'media'
mkdir media
cd media
ln -s ../prevent-index.php index.php

echo 'skins'
cd ../skins
ln -s ../prevent-index.php index.php

echo 'skins/default'
cd default
ln -s ../../prevent-index.php index.php

echo 'admin'
cd ../../admin
ln -s ../prevent-index.php disabled.php

echo 'pages'
cd ../pages
ln -s ../prevent-index.php index.php

echo 'favicon'
cd ../favicon
ln -s ../prevent-index.php index.php

echo; cd ..
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
