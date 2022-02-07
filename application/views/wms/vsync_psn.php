<div style="padding: 10px">
	<div class="col-md-12 order-md-1">        
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="table-responsive" id="itm_container">
                    <table id="sup_tbldiff" class="table table-bordered table-sm table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" colspan="2">Trans No</th>                                
                                <th class="text-center" rowspan="2">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-success" id="sup_btnrefresh" title="Refresh"><i class="fas fa-sync"></i></button>
                                        <button class="btn btn-success" id="sup_btnsync">Synchronize</button>
                                    </div>
                                </th> 
                            </tr>
                            <tr>
                                <th class="text-center">WMS</th>
                                <th class="text-center">Mega</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$("#sup_btnrefresh").click(function (e) { 
    e.preventDefault();
    $("#sup_tbldiff tbody").html('');
    $.ajax({
        type: "get",
        url: "<?=base_url('SPL/getdiff')?>",        
        dataType: "json",
        success: function (response) {
            var ttlr = response.length;
            var tohtml = '';
            for(var i=0;i<ttlr;i++){
                tohtml += '<tr style="cursor:pointer">'+
                '<td>?</td>'+
                '<td>'+response[i].PPSN2_PSNNO.trim()+'</td>'+
                '<td></td>'+
                '</tr>';
            }
            $("#sup_tbldiff tbody").html(tohtml);
        }
    });
});

</script>