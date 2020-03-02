#!/bin/sh
rm simpleblog-cache-patch-full.zip
mkdir ./tmp

while true; do
	echo -n 'install cacheTag library? [y/n] '
	read taganswer
	if [ "$taganswer" = 'y' ]; then
		cd ./tmp
		mkdir ./tag_cache
		cd ..
		break
	fi
	if [ "$taganswer" = 'n' ]; then
		rm -r ./tag
		rm ./lib/cacheTag.php
		break
	fi
done

while true; do
	echo -n 'install admin module? [y/n] '
	read adminanswer
	if [ "$adminanswer" = 'y' ]; then
		cd admin
		mkdir lib
		cd lib
		cp ../../lib/cacheIndex.php ./cacheIndex.php
		[ -e ../../lib/cacheTag.php ] && cp ../../lib/cacheTag.php ./cacheTag.php
		cd ../..
		break
	fi
	if [ "$adminanswer" = 'n' ]; then
		rm -r ./admin
		break
	fi
done

cd ./tmp
mkdir ./posts_cache
cd ..
rm README.MD
rm setup.bat
rm setup-links.sh

rm setup.sh
exit 0
