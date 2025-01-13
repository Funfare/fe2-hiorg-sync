
## 📖  About FE2 - HiOrg Synchronisation

This application is a synchronisation tool for the aPager Pro synchronsiation between the HiOrg-Server and the FE2 alamos server. It is based on the Laravel Framework and uses the HiOrg-Server API to get the data from the HiOrg-Server.

## 🛠 Built With
  <ul>
    <li><a href="https://laravel.com/">Laravel (PHP framework)</a></li>
    <li><a href="https://vitejs.dev/">vite.js (asset handling)</a></li>
    <li><a href="https://getbootstrap.com/">Bootstrap (FrontEnd framework)</a></li>
  </ul>

## 💻 Getting Started
1. make sure, that your local php version is 8.2, and you got composer v2 installed
2. make sure, that Node (v16+) and npm is installed you can check with `node -v` and `npm -v`. You can easily install the latest version of Node and NPM using simple graphical installers from [the official Node website](https://nodejs.org/en/download/).
3. install the XAMPP Stack and create a mysql Database (you only need mysql, no apache)
4. run `composer install`
5. copy the `.env.example` to `.env` and put in your database connection details
6. run `php artisan key:generate`
7. run `php artisan migrate --seed`
8. run `npm i && npm run build`
9. run `php artisan serve`
10. have fun :)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
