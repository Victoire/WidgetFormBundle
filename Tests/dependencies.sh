#!/usr/bin/env bash

echo ">>> Installing Mailhog"

apt-get install -y wget
# Download binary from github
wget https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_linux_amd64
cp MailHog_linux_amd64 /usr/bin/mailhog
chmod +x /usr/bin/mailhog
nohup mailhog > /dev/null 2>&1 &
