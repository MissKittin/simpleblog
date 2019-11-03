@echo off

echo media
md media
cd media
copy ..\prevent-index.php index.php > NUL

echo skins
cd ..
cd skins
copy ..\prevent-index.php index.php > NUL

echo skins/default
cd default
copy ..\..\prevent-index.php index.php > NUL

echo admin
cd ..
cd ..
cd admin
copy ..\prevent-index.php disabled.php > NUL

echo pages
cd ..
cd pages
copy ..\prevent-index.php index.php > NUL

echo favicon
cd ..
cd favicon
copy ..\prevent-index.php index.php > NUL

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
move htaccess .htaccess
echo fill the settings in settings.php and .htaccess

:end
echo:
echo setup.sh
del setup.sh > NUL

echo setup.bat
del setup.bat > NUL