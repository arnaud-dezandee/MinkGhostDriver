#!/bin/sh

# Download phantomJs if needed
if [ ! -d phantomjs-1.9.7-linux-x86_64 ]; then
    wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-1.9.7-linux-x86_64.tar.bz2 -O - | tar -xj
fi
if [ ! -e ./bin/phantomjs ]; then
    ln -s ../phantomjs-1.9.7-linux-x86_64/bin/phantomjs ./bin/phantomjs
fi

echo "Launching PhantomJS WebDriver"
./bin/phantomjs --version
./bin/phantomjs -w > ./bin/phantom.js.log &
