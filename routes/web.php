<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use PHPUnit\TextUI\XmlConfiguration\Group;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Symfony\Component\HttpKernel\Profiler\Profile;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/',[PageController::class,'home'])->name('home');

    //question
    Route::get('question/create',[QuestionController::class,'createQuestion'])->name('question.create');
    Route::post('question/create',[QuestionController::class,'storeQuestion'])->name('question.store');
    Route::get('question/detail/{slug}',[PageController::class,'questionDetail'])->name('question.detail');
    Route::get('question/delete/{id}',[QuestionController::class,'deleteQuestion'])->name('question.delete');
    Route::get('question/edit/{slug}',[QuestionController::class,'editQuestion'])->name('question.edit');
    Route::post('question/edit/{slug}',[QuestionController::class,'updateQuestion'])->name('question.update');

    Route::get('question/like/{id}',[PageController::class,'like'])->name('question.like');
    Route::get('question/disLike/{id}',[PageController::class,'disLike'])->name('question.disLike');
    Route::post('question/comment',[PageController::class,'createComment'])->name('question.createComment');
    Route::post('question/makeSolved',[QuestionController::class,'makeSolved'])->name('question.makeSolved');
    Route::post('question/save',[QuestionController::class,'saveQuestion'])->name('question.save');
    Route::post('question/unsave',[QuestionController::class,'unSaveQuestion'])->name('question.unSave');
    Route::post('question/search',[QuestionController::class,'searchQuestion'])->name('question.search');

    //profile
    Route::get('/profile/edit',[ProfileController::class,'editProfile'])->name('profile.edit');
    Route::post('/profile/updateProfile',[ProfileController::class,'updateProfile'])->name('profile.update');
    Route::get('/profile/editPassword',[ProfileController::class,'editPassword'])->name('profile.editPassword');
    Route::post('/profile/updatePassword',[ProfileController::class,'updatePassword'])->name('profile.updatePassword');
    Route::get('/profile/userQuestion',[ProfileController::class,'userQuestion'])->name('profile.userQuestion');
    Route::get('profile/savedQuestion',[ProfileController::class,'showSaveQuestion'])->name('showSaveQuestion');


});

Route::group(['namespace' => 'Admin','prefix' => 'admin'],function () {
    //admin dashboard
    Route::get('/dashboard',[UserController::class,'dashboard'])->name('dashboard');
    //user
    Route::get('/user/list',[UserController::class,'index'])->name('admin.userList');
    //question
    Route::get('/question/list',[AdminQuestionController::class,'questionList'])->name('admin.questionList');
    //tag
    Route::get('/tag/list',[AdminTagController::class,'tagList'])->name('admin.tagList');
    Route::post('tag/create',[AdminTagController::class,'createTag'])->name('admin.createTag');
    Route::get('tag/delete/{id}',[AdminTagController::class,'deleteTag'])->name('admin.deleteTag');
});



Route::get('/register',[AuthController::class,'create'])->name('register');
Route::post('/register',[AuthController::class,'store'])->name('postRegister');
Route::get('/login',[AuthController::class,'loginPage'])->name('login');
Route::post('/login',[AuthController::class,'userLogin'])->name('postLo
gin');
Route::get('/logout',[AuthController::class,'logout'])->name('logout');