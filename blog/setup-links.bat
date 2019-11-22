@echo off
goto start

:deladmin
rmdir admin /S /Q
goto postadmin

:start

echo:
set /p cron="install cron? (y/[n]) "
if /i "%cron%" neq "y" goto cron

md cron
goto postcron

:cron
del lib\cron.php

:postcron

echo:
set /p mbp="install maintenace break pattern? (y/[n]) "
if /i "%mbp%" neq "y" goto mbp

goto postmbp

:mbp
del lib\maintenace-break.php

:postmbp

echo:
set /p iiadmin="install admin panel? (y/[n]) "
if /i "%iiadmin%" neq "y" goto deladmin

cd admin

echo admin\lib
cd lib
mklink prevent-index.php ..\..\lib\prevent-index.php > NUL
mklink index.php prevent-index.php > NUL

echo admin\core
mklink core.php ..\..\lib\core.php > NUL

echo admin\login
cd login
mklink index.php ..\prevent-index.php > NUL
cd ..

echo admin\menu
cd menu
mklink index.php ..\prevent-index.php > NUL
cd ..

echo admin\skins
cd ..
cd skins
mklink index.php ..\lib\prevent-index.php > NUL

echo admin\disabled
cd ..
mklink disabled.php lib\prevent-index.php > NUL
cd ..

:postadmin
echo:

echo lib
cd lib
mklink index.php prevent-index.php > NUL

echo lib\favicon
cd favicon
mklink index.php ..\prevent-index.php > NUL
cd ..
cd ..

echo media
md media
cd media
mklink index.php ..\lib\prevent-index.php > NUL

echo skins
cd ..
cd skins
mklink index.php ..\lib\prevent-index.php > NUL
cd ..

echo pages
cd pages
mklink index.php ..\lib\prevent-index.php > NUL
cd ..

echo:
set /p phpcli="will you use simpleblog on php built-in server? (y/[n]) "
if /i "%phpcli%" neq "y" goto apache

echo htaccess
del htaccess > NUL
echo settings.php
del settings.php > NUL
echo fill the settings in router.php
goto end

:apache
echo router.php
del router.php > NUL
echo .htaccess
move htaccess .htaccess > NUL
echo fill the settings in settings.php and .htaccess

:end
echo:
echo setup.sh
del setup.sh > NUL

echo setup.bat
del setup.bat > NUL

echo:
set /p cron="Hit ENTER"
echo:

echo setup-links.bat
del setup-links.bat > NUL