<div style="padding: 10px">
	<div class="container-fluid">       
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <button class="btn btn-sm btn-outline-primary" id="si_conf_btn_sync" onclick="si_conf_btn_sync_e_click()"><i class="fas fa-sync"></i></button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                
                    <table id="si_conf_tbl" class="table table-sm table-striped table-bordered table-hover compact" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">TX ID</th>
                                <th class="text-center">Customer DO</th>
                                <th class="text-center">Customs Date</th>
                                <th class="text-center">Remark</th>
                                <th class="text-center align-middle">Str.Loc</th>
                                <th ></th>
                            </tr>
                        </thead>
                        <tbody>                        
                        </tbody>
                    </table>                
            </div>
        </div>
	</div>
</div>
<script>
    var si_conf_DTABLE_psn;
    function si_conf_on_GridActionButton_Click(event) {        
        if(confirm("Is this DO ("+event.data.DLV_ID+") delivered ?")){
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/confirm_delivery')?>",
                data: {indo: event.data.DLV_ID},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==1){
                        alertify.success(response.status[0].msg);
                        si_conf_btn_sync_e_click();
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }
    function si_conf_btn_sync_e_click(){        
        document.getElementById('si_conf_btn_sync').disabled=true;
        si_conf_DTABLE_psn =  $('#si_conf_tbl').DataTable({
            select: true,
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
            ajax: {
                url : '<?=base_url("DELV/getunconfirmed")?>',                  
                dataSrc: function(json){
                    document.getElementById('si_conf_btn_sync').disabled=false;
                    return json.data;
                }
            },
            columns:[
                { "data": 'DLV_ID'},
                { "data": 'CUSTDO'},
                { "data": 'DLV_BCDATE'},
                { "data": 'DLV_RMRK'},
                { "data": 'PLANT'},
                { "data": 'PLANT', 
                    render : function(data, type, row){
                        return '<input type="button" class="btn btn-sm btn-success" value="Confirm">';
                    },
                    createdCell: function(cell, cellData, rowData, rowIndex, colIndex) {
                        $(cell).on("click", "input", rowData, si_conf_on_GridActionButton_Click);
                    }
                }
            ],
            columnDefs: [
                {
                    targets: 4,
                    className: 'text-center'
                }
            ]        
        });
    }
</script>