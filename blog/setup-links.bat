@echo off

echo cron
md cron

echo media
md media
cd media
mklink index.php ..\prevent-index.php > NUL

echo skins
cd ..
cd skins
mklink index.php ..\prevent-index.php > NUL

echo skins/default
cd default
mklink index.php ..\..\prevent-index.php > NUL

echo admin
cd ..
cd ..
cd admin
mklink disabled.php ..\prevent-index.php > NUL

echo pages
cd ..
cd pages
mklink index.php ..\prevent-index.php > NUL

echo favicon
cd ..
cd favicon
mklink index.php ..\prevent-index.php > NUL

echo tag
cd ..
cd tag
if exist "index-stripdown.php" del index-stripdown.php > NUL

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

echo setup-links.bat
del setup-links.bat > NUL