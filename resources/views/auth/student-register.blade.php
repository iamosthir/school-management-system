<!DOCTYPE html>
<html lang="ar">


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{ env("APP_NAME") }} - لوحة تسجيل الطالب</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset("backend/css/app.min.css") }}">
  <link rel="stylesheet" href="{{ asset("backend/bundles/bootstrap-social/bootstrap-social.css") }}">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset("backend/css/style.css") }}">
  <link rel="stylesheet" href="{{ asset("backend/css/components.css") }}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{ asset("backend/css/custom.css") }}">
  <link rel='shortcut icon' type='image/x-icon' href='{{ asset("backend/img/favicon.ico") }}' />
</head>

<body>
  <div class="loader"></div>
  <div id="studentRegister">
    <section class="section">
        <div class="container mt-5">
            <student-register></student-register>
        </div>
    </section>
  </div>
  <!-- General JS Scripts -->
  <script src="{{ asset("backend/js/app.min.js") }}"></script>
  <script src="{{ asset("js/app.js") }}"></script>
  <!-- JS Libraies -->
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="{{ asset("backend/js/scripts.js") }}"></script>
  <!-- Custom JS File -->
  <script src="{{ asset("backend/js/custom.js") }}"></script>
</body>


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
</html>