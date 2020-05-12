#!/bin/sh
rm README.md
rm simpleblog-rss-feed-patch-full.zip

mkdir ./tmp
mkdir ./tmp/feed_cache

while true; do
	echo -n 'install admin module? [y/n] '
	read adminanswer
	[ "$adminanswer" = 'y' ] && break
	if [ "$adminanswer" = 'n' ]; then
		rm -r ./admin
		break
	fi
done

rm setup.bat
rm setup.sh
exit 0
