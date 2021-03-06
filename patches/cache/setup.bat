@echo off
goto start

:noadmin
rmdir admin /S /Q
goto postadmin

:nocache
del lib\viewTag.php
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
REM md lib
cd lib
copy ..\..\lib\cacheIndex.php cacheIndex.php > NUL
if exist ..\..\lib\cacheTag.php copy ..\..\lib\cacheTag.php cacheTag.php > NUL
cd ..
cd ..
:postadmin

cd tmp
md posts_cache
cd ..
del README.MD
del setup.sh
del setup-links.sh
del setup-links.bat

del setup.bat