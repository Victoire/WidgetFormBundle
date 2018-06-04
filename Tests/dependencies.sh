#!/usr/bin/env bash

echo ">>> Installing MailCatcher"

sudo apt install -y build-essential libsqlite3-dev ruby-dev
gem install mailcatcher
echo "sendmail_path = /usr/bin/env $(which catchmail) -f 'www-data@localhost'" >> /etc/php/7.1/mods-available/mailcatcher.ini
phpenmod mailcatcher
/usr/bin/env $(which mailcatcher) --ip=0.0.0.0

echo ">>> Update Behat config"

# Get the Namespace of EmailContext file with regular expression
# Alter the namespace to remove ';' then use triple backslashes instead of a single backslash
namespace="$(cat ../../../Tests/Context/EmailContext.php | sed -rn 's/namespace ((\\{1,2}\w+|\w+\\{1,2})(\w+\\{0,2})+)/\1/p' | sed -r 's/;+$//' | sed -e 's|\\|\\\\\\|g' )"
# Add EmailContext path in the behat.yml.dist file to load the context
sed -i "s@contexts:@contexts: \n                 - $namespace\\\EmailContext@" behat.yml.dist
rm ../../../Tests/Context/EmailContext.php
sed -i "s@extensions:@extensions: \n         Alex\\\MailCatcher\\\Behat\\\MailCatcherExtension\\\Extension:\n            url: http://fr.victoire.io:1080\n            purge_before_scenario: true@" behat.yml.dist
# Use Chrome instead of firefox
sed -i -e 's/firefox/chrome/g' behat.yml.dist

echo ">>> Install dev Dependencies"
php -d memory_limit=-1 /usr/local/bin/composer require alexandresalome/mailcatcher
