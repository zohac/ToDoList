# ToDoList

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/b80f4876ec6c4eb4883e6820f3736191)](https://www.codacy.com/app/zohac/ToDoList?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=zohac/ToDoList&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/b80f4876ec6c4eb4883e6820f3736191)](https://www.codacy.com/app/zohac/ToDoList?utm_source=github.com&utm_medium=referral&utm_content=zohac/ToDoList&utm_campaign=Badge_Coverage)
[![Build Status](https://travis-ci.org/zohac/ToDoList.svg?branch=feature%2FIssue9)](https://travis-ci.org/zohac/ToDoList)

---

## About

An application to manage daily tasks.
Project 8 of the OpenClassrooms "Application Developer - PHP / Symfony" course.

## Requirements

+ PHP: ToDoList requires PHP version 7.1 or greater. [![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg?style=flat-square)](https://php.net/)
+ MySQL: for the database. [![Minimum MySQL Version](https://img.shields.io/badge/MySQL-%3E%3D5.7-blue.svg?style=flat-square)](https://www.mysql.com/fr/downloads/)
+ Composer: to install the dependencies. [![Minimum Composer Version](https://img.shields.io/badge/Composer-%3E%3D1.6-red.svg?style=flat-square)](https://getcomposer.org/download/)

## Installation

### Git Clone

You can also download the bilemo source directly from the Git clone:

    git clone https://github.com/zohac/bilemo.git todolist
    cd todolist

Give write access to the /var directory

    chmod 777 -R var

Then

    composer install

Configure the application by completing the file /app/config/parameters.yml

    php bin/console doctrine:schema:update --dump-sql
    php bin/console doctrine:schema:update --force

## Issues

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/zohac/ToDoList/issues)

## Author

Simon JOUAN
[https://jouan.ovh](https://jouan.ovh)
