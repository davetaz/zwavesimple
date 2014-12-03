zwavecontrol
============

This lightweight library is designed to sit on top of the already available lightweight z-wave server that comes part of the lightscontrol bundle on a raspberry-pi. Lightscontrol is a bit much so this simple version provides simple on/off functionality for z-wave devices by calling this app from a simple mobile app.

To use:
=======

Create a file called code.json somewhere that is NOT publically accessible and put the following in it (change the code)

{ "code": "CHANGEME" }

Edit config.json and add the absolute path to this file at the top where it says to.

While you are in config.json you can add all the names for your devices if you need to override the defaults. This is not necessary and you can just remove all the data.

To use the library simply host it on the same device as your zwave controller with lightscontrol and point your browser at http://your-pi/wherever-you-put-it/?code=CHANGEME

Security is your problem not mine! 
