<?php

use Illuminate\Support\Facades\Route;
use App\Notifications\SampleNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;

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

Route::get('/', function () {
    return view('send-notification');
});

Route::post('/send-notification', function () {
    // Notifiableトレイトを使用したクラスを作成
    $notifiable = new class {
        use Notifiable;

        public function routeNotificationForMail()
        {
            return 'test@example.com';  // 送信先メールアドレス
        }
    };

    $notification = new SampleNotification();

    // 通知を送信
    $notifiable->notify($notification);

    // ZIPファイルを作成
    $zip = new ZipArchive;
    $zipFilePath = sys_get_temp_dir().'/notifications2.zip';
    if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
        // メール通知の一時ファイルをZIPファイルに追加
        $zip->addFile($notification->getTempFilePath(), 'notification2.pdf');
        $zip->close();
    }

    return 'Notification sent! ZIP file is at '.$zipFilePath;
});

Route::get('/download-notifications', function () {
    $zipFilePath = sys_get_temp_dir().'/notifications2.zip';
    if (file_exists($zipFilePath)) {
        return response()->download($zipFilePath);
    } else {
        return 'No notifications to download.';
    }
});
