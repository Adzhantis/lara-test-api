<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\TodoList;

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
Route::get('/todo/all', function (Request $request) {
    return TodoList::get();
});

Route::get('/todo/get', function (Request $request) {
    return TodoList::where(['id' => $request->get('id')])->firstOrFail();
});

Route::post('/todo/add', function (Request $request) {
    if (!$request->post('name')) {
        //return ->json(['name' => 'Please add name']);
    }
    $todo = new TodoList([
        'name' => $request->post('name'),
    ]);
    $todo->save();
    return $todo->toArray();
});

Route::post('/todo/edit', function (Request $request) {
    $todo = TodoList::where(['id' => $request->get('id')])->first();

    if ($todo) {
        if ($request->post('name')) {
            $todo->name = $request->post('name');
        }
        $todo->save();

        return $todo->toArray();
    } else {
        return response('', 404);
    }
});

Route::post('/todo/mark-as-done', function (Request $request) {
    $todo = TodoList::where(['id' => $request->get('id')])->first();
    if ($todo) {
        $todo->done = 1;
        return $todo->save();
    } else {
        return response('', 404);
    }
});


Route::post('/todo/mark-as-not-done', function (Request $request) {
    $todo = TodoList::where(['id' => $request->get('id')])->first();
    if ($todo) {
        $todo->done = 0;
        return $todo->save();
    } else {
        return response('', 404);
    }
});


Route::post('/todo/delete', function (Request $request) {

    $todo = TodoList::where(['id' => $request->post('id')])->first();
    if ($todo) {
        if ($todo->done) {
            return $todo->delete();
        } else {
            return response('', 422);
        }
    } else {
        return response('', 404);
    }
});
