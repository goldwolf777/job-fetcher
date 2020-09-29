## Jobilla Task

1. Create a fresh Laravel installation, pull in Vue.js for the front-end part
2. Fetch all English jobs from the Public Employment and Business Services’ API, and store
them in a MySQL DB table (https://paikat.te-palvelut.fi/tpt-api/tyopaikat?englanti=true)
3. Fetch the following attributes: Job title (otsikko), description (kuvausteksti), created_at
(ilmoituspaivamaara), company (tyonantajanNimi)
4. Display the fetched Jobs on a simple web page (table, grid, whatever you prefer)
5. Implement sorting or searching the data on the page somehow (please perform the data
sorting/searching on the server-side, not purely on the front-end)

## Steps undertaken

1) Created a fresh Laravel installation, and looked at how Laravel and PHP works together + what composer does
2) Added a Vue.js dependency and made it work 
3) Created the logic to fetch the jobs from the API
4) Created the logic to save the fetched data
5) Found a problem with created_at and updated_at not getting updated and fixed it
6) Added a new column external_id to be able to do upserts
7) Found a library to be able to do an upsert
8) Added logic for pagination,sorting,search and update 
9) Added a new Component to Vue.js and vue-good-table to show the data and have pagination etc out of the box
10) Updated logic to be able to re-sync with the API data (this means that data will be updated in the database)
11) Refactoring, adding Repository classes (not sure if that's the right way to use it)
12) Moved Job Fetching to a separate implementation class and interface
13) Added tests

## Assumptions made

Rather than just adding data once to the database and using that data (or just getting data from API all the time),
extra logic was added, to be able to resync the data from the api to the database on the click of the button.

Rather than creating my own table, ive used vue-good-table which is similar to material-table for react and allows remote data.

Id of the jobs from the api was saved as external_id to be able to do the upsert (meaning that we are not going to spam the database)

I didn't want to call the db on each row (and upsert seemed like the best solution)

Standard classes that came with Laravel install were left for future if User authentication would be needed

Sadly not many tests were created but that's due to some issues about which you can find out below

Jobila logo was used in the Navbar and the logo does not belong to me and all rights are reserved by Jobilla

## Issues 

I was unable to set up the dubug on PHPStorm IDE (spent good 1-2hrs) for some reason. Xdebug is installed, PHPStorm seems to be setup
the browser extension for XDebug was also set. This made development rather not straight forward and not being able to see and evaluate
how the new language to me works was rather hard.

Not many tests were written again due to being unable to debug to see how mocked objects work, and how to be able to mock methods and some classes

## Improvements

As we already have the User object and authentication out of the box with Laravel I would add authentication to this application
Columns in table should be aligned better, such as maybe have less space for Description of the job and give more space to the Date.
Maybe NavBar could be fixed (somehow sticky parameter didn't work on it)
More Unit Tests should be added on the backend side

## Conclusion

Overall an interesting task, and fun when you never done PHP and Vue.js. PHP has definitely went further from where it used to me 5+ years ago.
Vue.js seems rather similar to React except few things were interesting like the way child communicates with the parent component.

