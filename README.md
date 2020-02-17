# BombSweeper challenge app

## Overwiew

This was a challenging project, I could cover the most of the requirements being asked according the time I had to solve them. I called this app BombSweeper to make it a little more original than the classic game for windows.
I wanted to focus first in frontend features that could show something working and then backend features.

## Walktrough

I didn't know how to play Minesweeper so I had familiarize first with it in order to understand how to code it.
This was more a frontend project than backend, I used Vuejs framework but I could used just vanilla javascript and the result would be the same, also I wanted to use some features like two way binding and computed properties that might come handy. For backend I used Laravel framework and sqlite as database to store users.

## Important decisions

- First challenge was to make a board for the game, I wanted to use a two dimensional array for this but it doesn't exist such thing in javascript, what does exist is an array inside an array.
- Then I created a type Cell inside every position of the inner array that could hold properties like hidden, value, icon, flagged and coordinates to handle what to show and how to get every cell.
- Another challenge was to reveal empty surrounding cells, for this I used recursion.
- I started the project using vue cli and then I had to integrate the backend part with Laravel framework, for this I had to move everything inside the on proggress frontend part to the laravel folder structure.

## Biggest challenges

- Deploy the app for heroku, I got some 500 erros and I couldn't see any logs with the detail of the error.

# Setup

```
composer install
npm install
php artisan serve
npm run development
// OR
npm run watch
```
