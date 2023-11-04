<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/','Backend\DashboardController@index')->middleware('auth');

Route::get('/get/{id}', 'Backend\DashboardController@kanban_view');

Route::get('/clear/notification', 'Backend\DashboardController@clearNotification');

Route::get('/login','Backend\Auth\LoginController@showLoginForm')->name('login');
Route::post('/login/submit','Backend\Auth\LoginController@login')->name('login.submit');

Route::get('/logout','Backend\Auth\LoginController@logout')->name('logout');
Route::get('/show-forget-password-form','Backend\Auth\LoginController@showForgetPasswordForm')->name('showForgetPasswordForm');
Route::post('/send-email-for-reset','Backend\Auth\LoginController@sendEmailForReset')->name('sendEmailForReset');
Route::get('/password-reset/verify/{token}', 'Backend\Auth\LoginController@passwordresetverifyemail')->name('password.verify');
Route::get('/password-reset', 'Backend\Auth\LoginController@passwordResetForm')->name('resetPassword');
Route::post('/password-update', 'Backend\Auth\LoginController@passwordUpdate')->name('passwordUpdate');
Route::get('/login/google','Backend\Auth\LoginController@redirectToGoogle')->name('login.google');
Route::get('/login/google/callback','Backend\Auth\LoginController@handleGoogleCallback');

Route::get('/dashboard','Backend\DashboardController@index')->name('dashboard');
Route::get('/change-office-status','Backend\DashboardController@changeOfficeStatus')->name('change.office.status');


Route::resource('roles','Backend\RolesController',['names' => 'roles'])->middleware('auth');
Route::get('/roles-export','Backend\RolesController@export')->name('roles.export')->middleware('auth');

Route::resource('users','Backend\UsersController',['names' => 'users'])->middleware('auth');
Route::get('/users-export','Backend\UsersController@export')->name('users.export')->middleware('auth');
Route::get('/bulk-upload','Backend\UsersController@showBulkUpload')->name('users.show.bulkUpload')->middleware('auth');
Route::post('/users-import','Backend\UsersController@import')->name('users.import')->middleware('auth');
Route::get('/user/change-password/{id}','Backend\UsersController@changePassword')->name('users.changePassword')->middleware('auth');
Route::post('/user/update-password/{id}','Backend\UsersController@updatePassword')->name('users.updatePassword')->middleware('auth');
Route::get('/user/download-excel-template','Backend\UsersController@downloadExcelTemplate')->name('users.download.excelTemplate')->middleware('auth');
Route::get('/user/search','Backend\UsersController@search')->name('users.search')->middleware('auth');

Route::resource('teams','Backend\TeamsController',['names' => 'teams'])->middleware('auth');
Route::get('team/task/{id}','Backend\TeamsController@getTeamTask',['names' => 'team-task']);
Route::get('/teams-export','Backend\TeamsController@export')->name('teams.export')->middleware('auth');
Route::get('/teams-card-view','Backend\TeamsController@cardView')->name('teams.cardView');
Route::post('/team/search','Backend\TeamsController@search')->name('team.search');
Route::post('/individual/search','Backend\TeamsController@searchIndividual')->name('team.individual');

Route::resource('departments','Backend\DepartmentsController',['names' => 'departments'])->middleware('auth');

Route::resource('companys','Backend\CompanysController',['names' => 'companys'])->middleware('auth');

Route::resource('tags','Backend\TagsController',['names' => 'tags'])->middleware('auth');

Route::resource('tasks','Backend\TasksController',['names' => 'tasks'])->middleware('auth');

