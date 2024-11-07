<style type="text/css">
	.tagbox-remove{
		display: none;
	}
    thead tr.first th,
    thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th,
    thead tr.second td {
        position: sticky;
        top: 26px;
    }
</style>
<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row" id="trace_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >PSN No.</span>
                    <input type="text" class="form-control" id="trace_txt_txno" required>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Item Code</span>
                    <input type="text" class="form-control" id="trace_txt_itmcd" required>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Lot Number</span>
                    <input type="text" class="form-control font-monospace" id="trace_txt_itmlot" required >
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" title="Unique Code">UC</span>
                    <input type="text" class="form-control font-monospace" id="trace_txt_itmuc" maxlength="21">
                </div>
            </div>
        </div>
        <div class="row" id="trace_stack2">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">From</span>
                    <input type="text" class="form-control" id="trace_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">To</span>
                    <input type="text" class="form-control" id="trace_txt_dt2" readonly>
                </div>
            </div>
        </div>
        <div class="row" id="trace_stack3">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="trace_btnsearch" class="btn btn-outline-primary" ><i class="fas fa-search"></i> Search</button>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <span id="trace_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="trace_divku">
                    <table id="trace_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th>PSN No</th>
                                <th>Category</th>
                                <th>Line</th>
                                <th>Feeder</th>
                                <th>Machine</th>
                                <th>Item Code</th>
                                <th>Lot No</th>
                                <th>QTY</th>
                                <th>Time</th>
                                <th>Unique Code</th>
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
<div class="modal fade" id="TRACE_HEADERPSN">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Job List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1 text-end">
                    <span id="trace_lblinfo_h" class="badge bg-info"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="trace_h_divku">
                        <table id="trace_tbljob" class="table table-hover table-sm table-bordered" style="width:100%;font-size:80%">
                            <thead class="table-light">
                                <tr>
                                    <th>Job Number</th>
                                    <th>Lot Size</th>
                                    <th>Process</th>
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
<div class="modal fade" id="TRACE_C3">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Combined RM Label List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1 text-end">
                    <span id="trace_lblinfo_c3" class="badge bg-info"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="trace_c3_divku">
                        <table id="trace_tblc3" class="table table-hover table-sm table-bordered" style="width:100%;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th>Lot No</th>
                                    <th class="text-end">Qty</th>
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
    $("#trace_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        clearBtn: true
    });
    $("#trace_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        clearBtn: true
    });

    $("#trace_txt_dt").datepicker('update', new Date());
    $("#trace_txt_dt2").datepicker('update', new Date());

    $("#trace_divku").css('height', $(window).height()-trace_stack1.offsetHeight-trace_stack2.offsetHeight -trace_stack3.offsetHeight -100);
    function trace_e_search(){
        let mpsn = document.getElementById('trace_txt_txno').value;
        let mitmcd = document.getElementById('trace_txt_itmcd').value;
        let mitmlot = document.getElementById('trace_txt_itmlot').value;
        document.getElementById('trace_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $("#trace_tbl tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/tracelot')?>",
            data: {inpsn: mpsn, initmcd: mitmcd, initmlot: mitmlot, date1: trace_txt_dt.value, 
                date2: trace_txt_dt2.value, uc : trace_txt_itmuc.value.trim()},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    let ttldata = response.data.length;
                    document.getElementById('trace_lblinfo').innerHTML = ttldata + ' row(s) found';
                    let mydes = document.getElementById("trace_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("trace_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("trace_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for(let i =0; i<ttldata; i ++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.onclick = function(){
                            trace_e_tdcgetval(response.data[i].SPLSCN_DOC, response.data[i].SPLSCN_LINE, response.data[i].SPLSCN_FEDR);
                        };
                        newcell.style.cssText = 'cursor:pointer'
                        newcell.innerText = response.data[i].SPLSCN_DOC
                        newcell = newrow.insertCell(1);
                        newcell.innerText = response.data[i].SPLSCN_CAT
                        newcell = newrow.insertCell(2);
                        newcell.innerText = response.data[i].SPLSCN_LINE
                        newcell = newrow.insertCell(3);
                        newcell.innerText = response.data[i].SPLSCN_FEDR
                        newcell = newrow.insertCell(4);
                        newcell.innerText = response.data[i].SPLSCN_ORDERNO
                        newcell = newrow.insertCell(5);
                        newcell.innerText = response.data[i].SPLSCN_ITMCD
                        newcell = newrow.insertCell(6);
                        newcell.classList.add('font-monospace');
                        newcell.classList.add('fw-bold');
                        if(response.data[i].SPLSCN_LOTNO.includes("$C") || response.data[i].SPLSCN_LOTNO.includes("#C")){
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.onclick = function(){
                                let param = {itemcode : response.data[i].SPLSCN_ITMCD
                                ,lotno : response.data[i].SPLSCN_LOTNO
                                ,qty : numeral(response.data[i].SPLSCN_QTY).value()
                                  };
                                trace_e_get_joined(param);
                            };
                        }
                        newcell.innerText = response.data[i].SPLSCN_LOTNO
                        newcell = newrow.insertCell(7);
                        newcell.style.cssText= "white-space: nowrap;text-align:right";
                        newcell.innerText = numeral(response.data[i].SPLSCN_QTY).format(',')
                        newcell = newrow.insertCell(8);
                        newcell.innerText = response.data[i].SPLSCN_LUPDT
                        newcell = newrow.insertCell(-1);
                        newcell.innerText = response.data[i].SPLSCN_UNQCODE
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                } else {
                    document.getElementById('trace_lblinfo').innerHTML = response.status[0].msg;
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function trace_e_get_joined(param){
        $.ajax({
            type: "post",
            url: "<?=base_url('SPL/get_c3_definition')?>",
            data: {inlot: param.lotno, initemcd : param.itemcode, inqty: param.qty},
            dataType: "json",
            success: function (response) {
                $("#TRACE_C3").modal('show');
                let ttldata = response.data.length;
                    document.getElementById('trace_lblinfo_c3').innerHTML = ttldata + ' row(s) found';
                    let mydes = document.getElementById("trace_c3_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("trace_tblc3");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("trace_tblc3");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for(let i =0; i<ttldata; i ++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.innerText = response.data[i].C3LC_LOTNO
                        newcell = newrow.insertCell(1);
                        newcell.style.cssText= "text-align:right";
                        newcell.innerText = response.data[i].C3LC_QTY
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#trace_txt_txno").keypress(function (e) {
        if(e.which==13){
            trace_e_search();
        }
    });
    $("#trace_txt_itmcd").keypress(function (e) {
        if(e.which==13){
            trace_e_search();
        }
    });
    $("#trace_txt_itmlot").keypress(function (e) {
        if(e.which==13){
            trace_e_search();
        }
    });

    $("#trace_btnsearch").click(function (e) {
        trace_e_search();
    });
    function trace_e_tdcgetval(ppsn, pline, pfedr){
        $("#TRACE_HEADERPSN").modal('show');
        let vpsn = ppsn;
        let vlineno = pline ;
        let vfedr = pfedr;
        document.getElementById('trace_lblinfo_h').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/tracelot_head')?>",
            data: {inpsn: vpsn, inline: vlineno, infedr: vfedr},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0') {
                    let ttldata = response.data.length;
                    document.getElementById('trace_lblinfo_h').innerHTML = ttldata + ' row(s) found';
                    let mydes = document.getElementById("trace_h_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("trace_tbljob");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("trace_tbljob");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for(let i =0; i<ttldata; i ++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.innerText = response.data[i].PPSN1_WONO
                        newcell = newrow.insertCell(1);
                        newcell.style.cssText= "white-space: nowrap;text-align:right";
                        newcell.innerText = numeral(response.data[i].PPSN1_SIMQT).format(',')
                        newcell = newrow.insertCell(2);
                        newcell.innerText = response.data[i].PPSN1_PROCD
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                } else {
                    document.getElementById('trace_lblinfo_h').innerHTML = response.status[0].msg;
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });

    }
</script>