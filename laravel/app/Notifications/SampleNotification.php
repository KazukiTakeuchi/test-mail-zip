<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Dompdf\Dompdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SampleNotification extends Notification
{
    use Queueable;

    private $tempFilePath;
    private $tempFile;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        // メールメッセージを作成
        $mailMessage = (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');

        // メールメッセージをレンダリング
        $html = $mailMessage->render();

        // レンダリングしたHTMLをPDFファイルとして保存
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $output = $dompdf->output();

        // PDFファイルを一時ファイルとして保存
        $tempFile = tmpfile();
        fwrite($tempFile, $output);

        // 一時ファイルの情報を保存
        $this->tempFilePath = stream_get_meta_data($tempFile)['uri'];
        $this->tempFile = $tempFile;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // メールメッセージを作成
        $mailMessage = (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function getTempFilePath()
    {
        return $this->tempFilePath;
    }
}
