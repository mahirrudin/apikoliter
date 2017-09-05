<script>
function confirmRestart() {
    swal({
        title: "Apakah anda yakin ?",
        text: "Anda akan melakukan restart mesin server!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, restart!",
        closeOnConfirm: false
    }, 

    function (isConfirm) {
        if (isConfirm) {
            window.location.replace("<?php echo "manageserver.php?power=".$rowsvrdata['txtidsvr']."&restart"; ?>");
        } else {
            swal("Cancel", "Proses restart tidak dilakukan! :)", "error");
        }
    });
}
</script>