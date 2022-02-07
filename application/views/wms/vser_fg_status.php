<div style="padding:5px" >
    <div class="container-fluid">     
        <div class="row">
            <div class="col-sm-4 mb-1 pr-1 pl-1">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Item</h4>
                    </div>
                    <div class="card-body p-1">
                        <div class="col-md-12 order-md-0 pl-1 pr-1">
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Job Number</span>                                        
                                        <input type="text" class="form-control" id="sersts_txt_jobno" required readonly>                                        
                                        <button class="btn btn-primary" type="button" id="sersts_btn_smod_job"><i class="fas fa-search"></i></button>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Code</span>                                        				
                                        <input type="text" class="form-control" id="sersts_txt_itmcd" required readonly >                                                                               
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Name</span>                                        
                                        <input type="text" class="form-control" id="sersts_txt_itmnm" required readonly>								
                                    </div>
                                </div>
                            </div>                            
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Job Qty</span>                                        
                                        <input type="text" class="form-control" id="sersts_txt_jobqty" required readonly>
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Qty</span>                                        
                                        <input type="text" class="form-control" id="sersts_txt_qty" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Production Date</span>                                        
                                        <input type="text" class="form-control" id="sersts_txt_proddt" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Line / Shift</span>                                                                                
                                        <select class="form-select" id="sersts_txt_prodline" required>
                                        </select>                                        
                                        <span class="input-group-text">/</span>                                        
                                        <select class="form-select" id="sersts_txt_prodshift" required>
                                            <option value="M0">M0</option>
                                            <option value="M1">M1</option>
                                            <option value="M2">M2</option>
                                            <option value="N1">N1</option>
                                            <option value="N2">N2</option>                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Remark</span>                                        
                                        <input type="text" class="form-control" id="sersts_txt_remark" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-0">
                                    <div class="btn-group btn-group-sm">
                                        <button title="New" class="btn btn-outline-primary" type="button" id="sersts_btn_new"><i class="fas fa-file"></i></button>
                                        <button title="Save" class="btn btn-primary" type="button" id="sersts_btn_prc"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>	
            <div class="col-sm-8 pr-1 pl-1">
                <div class="card mb-9 shadow-sm">			
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">ID per Job Number Info</h4>
                    </div>
                    <div class="card-body p-1">
                        <div class="col-md-12 order-md-0 pl-1 pr-1">
                            <div class="row">
                                <div class="col-md-12 mb-1">                               
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Job Number</span>                                        					
                                        <input type="text" class="form-control" id="sersts_txt_jobno_tobfind" required readonly >
                                        <input type="hidden" id="sersts_txt_item_tobfind">
                                        <button class="btn btn-outline-primary" type="button" id="sersts_btn_findser"><i class="fas fa-search"></i></button>
                                        <button class="btn btn-outline-primary" type="button" id="sersts_btn_print"><i class="fas fa-print" title="Print selected ID"></i></button>
                                        <button class="btn btn-outline-danger" type="button" id="sersts_btn_delete"><i class="fas fa-trash" title="Delete selected ID"></i></button>                                        
                                    </div>                                                                                
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-0">
                                    <table id="sersts_tbllastjobno" class="table table-sm table-bordered table-hover" style="width:100%;cursor:pointer">
                                        <thead class="table-light">
                                            <tr>	
                                                <th class="text-center"><input class="form-check-input" type="checkbox" id="sersts_ckall" title="Select all"></th>
                                                <th>ID</th>
                                                <th>Item Code</th>                                                
                                                <th>Qty</th>
                                                <th>Create Date</th>
                                                <th>User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" style="text-align:right">Total:</th>
                                                <th colspan="2" style="text-align:right"></th>
                                                
                                            </tr>
                                        </tfoot>
                                    </table>                                    
                                </div>
                            </div>                            
                        </div>					
                    </div>			
                </div>
            </div>
        </div>	
    </div>
</div>
<div class="modal fade" id="sersts_MODJOB">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Look up Data Table</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>                        
                        <input type="text" class="form-control" id="sersts_txtsearchjob" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col text-right">
                    <span class="badge bg-info" id="sersts_lblinfo_tblser"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="sersts_job_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Job No</th>
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
    </div>