Route::resource('myRequestTasks','Backend\MyRequestTasksController',['names' => 'myRequestTasks'])->middleware('auth');
Route::post('/my-request-task/search','Backend\MyRequestTasksController@search')->name('myRequestTask.search');
Route::get('/my-request-task-export','Backend\MyRequestTasksController@export')->name('myRequestTasks.export');
Route::get('/request-files-download/{taskId}','Backend\MyRequestTasksController@downloadFile')->name('request.file.download')->middleware('auth');
Route::post('/request-comment/{id}','Backend\MyRequestTasksController@commentSave')->name('requestTask.comment');
Route::post('/request-task-comment-edit/{id}','Backend\MyRequestTasksController@commentEdit')->name('requestTask.comment.edit');
Route::post('/request-task-comment-delete/{id}','Backend\MyRequestTasksController@commentDelete')->name('requestTask.comment.delete')->middleware('auth');
Route::post('/request-task-reply-delete/{id}','Backend\MyRequestTasksController@replyDelete')->name('requestTask.reply.delete')->middleware('auth');
Route::post('/request-task-reply/{id}','Backend\MyRequestTasksController@replySave')->name('requestTask.reply');
Route::post('/request-task-reply-edit/{id}','Backend\MyRequestTasksController@replyEdit')->name('requestTask.reply.edit');
Route::get('/request-task-download-commentFile/{commentId}','Backend\MyRequestTasksController@downloadCommentFile')->name('requestTask.download.commentFile')->middleware('auth');
Route::get('/request-task-download-replyFile/{replyId}','Backend\MyRequestTasksController@downloadReplyFile')->name('requestTask.download.replyFile')->middleware('auth');

Route::resource('otherRequestTasks','Backend\OtherRequestTasksController',['names' => 'otherRequestTasks'])->middleware('auth');
Route::post('/other-request-task-approve/{id}','Backend\OtherRequestTasksController@approveRequest')->name('otherRequestTasks.approve');
Route::post('/other-request-task/search','Backend\OtherRequestTasksController@search')->name('otherRequestTask.search');
Route::post('/other-request-task-reject','Backend\OtherRequestTasksController@rejectRequest')->name('otherRequestTasks.reject');
Route::get('/other-request-task-export','Backend\OtherRequestTasksController@export')->name('otherRequestTasks.export');


Route::post('/uploadFiles','Backend\TasksController@fileUpload');
Route::post('/uploadFilesComment','Backend\TasksController@fileUploadComment');
Route::post('/uploadFilesReply','Backend\TasksController@fileUploadReply');
Route::get('/download/{taskId}','Backend\TasksController@downloadFile')->name('download')->middleware('auth');
Route::get('/download-commentFile/{commentId}','Backend\TasksController@downloadCommentFile')->name('download.commentFile')->middleware('auth');
Route::get('/download-replyFile/{replyId}','Backend\TasksController@downloadReplyFile')->name('download.replyFile')->middleware('auth');
Route::get('/task-by-team/{id}','Backend\TasksController@taskByTeam')->name('taskByTeam')->middleware('auth');

Route::post('/task/search','Backend\TasksController@search')->name('task.search');
Route::post('/task/searchByTeam','Backend\TasksController@searchByTeam')->name('task.searchByTeam');
Route::post('/subTask-delete/{id}','Backend\SubTasksController@subTaskDestroy')->name('subTask.destroy')->middleware('auth');

Route::post('/comment/{id}','Backend\TasksController@commentSave')->name('comment');
Route::post('/comment-edit/{id}','Backend\TasksController@commentEdit')->name('comment.edit');
Route::post('/comment-delete/{id}','Backend\TasksController@commentDelete')->name('comment.delete')->middleware('auth');
Route::post('/reply-delete/{id}','Backend\TasksController@replyDelete')->name('reply.delete')->middleware('auth');
Route::post('/reply/{id}','Backend\TasksController@replySave')->name('reply');
Route::post('/reply-edit/{id}','Backend\TasksController@replyEdit')->name('reply.edit');
Route::get('/task/view/{id}','Backend\TasksController@changeRequestStatus')->name('task.changeRequestStatus')->middleware('auth');
Route::post('/task/updateRequestStatus/{id}','Backend\TasksController@updateRequestStatus')->name('task.updateRequestStatus')->middleware('auth');
Route::post('/task/updateTaskStatus/{id}','Backend\TasksController@updateTaskStatus')->name('task.updateTaskStatus')->middleware('auth');
Route::get('/tasks-export','Backend\TasksController@export')->name('tasks.export')->middleware('auth');
Route::get('/tasks-export-by-team/{id}','Backend\TasksController@exportByTeam')->name('tasksByTeam.export')->middleware('auth');
Route::get('/task-reassign/{id}','Backend\TasksController@reassign')->name('tasks.reassign')->middleware('auth');
Route::post('/task-reassign-update/{id}','Backend\TasksController@reassignUpdate')->name('tasks.reassign.update')->middleware('auth');
Route::get('/overdue-tasks','Backend\TasksController@overdueTasks')->name('tasks.overdue')->middleware('auth');
Route::get('/overdue-tasks-after-2-days','Backend\TasksController@overdueTasksAfter2Days')->name('tasks.overdueAfter2Days')->middleware('auth');
Route::get('/update-all-task-status','Backend\TasksController@updateAllTaskStatus')->name('tasks.updateAllTaskStatus');

