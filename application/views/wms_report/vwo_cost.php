<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row" id="rwo_cost_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >From</span>
                    <input type="text" class="form-control" id="rwo_cost_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >To</span>
                    <input type="text" class="form-control" id="rwo_cost_txt_dt2" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <button class="btn btn-sm btn-success" type="button" id="rwo_cost_btn_gen" onclick="rwo_cost_btn_gen_eCK(this)"><i class="fas fa-file-excel"></i></button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#rwo_cost_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rwo_cost_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rwo_cost_txt_dt").datepicker('update', new Date());
    $("#rwo_cost_txt_dt2").datepicker('update', new Date());

    function rwo_cost_btn_gen_eCK(p) {
        p.disabled = true
        p.innerHTML = '<i class="fas fa-file-excel fa-fade"></i>'
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>work-order/export-cost",
            data: { dateFrom : rwo_cost_txt_dt.value, dateTo: rwo_cost_txt_dt2.value , outputType:'spreadsheet' },
            success: function (response) {
                p.disabled = false
                p.innerHTML = '<i class="fas fa-file-excel"></i>'
                const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                const fileName = `WIP Cost report from ${rwo_cost_txt_dt.value} to ${rwo_cost_txt_dt2.value}.xlsx`
                saveAs(blob, fileName)
                alertify.success('Done')
            },
            xhr: function () {
                const xhr = new XMLHttpRequest()
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 2) {
                        if (xhr.status == 200) {
                            xhr.responseType = "blob";
                        } else {
                            p.innerHTML = '<i class="fas fa-file-excel"></i>'
                            p.disabled = false
                            xhr.responseType = "text";
                        }
                    }
                }
                return xhr
            }, error: function(xhr, xopt, xthrow)
            {
                alertify.warning(xthrow)
                p.innerHTML = '<i class="fas fa-file-excel"></i>'
                p.disabled = false
            }
        })
    }
</script>
