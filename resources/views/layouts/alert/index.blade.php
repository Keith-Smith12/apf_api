<script src="{{ asset('painel/js/sweetalert2.all.min.js') }}"></script>
@if (session('success'))
<script>
    var session_success = "<?php echo session('success')['sms']; ?>"
    Swal.fire(
        session_success
        , ''
        , 'success'
    )
</script>
@endif

@if (session('error'))
<script>
    var session_error = "<?php echo session('error')['sms']; ?>"
    Swal.fire(
        session_error
        , ''
        , 'error'
    )
</script>
@endif

@if (session('warning'))
<script>
    var session_warning = "<?php echo session('warning')['sms']; ?>"
    Swal.fire(
        session_warning
        , ''
        , 'warning'
    )
</script>
@endif