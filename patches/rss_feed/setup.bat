@echo off
goto start

:noadmin
rmdir admin /S /Q
goto postadmin

:start
del README.md
del simpleblog-rss-feed-patch-full.zip
md tmp
cd tmp
md feed_cache
cd ..

set /p admin="install admin module? (y/[n]) "
if /i "%admin%" neq "y" goto noadmin

:postadmin
del setup.sh
del setup.bat