#!/bin/bash
# Delete the temp directory if present and coy wordpress
if [ -d tmp ]; then
  rm -rf tmp
fi
mkdir tmp
cp -r /opt/wordpress tmp

# Install and activate the plugin
zip abd.zip -x '/*tmp/*' -r .
wp-cli --path=tmp/wordpress plugin install --activate abd.zip

# Check the status of the installed plugin
# If there are syntax errors, this will fail because of a PHP error
# If it fails because it isn't present, it will give a `plugin not found` error
wp-cli --path=tmp/wordpress plugin status abd