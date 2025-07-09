<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>SMTP Test</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4">
    <tr>
      <td>
        <table align="center" width="600" cellpadding="20" cellspacing="0" bgcolor="#ffffff" style="margin:20px auto;border:1px solid #ddd;border-radius:8px;">
          <tr>
            <td align="center" style="font-size:22px;color:#333;">
              <img src="https://cdn.dyzulk.com/logo/favicon.png" alt="logo" height="75">
            </td>
          </tr>
          <tr>
            <td>
              <p style="font-size:16px;color:#333;">Selamat! Email ini berhasil dikirim menggunakan konfigurasi SMTP Anda.</p>
              <p style="font-size:14px;color:#555;">Ini adalah email uji coba untuk memastikan pengaturan SMTP Anda sudah benar.</p>
              <table cellpadding="5" cellspacing="0" style="margin-top:20px;width:100%;">
                <tr>
                  <td style="width:120px;color:#888;">Tanggal</td>
                  <td style="color:#333;"><?= $tanggal ?></td>
                </tr>
                <tr>
                  <td style="width:120px;color:#888;">Kepada</td>
                  <td style="color:#333;"><?= htmlentities($email_to) ?></td>
                </tr>
                <tr>
                  <td style="width:120px;color:#888;">Status</td>
                  <td style="color:green;"><strong>Berhasil</strong></td>
                </tr>
              </table>
              <hr style="margin:30px 0;border:none;border-top:1px solid #ddd;">
              <p style="font-size:13px;color:#aaa;">Email ini dikirim otomatis dari <strong><?= htmlentities($site_name) ?></strong>.</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>