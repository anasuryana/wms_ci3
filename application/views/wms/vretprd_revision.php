<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-2">
                <label for="retrev_sel_psn" class="col-mb-2 col-form-label">Document</label>
                <div class="col-mb-10">
                    <select class="form-select" id="retrev_sel_psn">
                        <option value="-">Please wait ⌛</option>                   
                    </select>
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">                
                <table id="retrev_tbl" class="table table-sm table-striped table-bordered table-hover compact" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>PSN Number</th> 
                            <th>Category</th>                            
                            <th>Line</th>
                            <th>Feeder</th>                         
                            <th>OrderNo</th>
                            <th>Item Code</th>
                            <th>Lot No</th>
                            <th>Bef. QTY</th>
                            <th>Act. QTY</th>                                                                                                
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>                        
                    </tbody>
                </table>
            </div>
        </div>
	</div>
</div>
<div class="modal fade" id="RETREV_DETISSU">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Revision</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">            
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Item Code</span>
                        <input type="text" class="form-control" id="retrev_txtsel_itemcode" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Lot Number</span>
                        <input type="text" class="form-control" id="retrev_txtsel_lot" readonly>
                    </div>
                </div>                
            </div>   
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Act. Qty</span>
                        <input type="text" class="form-control" id="retrev_txtsel_befqty" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Act. Qty</span>
                        <input type="text" class="form-control" id="retrev_txtsel_qty">
                    </div>
                </div>
            </div>                                 
        </div>
        <input type="hidden" id="retrev_line">
        <input type="hidden" id="retrev_fr">
        <input type="hidden" id="retrev_category">        
        <input type="hidden" id="retrev_qtyissue">
        <input type="hidden" id="retrev_idscan">

        <div class="modal-footer">            
            <button type="button" class="btn btn-primary btn-sm" onclick="retrev_savechanges()">Save changes</button>
        </div>         
      </div>
    </div>
</div>
<script>    
    var retrev_DTABLE_psn;

    $.ajax({
        type: "GET",
        url: "<?=base_url("RETPRD/get_psn_list")?>",        
        dataType: "json",
        success: function (response) {
            let strSel = ''
            response.forEach((ai) => {
                strSel += `<option value="${ai['id']}">${ai['text']}</option>`
            })

            retrev_sel_psn.innerHTML = strSel
        }
    });

    $('#retrev_sel_psn').select2();

    $('#retrev_sel_psn').on('select2:select', function (e) {        
        retrev_sync(e.params.data.id)
    });

    function retrev_on_GridActionButton_Click(e){
        $("#RETREV_DETISSU").modal('show');
        document.getElementById('retrev_idscan').value = e.data.RETSCN_ID;
        document.getElementById('retrev_txtsel_itemcode').value = e.data.RETSCN_ITMCD;        
        document.getElementById('retrev_txtsel_lot').value = e.data.RETSCN_LOT;        
        document.getElementById('retrev_qtyissue').value = numeral(e.data.RETSCN_QTYBEF).format(',');
        document.getElementById('retrev_txtsel_befqty').value = numeral(e.data.RETSCN_QTYAFT).format(',');
        document.getElementById('retrev_txtsel_qty').value = '';
        document.getElementById('retrev_txtsel_qty').focus();
        document.getElementById('retrev_line').value = e.data.RETSCN_LINE;
        document.getElementById('retrev_fr').value = e.data.RETSCN_FEDR;
        document.getElementById('retrev_category').value = e.data.RETSCN_CAT;
    }

    function retrev_savechanges(){
        let mpsn = $('#retrev_txt_txno').combobox('getValue');
        let mline = document.getElementById('retrev_line').value;
        let mfr = document.getElementById('retrev_fr').value;
        let mcategory = document.getElementById('retrev_category').value;
        let mitemcode = document.getElementById('retrev_txtsel_itemcode').value;
        let mlotno = document.getElementById('retrev_txtsel_lot').value;

        let missuqty = numeral(document.getElementById('retrev_qtyissue').value).value();
        let moldactqty = numeral(document.getElementById('retrev_txtsel_befqty').value).value();
        let mnewactqty = numeral(document.getElementById('retrev_txtsel_qty').value).value();
        let midscan = document.getElementById('retrev_idscan').value;
        if(mnewactqty<0){
            alertify.warning("return "+mnewactqty+" ?");
            return;
        }
        if(missuqty<mnewactqty){
            alertify.warning("Issued qty > new act return qty. Please check again !");
            return;
        }
        if(confirm("Are you sure ?")){
            $.ajax({
                type: "post",
                url: "<?=base_url('RETPRD/revise')?>",
                data: {inidscan: midscan, inpsn: mpsn, inqty: mnewactqty, inoldactqty:moldactqty
                ,inline: mline, infr: mfr, incat: mcategory, initemcode: mitemcode ,inlot: mlotno },
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==1){
                        $("#RETREV_DETISSU").modal('hide');
                        alertify.success(response.status[0].msg);
                        let ppsn = $('#retrev_txt_txno').combobox('getValue');
                        retrev_sync(ppsn);
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
             
    }

    function retrev_sync(ppsn){        
        retrev_DTABLE_psn =  $('#retrev_tbl').DataTable({
            select: true,
            fixedHeader: true,
            destroy: true,
            ajax: {
                url : '<?=base_url("RETPRD/get_data_bypsn")?>',         
                data: {indoc: ppsn},
                dataSrc: function(json){
                    //WHEN THE DATA RECEIVED FROM SERVER                    
                    return json.data;
                }
            },
            columns:[
                { "data": 'RETSCN_ID'},
                { "data": 'RETSCN_SPLDOC'},
                { "data": 'RETSCN_CAT'},
                { "data": 'RETSCN_LINE'},
                { "data": 'RETSCN_FEDR'},
                { "data": 'RETSCN_ORDERNO'},
                { "data": 'RETSCN_ITMCD'},
                { "data": 'RETSCN_LOT'},
                { "data": 'RETSCN_QTYBEF', render: $.fn.dataTable.render.number(',', '.', 0,'')},
                { "data": 'RETSCN_QTYAFT', render: $.fn.dataTable.render.number(',', '.', 0,'')},
                { "data": 'RETSCN_QTYAFT', 
                    render : function(data, type, row){
                        return '<input type="button" class="btn btn-sm btn-success" value="Revise">';
                    },
                    createdCell: function(cell, cellData, rowData, rowIndex, colIndex) {
                        $(cell).on("click", "input", rowData, retrev_on_GridActionButton_Click);
                    }
                }
            ],
            columnDefs: [
                {
                    targets: 8,
                    className: 'text-end'
                },
                {
                    targets: 9,
                    className: 'text-end'
                },
                {
                    targets: 10,
                    className: 'text-center'
                }
            ]        
        })
       
    }    
</script>