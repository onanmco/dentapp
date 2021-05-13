# DentApp
Dentapp is an open source, ongoing development project that provides a web portal for dentists to manage their clinical procedures. Some of the services that they are provided or going to be provided can be listed such as:
- Onboarding new patients.
- Setting and managing appointments
- Applying treatments
- Issuing medical prescriptions
- Manage invoices
- Notification centre (Bulk SMS and E-mail management)

DentApp is served on a fully-configurable infrastructure. Also it provides multilingual views determined by logged in user's language preference.


## Core Features & Requirements

- PHP version 7.3 or later
- Apache Web Server 2.4.46 or later
- MySQL (MariaDB) 5 or later


## Build & Deploy

DentApp can be either deployed manually or can be built on docker environment by using the dockerfiles that we have prepared already.

You can get the dockerfiles from the following link:
https://github.com/onanmco/dockerized-dentapp

Note that docker uses 2 special branches to fetch source from github called docker-master and docker-unstable. Make sure the master branch is merged to docker-master branch before run docker composer (Same routine is supposed to be followed for unstable and docker-unstable branches). 

## Entity-Relationship Diagram

![enter image description here](https://i.ibb.co/2nxg0sy/dentapp-er-diagram.png)

## Contribution
- Fork the repository
- Make your changes
- Open a Pull Request