</div>
<div class="modal fade" id="sersts_MODPRINT">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Label Type</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1 text-center">
                    <form>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="sersts_rd_def" name="sersts_rd_lbltype" value="0">
                            <label class="custom-control-label" for="sersts_rd_def">Default</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="sersts_rd_ppr" name="sersts_rd_lbltype" value="1" checked>
                            <label class="custom-control-label" for="sersts_rd_ppr">Paper</label>
                        </div>                        
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1">
                    <div id="sersts_dv">
                        <div class="input-group input-group-sm mb-1">                            
                            <span class="input-group-text" >Size</span>                            
                            <select class="form-select" id="sersts_sel_ppr">
								<option value="A4">A4</option>
                                <option value="A3">A3</option>                                
                                <option value="A5">A5</option>
                                <option value="Letter">Letter</option>
                                <option value="Legal">Legal</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>     
            <div class="row">
                <div class="col mb-1 text-center">
                    <button class="btn btn-primary btn-sm" id="sersts_btn_prcprint">Print</button>
                </div>
            </div>       
        </div>             
      </div>
    </div>
</div>
<div class="modal fade" id="sersts_MODSELJOB">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Job List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>                        
                        <input type="text" class="form-control" id="sersts_txtseljob" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col text-right mb-1">
                    <span class="badge bg-info" id="sersts_lblinfo_tbljob"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="sersts_divku">
                        <table id="sersts_seljob_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">Job Number</th>
                                    <th rowspan="2" class="align-middle">Item Code</th>
                                    <th rowspan="2" class="align-middle">Item Name</th>
                                    <th colspan="3" class="text-center">QTY</th>
                                    <th rowspan="2" class="text-center align-middle">BS GROUP</th>
                                    <th rowspan="2" class="text-center align-middle">Customer</th>                                    
                                </tr>
                                <tr>
                                    <th class="text-right">Job</th>
                                    <th class="text-right">Labeled</th>
                                    <th class="text-right">Unlabeled</th>
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
    </div>
