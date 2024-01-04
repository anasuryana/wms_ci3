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
        <div class="row" id="trace_return_stack1">
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >PSN No.</span>
                    <input type="text" class="form-control" id="trace_return_txt_txno" required>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Item Code</span>
                    <input type="text" class="form-control" id="trace_return_txt_itmcd" required>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Lot Number</span>
                    <input type="text" class="form-control font-monospace" id="trace_return_txt_itmlot" required >
                </div>
            </div>
        </div>
        <div class="row" id="trace_return_stack2">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">From</span>
                    <input type="text" class="form-control" id="trace_return_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">To</span>
                    <input type="text" class="form-control" id="trace_return_txt_dt2" readonly>
                </div>
            </div>
        </div>
        <div class="row" id="trace_return_stack3">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="trace_return_btnsearch" class="btn btn-outline-primary" ><i class="fas fa-search"></i> Search</button>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <span id="trace_return_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="trace_return_divku">
                    <table id="trace_return_tbl" class="table table-sm table-bordered table-hover" style="width:100%;cursor:pointer;font-size:80%">
                        <thead class="table-light text-center">
                            <tr class="first">
                                <th>PSN No</th>
                                <th>Category</th>
                                <th>Line</th>
                                <th>Feeder</th>
                                <th>Confirm Date</th>
                                <th>Item Code</th>
                                <th>Lot No</th>
                                <th>QTY</th>
                                <th>Time</th>
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
    $("#trace_return_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        clearBtn: true
    });
    $("#trace_return_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        clearBtn: true
    });

    $("#trace_return_txt_dt").datepicker('update', new Date());
    $("#trace_return_txt_dt2").datepicker('update', new Date());

    $("#trace_return_divku").css('height', $(window).height() - trace_return_stack1.offsetHeight - trace_return_stack2.offsetHeight - trace_return_stack3.offsetHeight -100);
    function trace_return_e_search() {
        trace_return_btnsearch.disabled = true
        let mpsn = document.getElementById('trace_return_txt_txno').value;
        let mitmcd = document.getElementById('trace_return_txt_itmcd').value;
        let mitmlot = document.getElementById('trace_return_txt_itmlot').value;
        document.getElementById('trace_return_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $("#trace_return_tbl tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('RETPRD/tracelot')?>",
            data: {inpsn: mpsn, initmcd: mitmcd, initmlot: mitmlot, date1: trace_return_txt_dt.value, date2: trace_return_txt_dt2.value},
            dataType: "json",
            success: function (response) {
                trace_return_btnsearch.disabled = false
                if(response.status[0].cd!='0'){
                    let ttldata = response.data.length;
                    document.getElementById('trace_return_lblinfo').innerHTML = ttldata + ' row(s) found';
                    let mydes = document.getElementById("trace_return_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("trace_return_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("trace_return_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for(let i =0; i<ttldata; i ++) {
                        newrow = tableku2.insertRow(-1);

                        if(response.data[i].RETSCN_CNFRMDT) {
                            newrow.classList.add('table-success')
                        }

                        newcell = newrow.insertCell(0);
                        newcell.innerHTML = response.data[i].RETSCN_SPLDOC

                        newcell = newrow.insertCell(1);
                        newcell.innerHTML = response.data[i].RETSCN_CAT

                        newcell = newrow.insertCell(2);
                        newcell.innerHTML = response.data[i].RETSCN_LINE

                        newcell = newrow.insertCell(3);
                        newcell.innerHTML = response.data[i].RETSCN_FEDR
                        newcell.classList.add('text-center')

                        newcell = newrow.insertCell(4);
                        newcell.innerHTML = response.data[i].RETSCN_CNFRMDT
                        newcell.classList.add('text-center')

                        newcell = newrow.insertCell(5);
                        newcell.innerHTML = response.data[i].RETSCN_ITMCD

                        newcell = newrow.insertCell(6);
                        newcell.classList.add('font-monospace');
                        newcell.classList.add('fw-bold');
                        newcell.innerHTML = response.data[i].RETSCN_LOT

                        newcell = newrow.insertCell(7);
                        newcell.style.cssText= "white-space: nowrap";
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].RETSCN_QTYAFT).format(',')

                        newcell = newrow.insertCell(8);
                        newcell.classList.add('text-center')
                        newcell.innerHTML = response.data[i].created_at ?? response.data[i].RETSCN_LUPDT
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                } else {
                    document.getElementById('trace_return_lblinfo').innerHTML = response.status[0].msg;
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('trace_return_lblinfo').innerHTML = ''
                trace_return_btnsearch.disabled = false
            }
        });
    }

    $("#trace_return_txt_txno").keypress(function (e) {
        if(e.which==13){
            trace_return_e_search();
        }
    });
    $("#trace_return_txt_itmcd").keypress(function (e) {
        if(e.which==13){
            trace_return_e_search();
        }
    });
    $("#trace_return_txt_itmlot").keypress(function (e) {
        if(e.which==13){
            trace_return_e_search();
        }
    });

    $("#trace_return_btnsearch").click(function (e) {
        trace_return_e_search();
    });

</script>