<!DOCTYPE html>
<html lang="en">
<head>
  <title>Mercury Drug Email Template</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700|Montserrat:400,700">
  <style type="text/css">
  body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
  table { border-collapse: collapse !important; }
  body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
  </style>
</head>
<body style="background-color: #f0002d; margin: 0 !important; padding: 60px 0 60px 0 !important;">
  <table border="0" cellspacing="0" cellpadding="0" role="presentation" width="100%">
    <tr>
      <td style="background-color:#fff;"><br></td>
      <td bgcolor="white" width="600" style="border-radius: 0; color: grey; font-family: 'Josefin Sans', sans-serif; font-size: 18px; line-height: 28px; padding: 40px 40px;">
        <article>
          <img alt="mecurydrug logo "src="https://www.mercurydrug.com/public/images/md-main-logo.png" alt="" style="margin:0 auto; display: block;width: 250px;height:auto;">
          <p style="margin: 30px 0; font-family: 'Josefin Sans', sans-serif;">
            Hi {{$name}}! <br><br>
            Thank you for registering in Mercury Drug Online! Please click on the link below to verify your account.
          </p>
          <p style="margin: 30px 0;text-align:center;">
            <a href="{{url('/verify/'.$user_id)}}" target="_blank" style="font-size: 18px; font-family: 'Josefin Sans', sans-serif; color: #ffffff; text-decoration: none; border-radius: 8px; -webkit-border-radius: 8px; background-color: #f0002d; border: 20px solid #f0002d; width: 50%; margin:30px auto;display:block;">
              Verify Email Address
            </a>
          </p>
          <p>
            <a href="{{url('/verify/'.$user_id)}}" target="_blank" style="text-align: center;margin:0 auto;display:block;color:#f0002d;font-size: 13px; font-family: 'Josefin Sans', sans-serif; text-decoration: none;">
              Click here if the button doesn't work.
            </a>
          </p>
        </article>
      </td>
      <td  style="background-color:#fff;"><br></td>
    </tr>
  </table>
</body>
</html>
