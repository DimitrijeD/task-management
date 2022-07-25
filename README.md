<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Task Management

Task Management is application for grouping tasks into projects.

- Each project may have many tasks.
- Tasks can be reordered by dragging and dropping.
- Projects can be edited by double-clicking project's name.
- Tasks can be edited by double-clicking task body.

## Homestead Installation

Add to Homestead.yaml 
folders:
    - map: ~/your_projects_dir/task-management
      to: /home/vagrant/task-management

sites:
    - map: task-management.test
      to: /home/vagrant/task-management/public  

databases:
    - task-management

Add to hosts file your Homestead.yaml IP
- 192.168.56.56 task-management.test

Clone code
- cd ~/your_projects_dir
- git clone https://github.com/DimitrijeD/task-management.git
- cd task-management

Run and provision Vagrant box
- cd ~/Homestead
- vagrant reload --provision
- vagrant ssh
- cd task-management

Setup project
- composer install
- cp env.example env
- configure your environment variables
- php artisan key:generate
- php artisan config:cache
- php artisan migrate

Run tests to make sure app is configured properly
- php artisan test


Install NPM dependencies
- npm install && npm run dev

Seed database
- php artisan db:seed

Login with seeded user on task-management.test/login 
- email: test@test
- password: password

## Issues

There are some small issues with current version:  

- Dropping tasks into different projects works, but you must refresh page to update view (solution requires VueX implementation).
- Dragging and dropping is choppy.
