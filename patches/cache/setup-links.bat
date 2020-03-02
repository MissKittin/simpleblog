@echo off
goto start

:noadmin
rmdir admin /S /Q
goto postadmin

:nocache
rmdir tag /S /Q
del lib\cacheTag.php
goto postcache

:start
del simpleblog-cache-patch-full.zip
md tmp

set /p cache="install cacheTag library? (y/[n]) "
if /i "%cache%" neq "y" goto nocache
cd tmp
md tag_cache
cd ..
:postcache

set /p admin="install admin module? (y/[n]) "
if /i "%admin%" neq "y" goto noadmin
cd admin
md lib
cd lib
mklink cacheIndex.php ..\..\lib\cacheIndex.php > NUL
if exist ..\..\lib\cacheTag.php mklink cacheTag.php ..\..\lib\cacheTag.php > NUL
cd ..
cd ..
:postadmin

cd tmp
md posts_cache
cd ..
del README.MD
del setup.sh
del setup-links.sh
del setup.bat

del setup-links.bat