<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SmtpSettingsController extends Controller
{
    /**
     * Show Web-based SMTP settings form.
     */
    public function edit()
    {
        $smtp = [
            'mail_host' => SystemSetting::getByKey('mail_host', config('mail.mailers.smtp.host')),
            'mail_port' => SystemSetting::getByKey('mail_port', config('mail.mailers.smtp.port', 587)),
            'mail_encryption' => SystemSetting::getByKey('mail_encryption', config('mail.mailers.smtp.encryption', 'tls')),
            'mail_username' => SystemSetting::getByKey('mail_username', config('mail.mailers.smtp.username')),
            'mail_password' => SystemSetting::getByKey('mail_password', config('mail.mailers.smtp.password')),
            'mail_from_address' => SystemSetting::getByKey('mail_from_address', config('mail.from.address', 'noreply@talentflow.com')),
            'mail_from_name' => SystemSetting::getByKey('mail_from_name', config('mail.from.name', 'TalentFlow System')),
        ];

        return view('admin.settings.smtp', compact('smtp'));
    }

    /**
     * Update dynamic SMTP settings in database.
     */
    public function update(Request $request)
    {
        $request->validate([
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer',
            'mail_encryption' => 'required|string|in:tls,ssl,null',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        foreach ($request->only([
            'mail_host', 'mail_port', 'mail_encryption', 'mail_username',
            'mail_password', 'mail_from_address', 'mail_from_name'
        ]) as $key => $val) {
            SystemSetting::setKey($key, $val);
        }

        \App\Models\AuditLog::record('smtp_updated', 'Memperbarui parameter SMTP server email ke: ' . $request->mail_host);

        return back()->with('success', 'Konfigurasi SMTP Email Server berhasil diperbarui!');
    }

    /**
     * Send a live test email via SMTP.
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            $toEmail = $request->test_email;
            Mail::raw("Halo! Ini adalah Email Uji Coba Server SMTP dari Platform Web Karir TalentFlow. Server SMTP Anda berfungsi 100% normal!", function ($msg) use ($toEmail) {
                $msg->to($toEmail)
                    ->subject("✅ Test Email SMTP Server TalentFlow");
            });

            return back()->with('success', "Email uji coba berhasil dikirim ke: {$toEmail}!");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal mengirim email uji coba: " . $e->getMessage());
        }
    }
}
