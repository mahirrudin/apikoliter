<script>
	swal({
	title: "Gagal!",
	text: <?php echo $alertmsg; ?>,
	type: "warning",
	showCancelButton: false,
	confirmButtonClass: 'btn-warning',
	confirmButtonText: 'Tutup'
	});
</script>