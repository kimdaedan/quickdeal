<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Negosiasi Baru</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #e2e8f0; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 35px 20px; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 26px; font-weight: 850; letter-spacing: -0.025em;">
                                QUI<span style="color: #93c5fd;">CK</span>.DEAL
                            </h1>
                            <p style="margin: 8px 0 0 0; font-size: 13px; color: #dbeafe; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Notifikasi Negosiasi Baru</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 700; color: #0f172a;">Halo Admin,</h2>
                            <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #475569;">
                                Seorang klien telah mengajukan negosiasi harga baru untuk Surat Penawaran <strong>#{{ $negotiation->offer_id }}</strong> (klien: {{ $negotiation->offer->nama_klien }}).
                            </p>
                            
                            <!-- Rincian Grid -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">
                                        <span style="font-size: 11px; font-weight: 650; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Nama Pengaju / Perusahaan</span>
                                        <span style="font-size: 14px; font-weight: 700; color: #0f172a;">{{ $negotiation->nama_klien }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">
                                        <span style="font-size: 11px; font-weight: 650; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Kontak Klien</span>
                                        <span style="font-size: 14px; font-weight: 600; color: #3b82f6;">{{ $negotiation->kontak }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">
                                        <span style="font-size: 11px; font-weight: 650; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Harga Asli Penawaran</span>
                                        <span style="font-size: 14px; font-weight: 600; color: #64748b; text-decoration: line-through;">Rp {{ number_format($negotiation->offer->total_keseluruhan, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background-color: #eff6ff;">
                                        <span style="font-size: 11px; font-weight: 650; color: #2563eb; text-transform: uppercase; display: block; margin-bottom: 4px;">Harga Pengajuan Baru</span>
                                        <span style="font-size: 16px; font-weight: 800; color: #1e40af;">Rp {{ number_format($negotiation->harga_pengajuan, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                @if($negotiation->catatan)
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <span style="font-size: 11px; font-weight: 650; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Catatan Klien</span>
                                        <span style="font-size: 13px; font-style: italic; color: #475569; line-height: 1.5; display: block;">"{{ $negotiation->catatan }}"</span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                            
                            <!-- Button CTA -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 25px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url(route('histori.show', $negotiation->offer_id)) }}" style="display: inline-block; padding: 13px 30px; background-color: #2563eb; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); transition: background-color 0.2s ease;">
                                            Tinjau & Tanggapi Pengajuan
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0; font-size: 13px; line-height: 1.6; color: #64748b; text-align: center;">
                                Anda dapat menyetujui penawaran ini di dashboard admin, yang secara otomatis akan menerbitkan invoice baru dengan harga yang disepakati.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 25px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0 0 6px 0; font-size: 13px; font-weight: 600; color: #475569;">
                                PT. Tasniem Gerai Inspirasi
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #64748b;">
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
