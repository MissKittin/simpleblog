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
rmdir admin\admin-cron /S /Q

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
copy ..\..\lib\prevent-index.php prevent-index.php > NUL
copy prevent-index.php index.php > NUL

echo admin\core
copy ..\..\lib\core.php core.php > NUL

echo admin\login
cd login
copy ..\prevent-index.php index.php > NUL
cd ..

echo admin\login\material
cd login
cd material
copy ..\..\prevent-index.php index.php > NUL
cd ..
cd ..

echo admin\menu
cd menu
copy ..\prevent-index.php index.php > NUL
cd ..

echo admin\menu\material
cd menu
cd material
copy ..\..\prevent-index.php index.php > NUL
cd ..
cd ..

echo admin\skins
cd ..
cd skins
copy ..\lib\prevent-index.php index.php > NUL
cd ..

echo admin\passwordChangeRequired
copy lib\prevent-index.php passwordChangeRequired.php > NUL

echo admin\disabled
copy lib\prevent-index.php disabled.php > NUL
cd ..

:postadmin
echo:

echo lib
cd lib
copy prevent-index.php index.php > NUL

echo lib\favicon
cd favicon
copy ..\prevent-index.php index.php > NUL
cd ..
cd ..

echo media
md media
cd media
copy ..\lib\prevent-index.php index.php > NUL

echo skins
cd ..
cd skins
copy ..\lib\prevent-index.php index.php > NUL
cd ..

echo pages
cd pages
copy ..\lib\prevent-index.php index.php > NUL
cd ..

echo:
set /p phpcli="will you use simpleblog on php built-in server? (y/[n]) "
if /i "%phpcli%" neq "y" goto apache

echo .router.php
move router.php .router.php > NUL
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

echo setup-links.sh
del setup-links.sh > NUL

echo setup-links.bat
del setup-links.bat > NUL

echo:
set /p cron="Hit ENTER"
echo:

echo setup.bat
del setup.bat > NUL