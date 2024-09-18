<style type="text/css">
table.dataTable.fixedHeader-floating {
    z-index: 10;
}
</style>
<div style="padding:5px" >
    <div class="container-xxl">
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
                                        <input type="text" class="form-control" id="ser_txt_jobno" required readonly>
                                        <button class="btn btn-primary" type="button" id="ser_btn_smod_job"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Code</span>
                                        <input type="text" class="form-control" id="ser_txt_itmcd" required readonly >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Name</span>
                                        <input type="text" class="form-control" id="ser_txt_itmnm" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Label Color</span>
                                        <input type="text" class="form-control" id="ser_txt_lblcolor" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rank</span>
                                        <input type="text" class="form-control" id="ser_txt_lblrank" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Job Qty</span>
                                        <input type="text" class="form-control" id="ser_txt_jobqty" required readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">SPQ</span>
                                        <input type="text" class="form-control" id="ser_txt_spq" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Qty / sheet</span>
                                        <input type="text" class="form-control" id="ser_txt_shtqty" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Qty</span>
                                        <input type="text" class="form-control" id="ser_txt_qty" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Sheets</span>
                                        <input type="text" class="form-control" id="ser_txt_sheets" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Production Date</span>
                                        <input type="text" class="form-control" id="ser_txt_proddt" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Line / Shift</span>
                                        <!-- <input type="text" class="form-control" id="ser_txt_prodline" required>   -->
                                        <select class="form-select" id="ser_txt_prodline" required>
                                        </select>
                                        <span class="input-group-text">/</span>
                                        <select class="form-select" id="ser_txt_prodshift" required>
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
                                <div class="col-md-12 mb-0">
                                    <div class="btn-group btn-group-sm">
                                        <button title="Print" class="btn btn-primary" type="button" id="ser_btn_prc"><i class="fas fa-save"></i></button>
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
                                        <input type="text" class="form-control" id="ser_txt_jobno_tobfind" required readonly >
                                        <input type="hidden" id="ser_txt_item_tobfind">
                                        <button class="btn btn-outline-primary" type="button" id="ser_btn_findser"><i class="fas fa-search"></i></button>
                                        <button class="btn btn-outline-primary" type="button" id="ser_btn_print"><i class="fas fa-print" title="Print selected ID"></i></button>
                                        <button class="btn btn-outline-danger" type="button" id="ser_btn_delete"><i class="fas fa-trash" title="Delete selected ID"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-0">
                                    <table id="ser_tbllastjobno" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center"><input class="form-check-input" type="checkbox" id="ser_ckall" title="Select all"></th>
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
<div class="modal fade" id="SER_MODJOB">
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
                        <input type="text" class="form-control" id="ser_txtsearchjob" maxlength="15" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-right">
                    <span class="badge bg-info" id="ser_lblinfo_tblser"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="ser_job_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Job No</th>
                                    <th>Color</th>
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
<div class="modal fade" id="SER_MODPRINT">
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
                            <input type="radio" class="custom-control-input" id="ser_rd_def" name="ser_rd_lbltype" value="0">
                            <label class="custom-control-label" for="ser_rd_def">Default</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="ser_rd_ppr" name="ser_rd_lbltype" value="1" checked>
                            <label class="custom-control-label" for="ser_rd_ppr">Paper</label>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1">
                    <div id="ser_dv">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text" >Size</span>
                            <select class="form-select" id="ser_sel_ppr">
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
                    <button class="btn btn-primary btn-sm" id="ser_btn_prcprint">Print</button>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="SER_MODSELJOB">
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
                        <input type="text" class="form-control" id="ser_txtseljob" maxlength="15" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-right mb-1">
                    <span class="badge bg-info" id="ser_lblinfo_tbljob"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="ser_divku">
                        <table id="ser_seljob_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:91%">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">Job Number</th>
                                    <th rowspan="2" class="align-middle">Item Code</th>
                                    <th rowspan="2" class="align-middle">Item Name</th>
                                    <th colspan="3" class="text-center">QTY</th>
                                    <th rowspan="2" class="text-center align-middle">BS GROUP</th>
                                    <th rowspan="2" class="text-center align-middle">Customer</th>
                                    <th rowspan="2" class="text-center align-middle d-none">Label Color</th>
                                    <th rowspan="2" class="text-center align-middle d-none">QTY / Sheet</th>
                                    <th rowspan="2" class="text-center align-middle d-none">SPQ</th>
                                    <th rowspan="2" class="text-center align-middle">Rank</th>
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
    var ser_serprint = [];
    $("#ser_txt_proddt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#ser_btn_delete").click(function (e) {
        ser_serprint = [];
        $.each(tableSERLAST.rows().nodes(), function(key, value){
            var $tds = $(value).find('td'),
                rana = $tds.eq(0).find('input').is(':checked'),
                myser = $tds.eq(1).text();
            if(rana){
                ser_serprint.push(myser);
            }
        });
        if (ser_serprint.length==0){
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
            url: "<?=base_url('SER/remove')?>",
            data: {inid:ser_serprint},
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
    $("#ser_btn_prc").click(function (e) {
        e.preventDefault();
        let mitem = $("#ser_txt_itmcd").val();
        let mjob = $("#ser_txt_jobno").val();
        let mqty = $("#ser_txt_qty").val();
        let msheet = $("#ser_txt_sheets").val();
        let mproddt = $("#ser_txt_proddt").val();
        let mprodline = $("#ser_txt_prodline").val();
        let mprodshift = $("#ser_txt_prodshift").val();
        let mspq = $("#ser_txt_spq").val();
        const mrank = document.getElementById('ser_txt_lblrank').value
        if(mjob.trim()==''){
            $("#ser_txt_jobno").focus();
            return;
        }
        if (mitem.trim()=='') {
            alertify.warning('Please select Item first !');$("#ser_txt_itmcd").focus();
            return;
        }
        if(mqty.trim()==''){
            alertify.warning('QTY must be not null');$("#ser_txt_qty").focus();
            return;
        }
        mqty = numeral(mqty).value();
        if(numeral(mqty).value()<=0){
            alertify.warning('QTY must be greater than zero');$("#ser_txt_qty").focus();
            return;
        }
        if(mproddt.trim()==''){
            alertify.warning('Please entry Production Date');$("#ser_txt_proddt").focus();
            return;
        }
        mspq = numeral(mspq).value();
        if(mqty>mspq){
            alertify.warning('QTY could not be greater than SPQ');
            return;
        }
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/setfg')?>",
            data: {initemcd: mitem, injob: mjob, inqty: mqty, insheet: msheet, inproddt: mproddt, inline: mprodline, inshift: mprodshift, inrank: mrank },
            dataType: "json",
            success: function (response) {
                if(response[0].cd=='0'){
                    alertify.message(response[0].msg);
                } else {
                    alertify.message(response[0].msg);
                    document.getElementById('ser_txt_jobno_tobfind').value=response[0].doc;
                    document.getElementById('ser_txt_item_tobfind').value=response[0].itemcd;
                    initLASTSERList();
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#ser_txt_proddt").datepicker().on('changeDate', function(e){
        $("#ser_txt_prodline").focus();
    });
    initLASTSERList();
    function initLASTSERList() {
        tableSERLAST =  $('#ser_tbllastjobno').DataTable({
            scrollX : true,
            // responsive: true,
            // destroy: true,
            ajax: {
                url : '<?=base_url("SER/getdoclike")?>',
                type: 'get',
            },
            columns:[
                { "data": 'SER_ID',
                    render : function(data, type, row){
                        return '<input class="form-check-input" type="checkbox">';
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
                    targets: 1,
                    className: 'text-start'
                },
                {
                    targets: 3,
                    className: 'text-end'
                },
                {
                    targets: 0,
                    className: 'text-center',
                    orderable: false
                },
            ],
            footerCallback: function ( row, data, start, end, display ) {
                let apik = this.api();
                let intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                total = apik
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                apik.column(3).footer().innerHTML = 'Total: ' + numeral(total).format(',')
            }
        }).on('preXhr.dt', function ( e, settings, data ) {
            data.indoc = $("#ser_txt_jobno_tobfind").val()
            data.initm = $("#ser_txt_item_tobfind").val()
        });
    }
    $("#ser_btn_findser").click(function (e) {
        e.preventDefault();
        $("#SER_MODJOB").modal('show');
    });
    $("#SER_MODJOB").on('shown.bs.modal', function(){
        $("#ser_txtsearchjob").focus();
    });
    $("#ser_txtsearchjob").keypress(function (e) {
        if(e.which==13){
            let mval = $(this).val();
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/getdocg')?>",
                data: {inkey: mval},
                dataType: "json",
                success: function (response) {
                    var ttlrows = response.length;
                    let tohtml='';
                    for(let i=0;i<ttlrows;i++){
                        tohtml += '<tr>'+
                        '<td>'+response[i].SER_ITMID+'</td>'+
                        '<td>'+response[i].SER_DOC+'</td>'+
                        '<td>'+response[i].MITM_LBLCLR+'</td>'+
                        '</tr>';
                    }
                    $("#ser_job_tbl tbody").html(tohtml);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $('#ser_job_tbl tbody').on( 'click', 'tr', function () {
        let mitem =  $(this).closest("tr").find('td:eq(0)').text();
        let mjob =  $(this).closest("tr").find('td:eq(1)').text();
        let mcolor =  $(this).closest("tr").find('td:eq(2)').text();
        document.getElementById('ser_txt_lblcolor').value=mcolor;
        $("#ser_txt_item_tobfind").val(mitem); $("#ser_txt_jobno_tobfind").val(mjob);
        $("#SER_MODJOB").modal('hide');
        tableSERLAST.ajax.reload();
    });


    $("#ser_btn_print").click(function(){
        ser_serprint = [];
        $.each(tableSERLAST.rows().nodes(), function(key, value){
            var $tds = $(value).find('td'),
                rana = $tds.eq(0).find('input').is(':checked'),
                myser = $tds.eq(1).text();
            if(rana){
                ser_serprint.push(myser);
            }
        });
        if (ser_serprint.length>0){
            $("#SER_MODPRINT").modal('show');
        } else {
            alertify.message('Please select the data to be printed first');
        }
    });
    $("#ser_ckall").click(function(){
        var ischk = $(this).is(":checked");
        $.each(tableSERLAST.rows().nodes(), function(key, value) {
            var $tds = $(value).find('td'),
                rana = $tds.eq(0).find('input').prop('checked', ischk);
        });
    });

    $("#ser_btn_prcprint").click(function (e) {
        e.preventDefault();
        let mrd_type = $("input[name='ser_rd_lbltype']:checked").val();
        let msize = $("#ser_sel_ppr").val();
        Cookies.set('PRINTLABEL_FG', ser_serprint, {expires:365});
        Cookies.set('PRINTLABEL_FG_LBLTYPE', mrd_type, {expires:365});
        Cookies.set('PRINTLABEL_FG_SIZE', msize, {expires:365});
        window.open("<?=base_url('printlabel_fg')?>",'_blank');
    });

    $("#ser_rd_def").click(function (e) {
        $("#ser_dv").hide();
    });
    $("#ser_rd_ppr").click(function (e) {
        $("#ser_dv").show();
    });
    $("#ser_btn_smod_job").click(function (e) {
        e.preventDefault();
        $("#SER_MODSELJOB").modal('show');
    });
    $("#SER_MODSELJOB").on('shown.bs.modal', function(){
        $("#ser_txtseljob").focus();
    });
    $("#ser_txtseljob").keypress(function (e) {
        if(e.which==13){
            let mval =$(this).val();
            $("#ser_lblinfo_tbljob").text('Please wait ...');
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/getWOOpen')?>",
                data: {inwo: mval},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let mydes = document.getElementById("ser_divku");
                    var myfrag = document.createDocumentFragment();
                    var mtabel = document.getElementById("ser_seljob_tbl");
                    var cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    var tabell = myfrag.getElementById("ser_seljob_tbl");
                    var tanblebod = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                    var newrow, newcell, newText, unlbl;
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
                        newcell = newrow.insertCell(8);
                        newText = document.createTextNode(response[i].MITM_LBLCLR);
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'display: none';
                        newcell = newrow.insertCell(9);
                        newText = document.createTextNode(response[i].MITM_SHTQTY);
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'display: none';
                        newcell = newrow.insertCell(10);
                        newText = document.createTextNode(response[i].MITM_SPQ);
                        newcell.style.cssText = 'display: none';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(11)
                        newcell.innerHTML = response[i].MBOM_GRADE
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                    $("#ser_lblinfo_tbljob").text(ttlrows + ' row(s) found');
                    $('#ser_seljob_tbl tbody').on( 'click', 'tr', function () {
                        let mitem   = $(this).closest("tr").find('td:eq(1)').text();
                        let mjob    = $(this).closest("tr").find('td:eq(0)').text();
                        let mitemnm = $(this).closest("tr").find('td:eq(2)').text();
                        let qty     = $(this).closest("tr").find('td:eq(3)').text();
                        let qtylbl  = $(this).closest("tr").find('td:eq(4)').text();
                        let color  = $(this).closest("tr").find('td:eq(8)').text();
                        let mshtqty  = $(this).closest("tr").find('td:eq(9)').text();
                        let mSPQ  = $(this).closest("tr").find('td:eq(10)').text();
                        const mRank  = $(this).closest("tr").find('td:eq(11)').text();
                        document.getElementById('ser_txt_sheets').value='';
                        if(numeral(qty).value()!=numeral(qtylbl).value()){
                            $("#ser_txt_jobno").val(mjob); $("#ser_txt_itmcd").val(mitem);
                            document.getElementById('ser_txtseljob').value="";
                            document.getElementById('ser_txt_qty').value="";
                            document.getElementById('ser_txt_itmnm').value=mitemnm;
                            document.getElementById('ser_txt_jobqty').value=qty;
                            document.getElementById('ser_txt_lblcolor').value=color;
                            document.getElementById('ser_txt_shtqty').value=mshtqty;
                            document.getElementById('ser_txt_spq').value=numeral(mSPQ).format(',');
                            document.getElementById('ser_txt_lblrank').value = mRank
                            if(numeral(mshtqty).value()>0){
                                document.getElementById('ser_txt_sheets').focus();
                                document.getElementById('ser_txt_sheets').readOnly=false;
                                document.getElementById('ser_txt_qty').readOnly=true;
                            } else {
                                document.getElementById('ser_txt_qty').focus();
                                document.getElementById('ser_txt_sheets').readOnly=true;
                                document.getElementById('ser_txt_qty').readOnly=false;
                            }

                            $('#ser_seljob_tbl tbody').html('');
                            $("#ser_txt_item_tobfind").val(mitem); $("#ser_txt_jobno_tobfind").val(mjob);
                            $("#SER_MODSELJOB").modal('hide');

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
    ser_getline_mfg();
    function ser_getline_mfg(pjob){
        document.getElementById('ser_txt_prodline').innerHTML='';
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
                strtodis += "<option value='SMT-ATH2'>SMT-ATH2</option>";
                document.getElementById('ser_txt_prodline').innerHTML=strtodis;
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#ser_txt_qty").keyup(function (e) {
        let mval = numeral($(this).val()).format(',');
        $(this).val(mval);
    });

    $("#ser_txt_sheets").keyup(function (e) {
        let cttlsht = $(this).val();
        let cshtqty = document.getElementById('ser_txt_shtqty').value;
        let theqty = numeral(cttlsht).value()*numeral(cshtqty).value();
        document.getElementById('ser_txt_qty').value= theqty;
    });
</script>