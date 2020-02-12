<?php

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware('auth:api')->prefix('/game')->group( function() {
Route::prefix('/game')->group( function() {

  // Returns a new board with requested cols and rows with zeroed values
  Route::get('new', function() {
    $cols = request()->get('rows');
    $rows = request()->get('cols');

    $board[][] = [[]];
    for($y = 0; $y < $cols; $y++) {
      for($x = 0; $x < $rows; $x++) {
        $board[$y][$x] = 0;  
      }
    }
    return $board;
  });
}
);
