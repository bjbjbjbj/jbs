<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| backstage Routes
|--------------------------------------------------------------------------
|
*/
Route::group([], function () {
    Route::any("/", "BsController@login");
    Route::any("/login", "BsController@login");
    Route::any("/logout", "BsController@logout");
    Route::get("/info", "BsController@info");
    Route::any("/article/publish", "BsController@articlePublish");
});