</div>
<script>
    var tableSERLAST ;
    var sersts_serprint = [];
    $("#sersts_txt_proddt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });     
    $("#sersts_btn_new").click(function (e) { 
        e.preventDefault();
        document.getElementById('sersts_txt_jobno').value='';
        document.getElementById('sersts_txt_itmcd').value='';
        document.getElementById('sersts_txt_itmnm').value='';
        document.getElementById('sersts_txt_jobqty').value='';
        document.getElementById('sersts_txt_proddt').value='';
        document.getElementById('sersts_txt_remark').value='';
        document.getElementById('sersts_txt_jobno_tobfind').value='';
        document.getElementById('sersts_txt_item_tobfind').value='';
        initLASTSERList();
    });     
    $("#sersts_btn_delete").click(function (e) {
        sersts_serprint = [];
        $.each(tableSERLAST.rows().nodes(), function(key, value){
            var $tds = $(value).find('td'),
                rana = $tds.eq(0).find('input').is(':checked'),
                myser = $tds.eq(1).text();
            if(rana){
                sersts_serprint.push(myser);
            }
        });
        if (sersts_serprint.length==0){
            alertify.warning('Please select the data to be deleted first');
            return;
        }
        let konfirm = confirm('Are you sure ?');
        if(konfirm==false){
            alertify.message('canceled');
            return;
        }
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/remove_status_label')?>",
            data: {inid:sersts_serprint},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    alertify.success(response.status[0].msg);
                    initLASTSERList();
                } else {
                    alertify.message(response.status[0].msg);
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });        
    });
    $("#sersts_btn_prc").click(function (e) { 
        e.preventDefault();
        let mitem = $("#sersts_txt_itmcd").val();
        let mjob = $("#sersts_txt_jobno").val();
        let mqty = $("#sersts_txt_qty").val();        
        let mproddt = $("#sersts_txt_proddt").val();
        let mprodline = $("#sersts_txt_prodline").val();
        let mprodshift = $("#sersts_txt_prodshift").val();
        let mremark = document.getElementById('sersts_txt_remark').value;
        if(mjob.trim()==''){
            $("#sersts_txt_jobno").focus();
            return;
        }
        if (mitem.trim()=='') {
            alertify.warning('Please select Item first !');$("#sersts_txt_itmcd").focus();            
            return;
        }
        if(mqty.trim()==''){
            alertify.warning('QTY must be not null');$("#sersts_txt_qty").focus();
            return;
        }
        mqty = numeral(mqty).value();
        if(numeral(mqty).value()<=0){
            alertify.warning('QTY must be greater than zero');$("#sersts_txt_qty").focus();
            return;
        }
        if(mproddt.trim()==''){
            alertify.warning('Please entry Production Date');$("#sersts_txt_proddt").focus();
            return;
        }       
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/setfg_status')?>",
            data: {initemcd: mitem, injob: mjob, inqty: mqty, inproddt: mproddt, inline: mprodline, inshift: mprodshift,
            inremark: mremark },
            dataType: "json",
            success: function (response) {
                if(response[0].cd=='0'){
                    alertify.message(response[0].msg);
                } else {
                    alertify.message(response[0].msg);
                    document.getElementById('sersts_txt_jobno_tobfind').value=response[0].doc;                    
                    document.getElementById('sersts_txt_item_tobfind').value=response[0].itemcd;
                    initLASTSERList();
                }                
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#sersts_txt_proddt").datepicker().on('changeDate', function(e){
        $("#sersts_txt_prodline").focus();
    });
    initLASTSERList();
    function initLASTSERList(){
        let mdoc = $("#sersts_txt_jobno_tobfind").val();
        let mitem = $("#sersts_txt_item_tobfind").val();
        tableSERLAST =  $('#sersts_tbllastjobno').DataTable({
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
            ajax: {
                url : '<?=base_url("SER/getdoclike_lblstatus")?>',
                type: 'get',
                data: {indoc: mdoc, initm: mitem}
            },
            columns:[
                { "data": 'BALQTY',
                    render : (data, type, row) => {    
                        if(wms_usergroupid=='OQC2'){
                            return '<input type="checkbox" class="form-check-input">';
                        } else {
                            if(data>0){
                                return '<input type="checkbox" class="form-check-input">';
                            } else {
                                return '<input type="checkbox" class="form-check-input" disabled>';
                            }
                        }
                    }
                },
                { "data": 'SER_ID'},
                { "data": 'SER_ITMID'},
                { "data": 'SER_QTY', render: $.fn.dataTable.render.number(',', '.', 0,'')},
                { "data": 'SER_LUPDT'},
                { "data": 'SER_USRID'}    
            ], 
            columnDefs: [
                {
                    targets: 3,
                    className: 'text-end'
                }                
            ],
            footerCallback : function (row, data, start, end, display) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // Total over all pages
                total = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                $( api.column( 3 ).footer() ).html(
                    'Total: ' + total
                );
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {                
                if(aData.ITH_WH=='AFWH9SC'){
                    $('td', nRow).css('background-color', '#ff6347');
                } else {
                    if(aData.BALQTY<=0){
                        $('td', nRow).css('background-color', '#F0ED2E');
                    }                
                    if(aData.BALJNTQTY<=0){
                        $('td', nRow).css('background-color', '#4CAF50');
                    }                
                }
            }
        });             
    }
    $("#sersts_btn_findser").click(function (e) { 
        e.preventDefault();
        $("#sersts_MODJOB").modal('show');
    });
    $("#sersts_MODJOB").on('shown.bs.modal', function(){
        $("#sersts_txtsearchjob").focus();
    });
    $("#sersts_txtsearchjob").keypress(function (e) { 
        if(e.which==13){
            let mval = $(this).val();
            $("#sersts_job_tbl tbody").empty();
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/getdocg_status')?>",
                data: {inkey: mval},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml='';
                    if(ttlrows>0){
                        for(let i=0;i<ttlrows;i++){
                            tohtml += '<tr>'+
                            '<td>'+response[i].SER_ITMID+'</td>'+
                            '<td>'+response[i].SER_DOC+'</td>'+
                            '</tr>';
                        }
                        $("#sersts_job_tbl tbody").html(tohtml);
                    } else {
                        alertify.message('not found');
                    }           
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $('#sersts_job_tbl tbody').on( 'click', 'tr', function () {
        let mitem =  $(this).closest("tr").find('td:eq(0)').text();
        let mjob =  $(this).closest("tr").find('td:eq(1)').text();                
        $("#sersts_txt_item_tobfind").val(mitem); 
        $("#sersts_txt_jobno_tobfind").val(mjob);
        $("#sersts_MODJOB").modal('hide');
        initLASTSERList();
    });
    

    $("#sersts_btn_print").click(function(){
        sersts_serprint = [];
        $.each(tableSERLAST.rows().nodes(), function(key, value){
            var $tds = $(value).find('td'),
                rana = $tds.eq(0).find('input').is(':checked'),
                myser = $tds.eq(1).text();
            if(rana){
                sersts_serprint.push(myser);
            }
        });
        if (sersts_serprint.length>0){
            $("#sersts_MODPRINT").modal('show');
        } else {
            alertify.message('Please select the data to be printed first');
        }
    });
    $("#sersts_ckall").click(function(){
        var ischk = $(this).is(":checked");
        $.each(tableSERLAST.rows().nodes(), function(key, value) {
            var $tds = $(value).find('td');
            let ctl_sts = $tds.eq(0).find('input').prop('disabled');
            if(!ctl_sts){
                 $tds.eq(0).find('input').prop('checked', ischk);
            }
                // rana = $tds.eq(0).find('input').prop('checked', ischk);
        });
    });

    $("#sersts_btn_prcprint").click(function (e) { 
        e.preventDefault();
        let mrd_type = $("input[name='sersts_rd_lbltype']:checked").val();  
        let msize = $("#sersts_sel_ppr").val();      
        Cookies.set('PRINTLABEL_FG', sersts_serprint, {expires:365});
        Cookies.set('PRINTLABEL_FG_LBLTYPE', mrd_type, {expires:365});
        Cookies.set('PRINTLABEL_FG_SIZE', msize, {expires:365});
        window.open("<?=base_url('printlabel_fgstatus')?>",'_blank');
    });
    
    $("#sersts_rd_def").click(function (e) {         
        $("#sersts_dv").hide();
    });
    $("#sersts_rd_ppr").click(function (e) {        
        $("#sersts_dv").show();
    });
    $("#sersts_btn_smod_job").click(function (e) { 
        e.preventDefault();
        $("#sersts_MODSELJOB").modal('show');
    });
    $("#sersts_MODSELJOB").on('shown.bs.modal', function(){
        $("#sersts_txtseljob").focus();
    });
    $("#sersts_txtseljob").keypress(function (e) { 
        if(e.which==13){
            let mval =$(this).val();
            $("#sersts_lblinfo_tbljob").text('Please wait ...');
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/getWOOpen_assy_as_sub')?>",
                data: {inwo: mval},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;                                      
                    let mydes = document.getElementById("sersts_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("sersts_seljob_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("sersts_seljob_tbl");
                    let tanblebod = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText, unlbl;
                    tanblebod.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){
                        unlbl = Number(response[i].PDPP_WORQT)-Number(response[i].LBLTTL);
                        newrow = tanblebod.insertRow(-1);                       
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response[i].PDPP_WONO.trim());
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response[i].PDPP_MDLCD.trim());
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response[i].MITM_ITMD1);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(numeral(response[i].PDPP_WORQT).format(','));
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';
                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(numeral(response[i].LBLTTL).format(','));
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';                     
                        newcell = newrow.insertCell(5);
                        newText = document.createTextNode(numeral(unlbl).format(','));
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';                     
                        newcell = newrow.insertCell(6);
                        newText = document.createTextNode(response[i].PDPP_BSGRP);
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: center';                     
                        newcell = newrow.insertCell(7);
                        newText = document.createTextNode(response[i].PDPP_CUSCD);
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: center';                                     
                    }                                        
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                    $("#sersts_lblinfo_tbljob").text(ttlrows + ' row(s) found');
                    $('#sersts_seljob_tbl tbody').on( 'click', 'tr', function () {
                        let mitem   = $(this).closest("tr").find('td:eq(1)').text();
                        let mjob    = $(this).closest("tr").find('td:eq(0)').text();
                        let mitemnm = $(this).closest("tr").find('td:eq(2)').text();
                        let qty     = $(this).closest("tr").find('td:eq(3)').text();
                        let qtylbl  = $(this).closest("tr").find('td:eq(4)').text();
                        if(numeral(qty).value()!=numeral(qtylbl).value()){
                            $("#sersts_txt_jobno").val(mjob); 
                            $("#sersts_txt_itmcd").val(mitem);
                            document.getElementById('sersts_txt_qty').focus();
                            document.getElementById('sersts_txtseljob').value="";
                            document.getElementById('sersts_txt_qty').value="";
                            document.getElementById('sersts_txt_itmnm').value=mitemnm;
                            document.getElementById('sersts_txt_jobqty').value=qty;                            
                            
                            $('#sersts_seljob_tbl tbody').html('');
                            $("#sersts_txt_item_tobfind").val(mitem); 
                            $("#sersts_txt_jobno_tobfind").val(mjob);                            
                            $("#sersts_MODSELJOB").modal('hide');                            
                            initLASTSERList();
                        } else {
                            alertify.message('all labels are already created');
                        }
                    });
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });    
    
    $("#sersts_txt_qty").keyup(function (e) { 
        let mval = numeral($(this).val()).format(',');
        $(this).val(mval);
    });  

    sersts_getline_mfg();
    function sersts_getline_mfg(pjob){
        document.getElementById('sersts_txt_prodline').innerHTML='';
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/getline_mfg')?>",           
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let strtodis = "<option value='SMT-ATH1.1'>SMT-ATH1.1</option>";
                strtodis += "<option value='SMT-ATH1.2'>SMT-ATH1.2</option>";
                for(let i =0;i<ttlrows;i++){
                    let theline = response.data[i].PPSN1_LINENO;
                    strtodis += "<option value='"+theline+"'>"+theline+"</option>";
                }
                document.getElementById('sersts_txt_prodline').innerHTML=strtodis;
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    } 
</script>