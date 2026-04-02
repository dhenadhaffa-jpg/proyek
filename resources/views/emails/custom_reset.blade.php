<!DOCTYPE html>
<html>
<head>
    <title>Reset Password Outfitology</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; padding: 40px 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        
        <h1 style="text-align: center; color: #0A192F; margin-top: 0; font-size: 28px; letter-spacing: 2px;">OUTFITOLOGY</h1>
        <hr style="border: none; border-top: 2px solid #eee; margin-bottom: 30px;">
        
        <h3 style="color: #333; font-size: 20px; margin-bottom: 15px;">Halo, Pelanggan Setia Outfitology</h3>
        <p style="color: #555; font-size: 16px; line-height: 1.6; margin-bottom: 25px;">
            Kami menerima permintaan untuk mengatur ulang kata sandi <i>(reset password)</i> untuk akun <strong>Outfitology Store</strong> Anda. Silakan klik tombol di bawah ini untuk membuat kata sandi yang baru:
        </p>
        
        <div style="text-align: center; margin: 35px 0;">
            <a href="{{ $url }}" style="background-color: #0A192F; color: #ffffff; padding: 14px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px; display: inline-block;">RESET PASSWORD</a>
        </div>
        
        <p style="color: #555; font-size: 15px; line-height: 1.6; margin-bottom: 30px;">
            Tautan ini hanya berlaku selama <strong>60 menit</strong>. Jika Anda tidak merasa melakukan permintaan perubahan kata sandi, silakan abaikan pesan ini. Keamanan akun Anda akan tetap terjaga.
        </p>
        
        <div style="border-top: 1px solid #eee; padding-top: 20px; text-align: center;">
            <p style="color: #999; font-size: 12px; margin: 0;">
                &copy; {{ date('Y') }} Outfitology Store. All rights reserved.
            </p>
            <p style="color: #aaa; font-size: 11px; margin-top: 5px;">
                Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda: <br>
                <span style="color: #0A192F;">{{ $url }}</span>
            </p>
        </div>
    </div>
</body>
</html>