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

echo 'setup.sh'
cd ..
rm setup.sh

exit 0
