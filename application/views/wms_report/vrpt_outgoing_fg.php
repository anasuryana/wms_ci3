<style>
    thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 26px;
    }
</style>
<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >From</span>
                    <input type="text" class="form-control" id="routgoing_wh_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >To</span>
                    <input type="text" class="form-control" id="routgoing_wh_txt_dt2" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 text-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routgoing_wh_typereport" id="routgoing_wh_typeall" value="a" checked>
                    <label class="form-check-label" for="routgoing_wh_typeall">All</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routgoing_wh_typereport" id="routgoing_wh_typenight" value="m">
                    <label class="form-check-label" for="routgoing_wh_typemorning">Morning</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routgoing_wh_typereport" id="routgoing_wh_typenight" value="n" >
                    <label class="form-check-label" for="routgoing_wh_typenight">Night</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Business Group</span>
                    <select class="form-select" id="routgoing_wh_bisgrup">
                        <option value="-">-</option>
                        <?php
$todis = '';
foreach ($lgroup as $r) {
    $todis .= '<option value="' . trim($r->MBSG_BSGRP) . '">' . trim($r->MBSG_DESC) . '</option>';
}
echo $todis;
?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Search by</span>
                    <select class="form-select" id="routgoing_wh_seachby">
                        <option value="assy">Assy Code</option>
                        <option value="job">Job Number</option>
                        <option value="reff">Reff Number</option>
                        <option value="si">Shipping Info</option>
                        <option value="txid">TX ID</option>
                    </select>
                    <input type="text" class="form-control" id="routgoing_wh_txt_assy">
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="routgoing_wh_btn_gen" onclick="routgoing_wh_btn_gen_e_click(this)">Search</button>
                    <button class="btn btn-success" title="export to spreadsheet file" type="button" id="routgoing_wh_btn_spreadsheet" onclick="routgoing_wh_btn_spreadsheet_e_click(this)"><span class="fas fa-file-excel"></span> </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <span id="routgoing_wh_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="routgoing_wh_divku">
                    <table id="routgoing_wh_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle">Assy Code</th>
                                <th  class="align-middle">Model</th>
                                <th  class="align-middle">SI</th>
                                <th  class="align-middle">TX ID</th>
                                <th  class="align-middle">Job Number</th>
                                <th  class="text-right">Qty</th>
                                <th  class="text-center">Reff. Number</th>
                                <th  class="text-center">ETA</th>
                                <th  class="text-center">ETD</th>
                                <th  class="text-center">Scanning Time</th>
                                <th  class="align-middle">Plant</th>
                                <th  class="align-middle">Business</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1 text-end">
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >TOTAL</span>
                    <input type="text" class="form-control" id="routgoing_wh_txt_total" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="routgoing_wh_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Preparation Checking Adjustment</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" >TX ID</span>
                        <input type="text" class="form-control" id="routgoing_wh_txt_tx_id" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="routgoing_whTblDiv">
                        <table id="routgoing_whTbl" class="table table-sm table-striped table-bordered table-hover">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>ID</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Qty</th>
                                    <th>...</th>
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
</div>
<input type="hidden" id="routgoing_row_at" value="1">
<script>
    $("#routgoing_wh_divku").css('height', $(window).height()*61/100);
    $("#routgoing_wh_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });

    $("#routgoing_wh_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });

    $("#routgoing_wh_txt_dt").datepicker('update', new Date());
    $("#routgoing_wh_txt_dt2").datepicker('update', new Date());

    function routgoing_wh_btn_gen_e_click(sender){
        let searchby = document.getElementById('routgoing_wh_seachby').value;
        let dtfrom = document.getElementById('routgoing_wh_txt_dt').value;
        let dtto = document.getElementById('routgoing_wh_txt_dt2').value;
        let reporttype = $('input[name="routgoing_wh_typereport"]:checked').val();
        let assyno = document.getElementById('routgoing_wh_txt_assy').value.trim();
        let bsgroup = document.getElementById('routgoing_wh_bisgrup').value;

        if(bsgroup=='-'){
            alertify.message('Please select business group');
            document.getElementById('routgoing_wh_bisgrup').focus()
            return;
        }

        sender.disabled = true
        sender.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'
        $.ajax({
            type: "get",
            url: "<?=base_url('SI/get_outgoing')?>",
            data: {indate: dtfrom,indate2: dtto, inreport: reporttype, inassy: assyno, insearchby : searchby, inbsgrp: bsgroup},
            dataType: "json",
            success: function (response) {
                sender.disabled = false
                sender.innerHTML = 'Search'

                let ttlrows = response.data.length;
                let mydes = document.getElementById("routgoing_wh_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("routgoing_wh_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("routgoing_wh_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].SISCN_SERQTY).value();
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.data[i].SI_ITMCD
                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].MITM_ITMD1

                    newcell = newrow.insertCell(2);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].SI_DOC

                    newcell = newrow.insertCell(3);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].DLV_ID
                    if(!response.data[i].ITH_LUPDT && response.data[i].DLV_ID) {
                        newcell.style.cssText= "white-space: nowrap; cursor:pointer";

                        newcell.onclick = function() {
                            routgoing_wh_txt_tx_id.value = response.data[i].DLV_ID
                            routgoing_wh_get_delivery_checking_detail({doc : response.data[i].DLV_ID})
                            $("#routgoing_wh_modal").modal('show')
                        }
                    }

                    newcell = newrow.insertCell(4);
                    newcell.style.cssText= "white-space: nowrap;text-align:center";
                    newcell.innerHTML = response.data[i].SER_DOC

                    newcell = newrow.insertCell(5);
                    newcell.style.cssText= 'text-align:right';
                    newcell.innerHTML = numeral(response.data[i].SISCN_SERQTY).format(',')

                    newcell = newrow.insertCell(6);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].SISCN_SER

                    newcell = newrow.insertCell(-1);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].SI_DOCREFFETA

                    newcell = newrow.insertCell(-1);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].ITH_LUPDT

                    newcell = newrow.insertCell(-1);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].SISCN_LUPDT

                    newcell = newrow.insertCell(-1);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].SI_OTHRMRK

                    newcell = newrow.insertCell(-1);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].SI_BSGRP
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('routgoing_wh_lblinfo').innerHTML = ttlrows>0 ? '': 'data not found';
                document.getElementById('routgoing_wh_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                sender.disabled = false
                sender.innerHTML = 'Search'
            }
        });
    }

    function routgoing_wh_get_delivery_checking_detail(data) {
        routgoing_whTbl.getElementsByTagName("tbody")[0].innerHTML = `<tr><td colspan="5">Please wait</td</tr>`
        $.ajax({
            type: "GET",
            url: `<?=$_ENV['APP_INTERNAL_API']?>delivery/checking/${btoa(data.doc)}`,
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("routgoing_whTblDiv");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("routgoing_whTbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("routgoing_whTbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;
                for (let i = 0; i<ttlrows; i++) {
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(-1);
                    newcell.innerHTML = response.data[i].SER_ID
                    newcell = newrow.insertCell(-1);
                    newcell.innerHTML = response.data[i].SER_ITMID
                    newcell = newrow.insertCell(-1);
                    newcell.innerHTML = response.data[i].ITMD1
                    newcell.style.cssText = 'white-space: nowrap'
                    newcell = newrow.insertCell(-1);
                    newcell.innerHTML = response.data[i].dlv_qty
                    newcell.classList.add('text-center')
                    newcell = newrow.insertCell(-1);
                    newcell.innerHTML = `<span class="badge bg-warning"><strong>Cancel</strong></span>`
                    newcell.style.cssText = 'cursor:pointer'
                    newcell.classList.add('text-center')
                    newcell.onclick = function() {

                        if(tableku2.rows[i].cells[4].innerText.includes('wait')) {
                            alertify.warning('Please wait oy!')
                            return
                        }
                        if(confirm("Are you sure ?")) {
                            tableku2.rows[i].cells[4].innerHTML = `<span class="badge bg-info"><strong>Please wait</strong></span>`
                            $.ajax({
                                type: "DELETE",
                                url: "<?=$_ENV['APP_INTERNAL_API']?>delivery/checking",
                                data: {doc : data.doc, id: response.data[i].SER_ID, itemCode : response.data[i].SER_ITMID},
                                dataType: "json",
                                success: function (response) {
                                    alertify.message(response.message);
                                    routgoing_wh_get_delivery_checking_detail(data)
                                }, error: function(xhr, xopt, xthrow) {
                                    alertify.error(xthrow);
                                    tableku2.rows[i].cells[4].innerHTML = `<span class="badge bg-warning"><strong>Cancel</strong></span>`
                                }
                            });
                        }
                    }
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
                routgoing_whTbl.getElementsByTagName("tbody")[0].innerHTML = `<tr><td colspan="5">-</td</tr>`
            }
        });
    }

    function routgoing_wh_btn_spreadsheet_e_click(p)
    {
        let searchby = document.getElementById('routgoing_wh_seachby').value;
        let dtfrom = document.getElementById('routgoing_wh_txt_dt').value;
        let dtto = document.getElementById('routgoing_wh_txt_dt2').value;
        let reporttype = $('input[name="routgoing_wh_typereport"]:checked').val();
        let assyno = document.getElementById('routgoing_wh_txt_assy').value.trim();
        let bsgroup = document.getElementById('routgoing_wh_bisgrup').value;
        if(bsgroup=='-'){
            alertify.message('Please select business group');
            document.getElementById('routgoing_wh_bisgrup').focus()
            return;
        }
        p.innerHTML = 'Please wait'
        p.disabled = true
        $.ajax({
            type: "GET",
            url: "<?=base_url('SI/get_outgoing_as_spreadsheet')?>",
            data: {indate: dtfrom,indate2: dtto, inreport: reporttype, inassy: assyno, insearchby : searchby, inbsgrp: bsgroup },
            success: function (response) {
                const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                const fileName = `Outgoing FG from ${dtfrom} to ${dtto}, ${bsgroup}.xlsx`
                saveAs(blob, fileName)
                p.innerHTML = '<span class="fas fa-file-excel"></span>'
                p.disabled = false
                alertify.success('Done')
            },
            xhr: function () {
                const xhr = new XMLHttpRequest()
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 2) {
                        if (xhr.status == 200) {
                            xhr.responseType = "blob";
                        } else {
                            p.innerHTML = '<span class="fas fa-file-excel"></span>'
                            p.disabled = false
                            xhr.responseType = "text";
                        }
                    }
                }
                return xhr
            },
        })
    }
</script>