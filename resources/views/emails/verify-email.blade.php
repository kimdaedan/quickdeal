<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Alamat Email Anda</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; color: #1e293b;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #e2e8f0; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); padding: 40px 20px; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.025em;">
                                QUI<span style="color: #60a5fa;">CK</span>.DEAL
                            </h1>
                            <p style="margin: 10px 0 0 0; font-size: 14px; color: #bfdbfe; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">Verifikasi Akun Baru</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #0f172a;">Halo, {{ $user->name }}!</h2>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #475569;">
                                Terima kasih telah mendaftar di <strong>Quick.Deal</strong>. Sebelum Anda dapat menggunakan seluruh layanan kami, kami perlu memastikan bahwa alamat email Anda valid.
                            </p>
                            
                            <!-- Button CTA -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}" style="display: inline-block; padding: 14px 32px; background-color: #2563eb; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2), 0 2px 4px -1px rgba(37, 99, 235, 0.1); transition: background-color 0.2s ease;">
                                            Verifikasi Email Saya
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #475569;">
                                Tautan verifikasi ini berlaku selama 60 menit. Jika Anda tidak merasa melakukan pendaftaran ini, Anda dapat mengabaikan email ini dengan aman.
                            </p>
                            
                            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 30px 0;">
                            
                            <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #64748b;">
                                Jika Anda mengalami kendala saat mengeklik tombol di atas, silakan salin dan tempel URL di bawah ini ke browser Anda:
                                <br>
                                <a href="{{ $url }}" style="color: #2563eb; text-decoration: none; word-break: break-all;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #475569;">
                                PT. Tasniem Gerai Inspirasi
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #64748b;">
                                &copy; {{ date('Y') }} Quick.Deal. Sistem Manajemen Terintegrasi.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
