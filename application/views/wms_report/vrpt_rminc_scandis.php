<style type="text/css">
    .tagbox-remove {
        display: none;
    }

    .txfg_cell:hover {
        font-weight: 900;
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
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rminc_scandis_btn_gen">Search</button>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-right">
                <span id="rminc_scandis_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rminc_scandis_divku">
                    <table id="rminc_scandis_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th rowspan="2" class="align-middle">DO Number</th>
                                <th colspan="2" class="align-middle text-center">QTY</th>
                            </tr>
                            <tr class="second">
                                <th class="text-right">DO</th>
                                <th class="text-right">Scan</th>
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
<div class="modal fade" id="rminc_scandis_DETAILDO">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Detail DO </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >DO Number</span>
                        <input type="text" class="form-control" id="rminc_scandis_txno" required readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rminc_scandis_mod_divku">
                        <table id="rminc_scandis_mod_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">Item Code</th>
                                    <th rowspan="2" class="align-middle">Item Name</th>
                                    <th colspan="2" class="align-middle text-center">QTY</th>
                                </tr>
                                <tr>
                                    <th class="text-right">DO</th>
                                    <th class="text-right">Scan</th>
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
    $("#rminc_scandis_divku").css('height', $(window).height()*75/100);
    $("#rminc_scandis_btn_gen").click(function (e) {
        document.getElementById('rminc_scandis_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/getdiscrepancy_scan')?>",
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rminc_scandis_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rminc_scandis_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rminc_scandis_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.data[i].RCV_DONO);
                    newcell.style.cssText= "white-space: nowrap;cursor: pointer;";
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(numeral(response.data[i].GTDOQTY).format(','));
                    newcell.style.cssText= "white-space: nowrap;text-align:right";
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(numeral(response.data[i].GTSCNQTY).format('0,0'));
                    newcell.style.cssText= "white-space: nowrap;text-align:right";
                    newcell.appendChild(newText);
                }
                let mrows = tableku2.getElementsByTagName("tr");
                for(let x=0;x<mrows.length;x++){
                    tableku2.rows[x].cells[0].onclick = function(){rminc_scandis_e_showdetail(tableku2.rows[x])};
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('rminc_scandis_lblinfo').innerHTML = ttlrows>0 ? ttlrows +' row(s) data found': response.status[0].msg;
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
    function rminc_scandis_e_showdetail(prow){
        let tcell0 = prow.getElementsByTagName("td")[0];
        let dono = tcell0.innerText;
        $("#rminc_scandis_DETAILDO").modal('show');
        document.getElementById('rminc_scandis_txno').value=dono;
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/getdiscrepancy_scan_d')?>",
            data: {indo: dono},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rminc_scandis_mod_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rminc_scandis_mod_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rminc_scandis_mod_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.data[i].RCV_DONO);
                    newcell.style.cssText= "white-space: nowrap;cursor: pointer;";
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].RCV_ITMCD);
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(numeral(response.data[i].DOQTY).format(','));
                    newcell.style.cssText= "white-space: nowrap;text-align:right";
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(numeral(response.data[i].SCNQTY).format('0,0'));
                    newcell.style.cssText= "white-space: nowrap;text-align:right";
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);

            }
        });
    }
</script>