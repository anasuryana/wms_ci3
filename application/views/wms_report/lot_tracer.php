<style>
    .anastylesel_sim{
        background: red;
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
    thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 25px;
    }
</style>

<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row" id="rtracelot_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >From</span>
                    <input type="text" class="form-control" id="rtracelot_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >To</span>
                    <input type="text" class="form-control" id="rtracelot_txt_dt2" readonly>
                </div>
            </div>
        </div>

        <div class="row" id="rtracelot_stack2">
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rtracelot_btn_gen" onclick="rtracelot_btn_gen_eCK()">Search</button>
                    <div class="btn-group btn-group-sm" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Export to
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="#" id="rtracelot_btn_xls" onclick="rtracelot_btn_xls_eCK()"><span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> XLS</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <span id="rtracelot_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rtracelot_divku">
                    <table id="rtracelot_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light text-center">
                            <tr class="first">
                                <th rowspan="2" class="align-middle text-center">WO NO</th>
                                <th rowspan="2" class="align-middle">PROCESS</th>
                                <th rowspan="2" class="align-middle">LINE</th>
                                <th rowspan="2" class="align-middle">MCZ</th>
                                <th colspan="4" class="align-middle">OLD</th>
                                <th rowspan="2" class="align-middle">ITEM CODE</th>
                                <th rowspan="2" class="align-middle">LOT NO</th>
                                <th rowspan="2" class="align-middle">QTY</th>
                                <th rowspan="2" class="align-middle">UNIQUE</th>
                                <th rowspan="2" class="align-middle">DATE</th>
                                <th rowspan="2" class="align-middle">NIK</th>
                                <th rowspan="2" class="align-middle">REMARK</th>
                                <th rowspan="2" class="align-middle">JOB</th>
                            </tr>
                            <tr class="second">
                                <th  class="align-middle">ITEM CODE</th>
                                <th  class="align-middle">LOT NO</th>
                                <th  class="align-middle">QTY</th>
                                <th  class="align-middle">UNIQUE</th>
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
    $("#rtracelot_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rtracelot_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rtracelot_txt_dt").datepicker('update', new Date());
    $("#rtracelot_txt_dt2").datepicker('update', new Date());

    function rtracelot_btn_gen_eCK() {
        const date1 = document.getElementById('rtracelot_txt_dt').value
        const date2 = document.getElementById('rtracelot_txt_dt2').value

        const btnprc = document.getElementById('rtracelot_btn_gen')
        btnprc.disabled = true
        btnprc.innerHTML = 'Please wait'
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>item-tracer/lot",
            data: {dateFrom: date1, dateTo: date2},
            dataType: "json",
            success: function (response) {
                btnprc.disabled = false
                btnprc.innerHTML = 'Search'
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rtracelot_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rtracelot_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rtracelot_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                let myitmttl = 0;
                tableku2.innerHTML='';
                for (let i = 0; i<ttlrows; i++) {
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data[i].ENG_WO
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].PROCD
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].LINENOM
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].MCZ
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].OLD_ITEM_CODE
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].OLD_LOT_CODE
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].OLD_QTY).format(',')
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].OLD_UNIQUE
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].NEW_ITEM_CODE
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].NEW_LOT_CODE
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].NEW_QTY).format(',')
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].NEW_UNIQUE
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].DATE_AT
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].NIK
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].REMARK
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = response.data[i].JOB
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error : function(xhr, xopt, xthrow){
                btnprc.disabled = false
                btnprc.innerHTML = 'Search'
                alertify.error(xthrow);
            }
        });
    }

    function rtracelot_btn_xls_eCK() {
        let mdate1 = document.getElementById('rtracelot_txt_dt').value;
        let mdate2 = document.getElementById('rtracelot_txt_dt2').value;
    }

    $("#rtracelot_divku").css('height', $(window).height()   
    -document.getElementById('rtracelot_stack1').offsetHeight 
    -document.getElementById('rtracelot_stack2').offsetHeight    
    -100);
</script>