Route::post('/nuzz/{user_id}/{task_id}','Backend\TasksController@nudge')->name('tasks.nuzz')->middleware('auth');

Route::get('/subtask/create/{id}','Backend\SubTasksController@create')->name('subtasks.create')->middleware('auth');
Route::post('/subtask/store/{id}','Backend\SubTasksController@store')->name('subtasks.store')->middleware('auth');

Route::post('/request/{id}','Backend\RequestController@requestSave')->name('request.save')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/sso-login','SSOLoginController@index')->name('ssoLogin');
Route::get('/sso-response','Backend\Auth\LoginController@ssoResponse')->name('sso.login');
Route::post('/save-token', [App\Http\Controllers\Backend\Notification\PushNotificationController::class, 'saveToken'])->name('save-token');

Route::resource('/task-analytics', 'Backend\TaskAnalyticsController')->middleware('auth');
Route::get('/fetch-all-data', 'Backend\TaskAnalyticsController@fetchAllData');
Route::get('/fetch-data', 'Backend\TaskAnalyticsController@fetchData');
Route::get('/fetch-member', 'Backend\TaskAnalyticsController@fetchMember');
Route::post('/fetch-tasks-of-user', 'Backend\TaskAnalyticsController@fetchTasksOfUser')->name('taskAnalytics.fetchTasksOfUser');
Route::get('/fetch-user-under-me', 'Backend\TaskAnalyticsController@fetchUser');
Route::get('/users-under-me-export','Backend\TaskAnalyticsController@export')->name('users.under.me.export')->middleware('auth');
Route::get('/member-export','Backend\TaskAnalyticsController@exportMember')->name('members.export')->middleware('auth');
Route::get('/singleUser-export/{id}','Backend\TaskAnalyticsController@singleUserExport')->name('single.user.export')->middleware('auth');
Route::get('/task-show/{id}','Backend\TaskAnalyticsController@showTask')->name('task_analytics.task.show')->middleware('auth');

Route::post('/ckeditor/upload','Backend\CKEditorController@upload')->name('ckeditor.upload');

// shovon modification starts

Route::get('/report','Backend\ReportController@getReport')->name('report.index');
Route::post('/report/export','Backend\ReportController@getExportedReport')->name('report.export');

Route::get('/notification-module','Backend\NotificationModuleController@index')->name('notification.module.index');
Route::post('/notification-module/save','Backend\NotificationModuleController@store')->name('notification.module.store');

// shovon modification ends

Route::get('/worklogs','Backend\WorkLogController@index')->name('worklogs.index')->middleware('auth');
Route::get('/worklogs/create','Backend\WorkLogController@create')->name('worklogs.create')->middleware('auth');
Route::get('/worklogs/edit/{date}','Backend\WorkLogController@edit')->name('worklogs.edit')->middleware('auth');
Route::post('/worklogs/store','Backend\WorkLogController@store')->name('worklogs.store')->middleware('auth');
Route::post('/worklogs/update','Backend\WorkLogController@update')->name('worklogs.update')->middleware('auth');
Route::get('/worklogs/destroy','Backend\WorkLogController@destroy')->name('worklogs.destroy');
Route::get('/worklogs/show/{date}','Backend\WorkLogController@show')->name('worklogs.show')->middleware('auth');
Route::get('/worklog-search','Backend\WorkLogController@search')->name('worklogs.search');
Route::get('/worklogs-export','Backend\WorkLogController@export')->name('worklogs.export')->middleware('auth');
