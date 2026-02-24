modele = https://www.pathe.fr/films
home->details->login(->register)->booking->reservation
## Instalation

### Create a .env file
- copy paste and complete the following part in the newly created file :
DB_HOST = localhost;
DB_NAME = cinema;
DB_USERNAME = [WRITE YOUR USERNAME];
DB_PASSWORD = [WRITE YOUR PASSWORD];

## Lunch the web site in local
-Copy paste in the root of the project :
php -S localhost:8000 -t public

-Open :
http://localhost:8000/

## Work Partitioning

### Abdulmalek
- Login
- Register
- Home
- Header
- Router

### Alexandre
- Database
- Admin
- Booking
- Details
- Reservation
