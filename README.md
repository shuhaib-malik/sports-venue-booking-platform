# sports-venue-booking-platform

steps to install
1.clone this repositary

2.run composer install

3.run cp .env.example .env

4.run php artisan key:generate

5.create a database and change database in .env file

6.run php artisan migrate

7.run php artisan db:seed --class=DatabaseSeeder

8.php artisan serve
