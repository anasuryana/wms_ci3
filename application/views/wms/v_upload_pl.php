<div style="padding: 5px">
    <div class="container-fluid">
        <div class="row">
            <div class="col mb-1">
                <div class="input-group">
                    <input type="file" id="rcv_pl_file_upload" name="file_upload[]" class="form-control" multiple accept=".xls,.xlsx">
                    <button id="rcv_pl_btn_startimport" onclick="rcv_pl_btn_startimport_on_click(this)" class="btn btn-primary btn-sm">Start Importing</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function rcv_pl_btn_startimport_on_click(pThis) {
        const formData = new FormData();
        const files = $('#rcv_pl_file_upload')[0].files;

        if (files.length === 0) {
            alertify.warning(`Please select file`)
            return
        }

        for (let i = 0; i < files.length; i++) {
            formData.append('file_upload[]', files[i]);
        }

        pThis.disabled = true

        if(!confirm('Are you sure ?')) {
            return
        }
        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>receiving/upload-pl",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                alertify.message(response.message)
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                pThis.disabled = false               
            }
        });
    }
</script>