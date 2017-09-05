<script>
swal({
  title: "Sukses!",
  text: <?php echo $alertmsg; ?>,
  type: "success",
  timer: 3000,
  showCancelButton: true,
  showConfirmButton: true
}, function(){
      window.location.href = "<?php echo $contenturl; ?>";
});
</script>