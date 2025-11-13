<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Redirect...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #f9fafb; font-family: sans-serif;">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: "Sukses!",
            text: "{{ $message }}",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: '#388da8',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => {
            window.location.href = "{{ $redirectUrl }}";
        });
    });
</script>

</body>
</html>
