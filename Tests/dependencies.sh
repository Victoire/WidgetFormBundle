#!/usr/bin/env bash

echo ">>> Installing Mailhog"

# Download binary from github
wget https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_linux_amd64
sudo cp MailHog_linux_amd64 /usr/bin/mailhog
sudo chmod +x /usr/bin/mailhog
nohup mailhog > /dev/null 2>&1 &
sed -i -e 's/firefox/chrome/g' behat.yml.dist