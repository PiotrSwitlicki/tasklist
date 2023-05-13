<?php

use App\Http\Controllers\TaskController;

Route::put('/{id}/updatestatus', [TaskController::class, 'updateStatus']);