<!-- BEGIN btn-scroll-top -->
<a href="#" data-click="scroll-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
<!-- END btn-scroll-top -->
</div>
<!-- END #app -->


<!-- ================== BEGIN core-js ================== -->
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>
<!-- ================== END core-js ================== -->

<!-- ================== BEGIN page-js ================== -->
<script src="<?= base_url(); ?>assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
<script src="<?= base_url(); ?>assets/js/demo/highlightjs.demo.js"></script>
<!-- ================== END page-js ================== -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {

        $('#formReqDocument').on('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            Swal.fire({
                title: 'Yakin ingin mengajukan?',
                text: "Pastikan data sudah benar!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, kirim & Ajukan!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= base_url(route_to('home.req_doc')); ?>",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",

                        beforeSend: function() {
                            Swal.fire({
                                title: 'Loading...',
                                text: 'Sedang mengirim data',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },

                        success: function(res) {

                            if (res.status === 'success') {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pengajuan Berhasil !',
                                    text: res.message
                                });

                                // reset form
                                $('#formReqDocument')[0].reset();

                                // close modal
                                $('#createDocument').modal('hide');

                                // reload data (optional)
                                // location.reload();

                            } else {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: res.message
                                });

                            }
                        },

                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan server'
                            });
                        }

                    });

                }

            });

        });

    });
</script>