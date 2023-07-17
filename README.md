# Solar Furnace Interface

## Project Description
The solar furnace interface is a web tool developed as part of the 2022-2023 inter-speciality school project at Polytech Paris-Saclay.
The IT part of the project wich I was in charge of at the goal of creating an interface allowing to monitor the furnace datas and control the target temperature.
I decided to create a web tool and a mysql database, both hosted on a raspberry pi, coupled with an ethernet switch and an internal network, allowing any computer to simply plug in and acess the interface.
The key functionalities of this interface include:

- Simplicity of use
- Display of the temperature evolution through time
- Control of the furnace target temperature
- Display of the previous target temperature

## Project installation

Here is a serie of guides you can use to setup this project.

### Raspberry pi setup

1. Download Raspbian Lite from the following page: [https://raspberry-pi.fr/telechargements/](https://raspberry-pi.fr/telechargements/).

2. Download ISO flashing software such as Rufus from [https://rufus.ie/fr/](https://rufus.ie/fr/).

3. Format all partitions on the SD card and create a new complete simple volume using the Windows partition manager.

4. Use Rufus to flash the system image onto the SD card. This process may take some time.

5. Insert the SD card into the Raspberry Pi and power it on.

6. Wait for the boot process to complete. It may require multiple restarts (including forced ones by disconnecting and reconnecting the power cable). Wait until you see the login prompt. The default login for the Raspberry Pi is "pi" and the password is "raspberry".

7. Type "sudo raspi-config" to change the settings, including language settings (and keyboard configuration), Raspberry Pi password, and most importantly, enable SSH (in the interface menu).

8. Once the settings are changed and the Raspberry Pi has restarted, type "ifconfig" and look for the line showing "UP BROADCAST RUNNING MULTICAST". Just below that, you will find an IPv4 address (e.g., 169.254.31.36).

9. Use this address in an SSH terminal (Windows Terminal or software like PuTTY) on port 22. You should be able to connect to the Raspberry Pi using the username "pi" and the password you set.

If you forget the Raspberry Pi password, refer to [https://howtoraspberrypi.com/recover-password-raspberry-pi/](https://howtoraspberrypi.com/recover-password-raspberry-pi/). Please note that the default keyboard layout is QWERTY.

### Web Server Installation

Follow the tutorial "RPi A Simple Wheezy LAMP install - eLinux.org" at [https://www.tech2tech.fr/installation-de-lamp-sur-ubuntu-20-04/](https://www.tech2tech.fr/installation-de-lamp-sur-ubuntu-20-04/) to install the web server.

After following these tutorials, there should be a directory named "var/www/html" at the root of the Raspberry Pi, where you can place your web files. You can then access the website/files by connecting any computer to the local network (Ethernet switch) and entering `<ipaddress>/<webfilename>` (e.g., [http://169.254.31.36/test.php](http://169.254.31.36/test.php)).

### SSH Access from Any Computer

After connecting the computer to the local network (via an Ethernet cable to any port on the Ethernet switch):

1. Open Command Prompt (cmd).
2. Type `ssh pi@169.254.31.36`. (use the ip adress of your raspberry pi wich can be different from this example)
3. Enter "yes" if prompted (on a computer that has never connected before).
4. Enter the password "e".
5. Once connected (line with green text), type `cd ../../var/www/html` to navigate to the web folder.
6. Type `nano index.html` to edit the file containing the values read by the mbed.
7. Press Ctrl + X, then "y" to save.

**Note:** The IP address mentioned in this guide is an example address. To find the current IP address, connect the Raspberry Pi to a screen via HDMI and type "ifconfig" or "sudo ifconfig" in its console prompt.

### How to create an automatic service to launch at raspberry pi startup
This guide provides instructions on how to create a systemd service to run a Python script at startup. This setup is useful, for example, for a script that prints data from a database into an HTML file dedicated to the mbed.

1. Create a new service file by running the following command: `sudo nano /lib/systemd/system/myscript.service`
Replace `myscript` with a name that represents your script for the duration of the guide.
2. Add the following content to the service file, replacing `/path/to/your/python/script.py` with the actual path to your Python script, and `/usr/bin/python3` with the path to your Python interpreter. To find the Python interpreter path, you can run the command `which python3`:
```plaintext
[Unit]
Description=My Python Script Service
After=multi-user.target

[Service]
Type=idle
ExecStart=/usr/bin/python3 /path/to/your/python/script.py

[Install]
WantedBy=multi-user.target
```
Save the file and exit the editor by pressing Ctrl+X, then Y, and finally Enter.
3. Set the permissions for the service file using the following command: `sudo chmod 644 /lib/systemd/system/myscript.service`
4. Enable the service to start on boot by running the following commands:
`sudo systemctl daemon-reload`
`sudo systemctl enable myscript.service`

Now, the Python script will automatically start at boot. To monitor the logs, you can use the following command: `journalctl -u myscript.service -f`

You can also control the service using the following commands:
* `sudo systemctl start myscript.service`
* `sudo systemctl stop myscript.service`
* `sudo systemctl status myscript.service`

Remember to adapt the paths and names according to your specific setup.

## Futur improvment
A lot of consession have been made for this project and its structure. A lot could be modified and made much more efficient/cleaner. For example, I would like not to rely on infinitely looping python scripts. However this is what worked for us with some hard contraints, like the use of an old mbed operating system (instead of the newer mbed OS).
**Important** Everything in those guides is modifiable, and I encourage you to improve/change/redo parts or the entire project. This guide serves as a reference for the futur students that could take back this project.

## Credits
I would like to thank the Polytech Paris-Saclay teachers for their role in this project as well as the entire solar furnace 2022-2023 team.

## License
This project is licensed under the MIT License.

## Contact Information
If you have any questions, you can reach me via email at `maxime.fromont0@gmail.com`.
