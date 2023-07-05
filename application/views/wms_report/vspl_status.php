<style>
    table td.kuning {
        background-color: yellow;
    }

    table td.merah {
        background-color: red;
    }

    table td.hijau {
        background-color: greenyellow;
    }

    table td.abuabu {
        background-color: grey;
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
        <div class="row" id="stakit_stack0">
            <div class="col-md-7 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Business Group</span>
                    <select class="form-select" id="stakit_bisgrup">
                        <option value="">All</option>
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
            <div class="col-md-5 mb-1 text-center">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Job</span>
                    <input type="text" class="form-control" id="stakit_txt_job" onkeypress="stakit_txt_job_ekeypress(event)" required>
                </div>
            </div>
        </div>
        <div class="row" id="stakit_stack1">
            <div class="col mb-1 text-center">
            <button class="btn btn-outline-primary btn-sm" type="button" id="stakit_btn_findjob" onclick="stakit_btn_findjob_e_click()">Search</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="stakit_divku">
                    <table id="stakit_tbl" class="table table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr class="first">
                                <th rowspan="2" class="align-middle">PSN</th>
                                <th colspan="7" class="align-middle text-center">Category</th>
                            </tr>
                            <tr class="second">
                                <th class="text-center" style="width:10%">CHIP</th>
                                <th class="text-center" style="width:10%">HW</th>
                                <th class="text-center" style="width:10%">IC</th>
                                <th class="text-center" style="width:10%">KANBAN</th>
                                <th class="text-center" style="width:10%">PCB</th>
                                <th class="text-center" style="width:10%">PREPARE</th>
                                <th class="text-center" style="width:10%">SP</th>
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
<div class="modal fade" id="STAKIT_JOB_MOD">
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
                        <span id="stakit_job_lblinfo_h" class="badge bg-info"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="stakit_job_divku">
                            <table id="stakit_tbljob" class="table table-hover table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Job Number</th>
                                        <th class="text-end">Lot Size</th>
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
<div class="modal fade" id="StatusKittingItemModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Outstanding Item</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1">
                        <span id="StatusKittingSelectedPSN" class="badge bg-info"></span>
                        <span id="StatusKittingSelectedPSNCategory" class="badge bg-info"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="outstandingItemTableContainer">
                            <table id="outstandingItemTable" class="table table-hover table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle">MCZ</th>
                                        <th rowspan="2" class="align-middle">MC</th>
                                        <th rowspan="2" class="align-middle">Part Code</th>
                                        <th rowspan="2" class="align-middle">Part Name</th>
                                        <th rowspan="2" class="align-middle text-center">M/S</th>
                                        <th colspan="3" class="text-center">Qty</th>
                                    </tr>
                                    <tr>
                                        <th class="text-end">Requirement</th>
                                        <th class="text-end">Issue</th>
                                        <th class="text-end">Balance</th>
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
    function stakit_txt_job_ekeypress(e)
    {
        if(e.key==='Enter')
        {
            stakit_btn_findjob_e_click()
        }
    }
    $("#stakit_divku").css('height', $(window).height() -
        document.getElementById('stakit_stack0').offsetHeight -
        document.getElementById('stakit_stack1').offsetHeight -
        100);

    function stakit_btn_findjob_e_click() {
        let jobno = document.getElementById('stakit_txt_job').value;
        document.getElementById('stakit_btn_findjob').disabled = true;
        document.getElementById('stakit_btn_findjob').innerHTML = `<i class="fas fa-spinner fa-spin"></i>`
        $("#stakit_tbl tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/get_kitting_status_byjob')?>",
            data: {
                jobno: jobno,
                business: stakit_bisgrup.value,
            },
            dataType: "json",
            success: function(response) {
                document.getElementById('stakit_btn_findjob').innerHTML = `Search`
                document.getElementById('stakit_btn_findjob').disabled = false;
                let mtabel = document.getElementById("stakit_tbl");
                if (response.status.cd === '1') {
                    const ttlrows = response.data.length;
                    let mydes = document.getElementById("stakit_divku");
                    let myfrag = document.createDocumentFragment();
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("stakit_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = '';
                    let tmpnomor = '';
                    let simno = '';
                    let simno_dis = '';
                    let ttlqty = 0;
                    let stschip = '';
                    let stshw = '';
                    let stsic = '';
                    let stskanban = '';
                    let stspcb = '';
                    let stsprepare = '';
                    let stssp = '';
                    for (let i = 0; i < ttlrows; i++) {
                        //INIT STYLE
                        switch (response.data[i].STSCHIP) {
                            case 'O':
                                stschip = 'hijau';
                                break;
                            case 'T':
                                stschip = 'kuning';
                                break;
                            case 'X':
                                stschip = 'merah';
                                break;
                        }
                        switch (response.data[i].STSHW) {
                            case 'O':
                                stshw = 'hijau';
                                break;
                            case 'T':
                                stshw = 'kuning';
                                break;
                            case 'X':
                                stshw = 'merah';
                                break;
                        }
                        switch (response.data[i].STSIC) {
                            case 'O':
                                stsic = 'hijau';
                                break;
                            case 'T':
                                stsic = 'kuning';
                                break;
                            case 'X':
                                stsic = 'merah';
                                break;
                        }
                        switch (response.data[i].STSKANBAN) {
                            case 'O':
                                stskanban = 'hijau';
                                break;
                            case 'T':
                                stskanban = 'kuning';
                                break;
                            case 'X':
                                stskanban = 'merah';
                                break;
                        }
                        switch (response.data[i].STSPCB) {
                            case 'O':
                                stspcb = 'hijau';
                                break;
                            case 'T':
                                stspcb = 'kuning';
                                break;
                            case 'X':
                                stspcb = 'merah';
                                break;
                        }
                        switch (response.data[i].STSPREPARE) {
                            case 'O':
                                stsprepare = 'hijau';
                                break;
                            case 'T':
                                stsprepare = 'kuning';
                                break;
                            case 'X':
                                stsprepare = 'merah';
                                break;
                        }
                        switch (response.data[i].STSSP) {
                            case 'O':
                                stssp = 'hijau';
                                break;
                            case 'T':
                                stssp = 'kuning';
                                break;
                            case 'X':
                                stssp = 'merah';
                                break;
                        }

                        //
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.title = "PSN"
                        newcell.style.cssText = "cursor:pointer";
                        newcell.ondblclick = () => {
                            stakti_searchjob(response.data[i].SPL_DOC)
                        };
                        newcell.innerHTML = response.data[i].SPL_DOC

                        newcell = newrow.insertCell(1);
                        newcell.title = "CHIP";
                        if (!response.data[i].PRC_CHIP) {
                            stschip = 'abuabu'
                        } else {
                            newcell.title = numeral(response.data[i].PRC_CHIP).format('0,0') + '%'
                        }
                        newcell.classList.add('text-center', stschip)
                        if(response.data[i].STSCHIP === 'T'){
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.innerHTML = numeral(response.data[i].PRC_CHIP).format('0,0') + '%'
                            newcell.onclick = () => {
                                StatusKittingSelectedPSN.innerText = response.data[i].SPL_DOC
                                StatusKittingSelectedPSNCategory.innerText = 'CHIP'
                                $("#StatusKittingItemModal").modal('show')
                            }
                        } else {
                            newcell.innerHTML = response.data[i].STSCHIP
                        }

                        newcell = newrow.insertCell(2);
                        newcell.title = "HW";
                        if (!response.data[i].PRC_HW) {
                            stshw = 'abuabu'
                        } else {
                            newcell.title = numeral(response.data[i].PRC_HW).format('0,0') + '%'
                        }
                        newcell.classList.add('text-center', stshw);
                        if(response.data[i].STSHW === 'T'){
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.innerHTML = numeral(response.data[i].PRC_HW).format('0,0') + '%'
                            newcell.onclick = () => {
                                StatusKittingSelectedPSN.innerText = response.data[i].SPL_DOC
                                StatusKittingSelectedPSNCategory.innerText = 'HW'
                                $("#StatusKittingItemModal").modal('show')
                            }
                        } else {
                            newcell.innerHTML = response.data[i].STSHW
                        }

                        newcell = newrow.insertCell(3);
                        newcell.title = "IC";
                        if (!response.data[i].PRC_IC) {
                            stsic = 'abuabu'
                        } else {
                            newcell.title = numeral(response.data[i].PRC_IC).format('0,0') + '%'
                        }
                        newcell.classList.add('text-center', stsic);
                        if(response.data[i].STSIC === 'T'){
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.innerHTML = numeral(response.data[i].PRC_IC).format('0,0') + '%'
                            newcell.onclick = () => {
                                StatusKittingSelectedPSN.innerText = response.data[i].SPL_DOC
                                StatusKittingSelectedPSNCategory.innerText = 'IC'
                                $("#StatusKittingItemModal").modal('show')
                            }
                        } else {
                            newcell.innerHTML = response.data[i].STSIC
                        }

                        newcell = newrow.insertCell(4);
                        newcell.title = "KANBAN";
                        if (!response.data[i].PRC_KANBAN) {
                            stskanban = 'abuabu'
                        } else {
                            newcell.title = numeral(response.data[i].PRC_KANBAN).format('0,0') + '%'
                        }
                        newcell.classList.add('text-center', stskanban);

                        if(response.data[i].STSKANBAN === 'T'){
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.innerHTML = numeral(response.data[i].PRC_KANBAN).format('0,0') + '%'
                            newcell.onclick = () => {
                                StatusKittingSelectedPSN.innerText = response.data[i].SPL_DOC
                                StatusKittingSelectedPSNCategory.innerText = 'KANBAN'
                                $("#StatusKittingItemModal").modal('show')
                            }
                        } else {
                            newcell.innerHTML = response.data[i].STSKANBAN
                        }

                        newcell = newrow.insertCell(5);
                        newcell.title = "PCB";
                        if (!response.data[i].PRC_PCB) {
                            stspcb = 'abuabu'
                        } else {
                            newcell.title = numeral(response.data[i].PRC_PCB).format('0,0') + '%'
                        }
                        newcell.classList.add('text-center', stspcb);
                        if(response.data[i].STSPCB === 'T'){
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.innerHTML = numeral(response.data[i].PRC_PCB).format('0,0') + '%'
                            newcell.onclick = () => {
                                StatusKittingSelectedPSN.innerText = response.data[i].SPL_DOC
                                StatusKittingSelectedPSNCategory.innerText = 'PCB'
                                $("#StatusKittingItemModal").modal('show')
                            }
                        } else {
                            newcell.innerHTML = response.data[i].STSPCB
                        }

                        newcell = newrow.insertCell(6);
                        newcell.title = "PREPARE";
                        if (!response.data[i].PRC_PREPARE) {
                            stsprepare = 'abuabu'
                        } else {
                            newcell.title = numeral(response.data[i].PRC_PREPARE).format('0,0') + '%'
                        }
                        newcell.classList.add('text-center', stsprepare);
                        if(response.data[i].STSPREPARE === 'T'){
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.innerHTML = numeral(response.data[i].PRC_PREPARE).format('0,0') + '%'
                            newcell.onclick = () => {
                                StatusKittingSelectedPSN.innerText = response.data[i].SPL_DOC
                                StatusKittingSelectedPSNCategory.innerText = 'PREPARE'
                                $("#StatusKittingItemModal").modal('show')
                            }
                        } else {
                            newcell.innerHTML = response.data[i].STSPREPARE
                        }

                        newcell = newrow.insertCell(7);
                        newcell.title = "SP";
                        if (!response.data[i].PRC_SP) {
                            stssp = 'abuabu'
                        } else {
                            newcell.title = numeral(response.data[i].PRC_SP).format('0,0') + '%'
                        }
                        newcell.classList.add('text-center', stssp);

                        if(response.data[i].STSSP === 'T'){
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.innerHTML = numeral(response.data[i].PRC_SP).format('0,0') + '%'
                            newcell.onclick = () => {
                                StatusKittingSelectedPSN.innerText = response.data[i].SPL_DOC
                                StatusKittingSelectedPSNCategory.innerText = 'SP'
                                $("#StatusKittingItemModal").modal('show')
                            }
                        } else {
                            newcell.innerHTML = response.data[i].STSSP
                        }
                    }
                    mydes.innerHTML = '';
                    mydes.appendChild(myfrag);
                } else {
                    mtabel.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="8" class="text-center">${response.status.msg}</td></tr>`
                }
            },
            error: function(xhr, xopt, xthrow) {
                document.getElementById('stakit_btn_findjob').disabled = false;
                document.getElementById('stakit_btn_findjob').innerHTML = `Search`
                alertify.error(xthrow);
            }
        });
    }

    $("#StatusKittingItemModal").on('shown.bs.modal', function() {
        outstandingItemTable.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="8" class="text-center">Please wait...</td></tr>'
        const data = {
            doc : StatusKittingSelectedPSN.innerText
            ,category : StatusKittingSelectedPSNCategory.innerText
            }        
        $.ajax({
            type: "GET",
            url: "http://<?=$_SERVER['HTTP_HOST']?>/ems-glue/api/supply/outstanding-scan",
            data: data,
            dataType: "JSON",
            success: function (response) {
                if(response.data.length === 0){
                    outstandingItemTable.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="8" class="text-center">Not found</td></tr>'
                } else {
                    let mydes = document.getElementById("outstandingItemTableContainer");
                    let myfrag = document.createDocumentFragment();
                    let cln = outstandingItemTable.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("outstandingItemTable");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML = '';
                    response.data.sort((a,b) => {
                        let fa = a.SPL_ORDERNO.toLowerCase(),
                            fb = b.SPL_ORDERNO.toLowerCase();

                        if (fa < fb) {
                            return -1;
                        }
                        if (fa > fb) {
                            return 1;
                        }
                        return 0;
                    })
                    
                    response.data.forEach((arrayItem) => {
                        const balanceQT = numeral(arrayItem['REQQT']).value() - numeral(arrayItem['PLOTQT']).value()
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = arrayItem['SPL_ORDERNO']
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = arrayItem['SPL_MC']
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = arrayItem['SPL_ITMCD']
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = arrayItem['MITM_SPTNO']
                        newcell = newrow.insertCell(4)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = arrayItem['SPL_MS']
                        newcell = newrow.insertCell(5)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(arrayItem['REQQT']).format(',')
                        newcell = newrow.insertCell(6)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(arrayItem['PLOTQT']).format(',')
                        newcell = newrow.insertCell(7)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(balanceQT).format(',')
                    })
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                }
            },
            error: function(xhr, xopt, xthrow) {
                outstandingItemTable.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="8" class="text-center">${xthrow}</td></tr>`
            }
        })
    });


    function stakti_searchjob(ppsn) {
        $("#STAKIT_JOB_MOD").modal('show');
        document.getElementById('stakit_job_lblinfo_h').innerText = "Please wait...";
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/get_job_bypsn')?>",
            data: {
                psn: ppsn
            },
            dataType: "json",
            success: function(response) {
                let ttlrows = response.data.length;
                document.getElementById('stakit_job_lblinfo_h').innerText = "";
                let mydes = document.getElementById("stakit_job_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("stakit_tbljob");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("stakit_tbljob");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML = '';
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.data[i].PPSN1_WONO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newcell.classList.add("text-end");
                    newText = document.createTextNode(numeral(response.data[i].PDPP_WORQT).format(','));
                    newcell.appendChild(newText);
                }
                mydes.innerHTML = '';
                mydes.appendChild(myfrag);
            }
        });
    }
</script>