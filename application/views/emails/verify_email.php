<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Verifikasi Email</title>
</head>
<body style="margin:0;padding:0;font-family:Arial, sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4">
    <tr>
      <td>
        <table align="center" width="600" cellpadding="20" cellspacing="0" bgcolor="#ffffff" style="margin:20px auto;border:1px solid #ddd;border-radius:8px;">
          <tr>
            <td align="center" style="font-size:24px;color:#333;">
              <strong><?= htmlentities($site_name) ?></strong>
            </td>
          </tr>
          <tr>
            <td>
              <p style="font-size:16px;color:#333;">Halo <?= htmlentities($username) ?>,</p>
              <p style="font-size:16px;color:#333;">Terima kasih telah mendaftar di <strong><?= htmlentities($site_name) ?></strong>.</p>
              <p style="font-size:16px;color:#333;">Silakan klik tombol di bawah untuk memverifikasi email Anda:</p>
              <p style="text-align:center;margin:30px 0;">
                <a href="<?= $verify_link ?>" target="_blank" style="background:#28a745;color:#fff;padding:12px 20px;text-decoration:none;border-radius:5px;display:inline-block;font-weight:bold;">Verifikasi Email</a>
              </p>
              <p style="font-size:14px;color:#555;">Atau salin dan tempel link berikut di browser Anda:</p>
              <p style="font-size:13px;color:#555;word-break:break-all;">
                <a href="<?= $verify_link ?>" target="_blank"><?= $verify_link ?></a>
              </p>
              <hr style="margin:30px 0;border:none;border-top:1px solid #ddd;">
              <p style="font-size:14px;color:#999;">Salam,<br><?= htmlentities($site_name) ?> Team</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>