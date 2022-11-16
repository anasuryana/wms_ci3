<div style="padding: 10px">
	<div class="container-xxl">       
        <div class="row">				
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Currency</span>                    
                    <select id="vexrat_curr" class="form-select">
                        <option value="RPH">RPH</option>
                        <option value="USD">USD</option>
                        <option value="YEN">YEN</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Type</span>                    
                    <select id="vexrat_type" class="form-select">
                        <option value="BANK">BANK</option>
                        <option value="TAX">TAX</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Date</span>                    
                    <input type="text" class="form-control" id="vexrate_date" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Value</span>                    
                    <input type="text" class="form-control" id="vexrate_val" required>
                </div>
            </div>
        </div>
       <hr>
        <div class="row">            
            <div class="col-md-6 mb-1">
                <button title="Save" class="btn btn-primary btn-sm" id="vexrate_btn_save"><i class="fas fa-save"></i></button>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Filter Year</span>
                    <input type="number" class="form-control" id="vexrate_val_filterYear" required>
                    <button title="Apply filter" class="btn btn-primary btn-sm" id="vexrate_btn_filter" onclick="initMEXRATEList()"><i class="fas fa-filter"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-0">                
                <table id="vexrate_tbl" class="table table-sm table-striped table-bordered" style="width:100%;cursor:pointer">
                    <thead class="table-light">                           
                        <tr>
                            <th>Currency</th>
                            <th>Type</th>                                
                            <th>Date</th>
                            <th>Value</th>
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
    var tableMEXRATE ;
    $("#vexrate_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true        
    });
    $("#vexrate_date").datepicker('update', new Date());
    $("#vexrate_val").keypress(function (e)
    {
        if(e.which==13)
        {
            vexrate_e_save();
        }
    });

    document.getElementById('vexrate_val_filterYear').value = new Date().getFullYear() 

    function vexrate_e_save()
    {
        let mcurr = $("#vexrat_curr").val();
        let mtype = $("#vexrat_type").val();
        let mdate = $("#vexrate_date").val();
        let mval = $("#vexrate_val").val();
        mval = mval.replace(',', '')
        $.ajax({
            type: "post",
            url: "<?=base_url('MEXRATE/set')?>",
            data: {incurr: mcurr, intype: mtype, indate: mdate, inval: mval},
            dataType: "text",
            success: function (response) {
                alertify.message(response);
                initMEXRATEList();
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#vexrate_btn_save").click(function (e) { 
        e.preventDefault();
        if(confirm("Are you sure ?"))
        {
            vexrate_e_save();
        }
    });
    $('#vexrate_date').datepicker()
    .on('changeDate', function(e) {
        $("#vexrate_val").focus();
    });
    initMEXRATEList();
    function initMEXRATEList()
    {        
        tableMEXRATE =  $('#vexrate_tbl').DataTable({
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
            ajax: {
                url : '<?=base_url("MEXRATE/getAll")?>',
                type: 'get',
                data: {inYear: document.getElementById('vexrate_val_filterYear').value}
            },
            columns:[               
                { "data": 'MEXRATE_CURR'},
                { "data": 'MEXRATE_TYPE'},
                { "data": 'MEXRATE_DT'},
                { "data": 'MEXRATE_VAL',
                    render: $.fn.dataTable.render.number(',', '.', 4,'')
                } 
            ], 
            columnDefs: [
                {
                    targets: 3,
                    className: 'text-right'
                }                
            ]           
        });             
    }
    $('#vexrate_tbl tbody').on( 'click', 'tr', function () { 
		if ($(this).hasClass('table-active') ) {			
			$(this).removeClass('table-active');
        } else {                    			
			$('#vexrate_tbl tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }
        let mcurr = $(this).closest("tr").find('td:eq(0)').text();
        let mtype = $(this).closest("tr").find('td:eq(1)').text();
        let mdate = $(this).closest("tr").find('td:eq(2)').text();
        let mval = $(this).closest("tr").find('td:eq(3)').text();
        document.getElementById('vexrat_curr').value = mcurr;
        document.getElementById('vexrat_type').value = mtype;        
        $('#vexrate_date').datepicker('update', mdate);
        document.getElementById('vexrate_val').value = mval;
    });
</script>