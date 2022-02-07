<style> 
table td.kuning  {
    background-color:yellow;    
}
table td.merah {
    background-color: red;
}
table td.hijau {
    background-color: greenyellow;    
}
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
        <div class="row" id="stakit_stack0">
            <div class="col-md-7 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Business Group</span>                    
                    <select class="form-select" id="stakit_bisgrup">
                        <option value="">All</option>
                        <?php 
                        $todis = '';
                        foreach($lgroup as $r){
                            $todis .= '<option value="'.trim($r->MBSG_BSGRP).'">'.trim($r->MBSG_DESC).'</option>';
                        }
                        echo $todis;
                        ?>
                    </select>
                    <button class="btn btn-primary" type="button" id="stakit_btn_simulate" onclick="stakit_btn_simulate_e_click()">All outstanding</button>
                </div>
            </div>
            <div class="col-md-5 mb-1 text-center">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Job</span> 
                    <input type="text" class="form-control" id="stakit_txt_job" required>                                        
                    <button class="btn btn-outline-primary" type="button" id="stakit_btn_findjob" onclick="stakit_btn_findjob_e_click()"><i class="fas fa-search"></i></button>   
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="stakit_divku">
                    <table id="stakit_tbl" class="table table-bordered table-sm table-hover" >
                        <thead class="table-light">
                            <tr class="first"> 
                                <th rowspan="2" class="align-middle">PSN</th>
                                <th colspan="7" class="align-middle text-center" >Category</th>
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
<script>
    $("#stakit_divku").css('height', $(window).height()   
    -document.getElementById('stakit_stack0').offsetHeight   
    -100);
    function stakit_btn_findjob_e_click(){
        let jobno = document.getElementById('stakit_txt_job').value;
        document.getElementById('stakit_btn_findjob').disabled = true;
        document.getElementById('stakit_btn_findjob').innerHTML = `<i class="fas fa-spinner fa-spin"></i>`
        $("#stakit_tbl tbody").empty();        
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/get_kitting_status_byjob')?>",
            data: {jobno: jobno},
            dataType: "json",
            success: function (response) {
                document.getElementById('stakit_btn_findjob').innerHTML = `<i class="fas fa-search"></i>`
                document.getElementById('stakit_btn_findjob').disabled = false;
                const ttlrows = response.data.length;
                let mydes = document.getElementById("stakit_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("stakit_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("stakit_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
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
                for (let i = 0; i<ttlrows; i++){
                    //INIT STYLE
                    switch(response.data[i].STSCHIP)
                    {
                        case 'O':
                            stschip = 'hijau';break;
                        case 'T':
                            stschip = 'kuning';break;
                        case 'X':
                            stschip = 'merah';break;
                    }
                    switch(response.data[i].STSHW)
                    {
                        case 'O':
                            stshw = 'hijau';break;
                        case 'T':
                            stshw = 'kuning';break;
                        case 'X':
                            stshw = 'merah';break;
                    }
                    switch(response.data[i].STSIC)
                    {
                        case 'O':
                            stsic = 'hijau';break;
                        case 'T':
                            stsic = 'kuning';break;
                        case 'X':
                            stsic = 'merah';break;
                    }
                    switch(response.data[i].STSKANBAN)
                    {
                        case 'O':
                            stskanban = 'hijau';break;
                        case 'T':
                            stskanban = 'kuning';break;
                        case 'X':
                            stskanban = 'merah';break;
                    }
                    switch(response.data[i].STSPCB)
                    {
                        case 'O':
                            stspcb = 'hijau';break;
                        case 'T':
                            stspcb = 'kuning';break;
                        case 'X':
                            stspcb = 'merah';break;
                    }
                    switch(response.data[i].STSPREPARE)
                    {
                        case 'O':
                            stsprepare = 'hijau';break;
                        case 'T':
                            stsprepare = 'kuning';break;
                        case 'X':
                            stsprepare = 'merah';break;
                    }
                    switch(response.data[i].STSSP)
                    {
                        case 'O':
                            stssp = 'hijau';break;
                        case 'T':
                            stssp = 'kuning';break;
                        case 'X':
                            stssp = 'merah';break;
                    }

                    //
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.title = "PSN"
                    newcell.style.cssText = "cursor:pointer";
                    newcell.ondblclick = () => {stakti_searchjob(response.data[i].SPL_DOC)};        
                    newcell.innerHTML = response.data[i].SPL_DOC

                    newcell = newrow.insertCell(1);
                    newcell.title = "CHIP";
                    newcell.classList.add('text-center', stschip)                    
                    newcell.innerHTML = response.data[i].STSCHIP

                    newcell = newrow.insertCell(2);
                    newcell.title = "HW";
                    newcell.classList.add('text-center', stshw);                    
                    newcell.innerHTML = response.data[i].STSHW

                    newcell = newrow.insertCell(3);
                    newcell.title = "IC";
                    newcell.classList.add('text-center', stsic);                    
                    newcell.innerHTML = response.data[i].STSIC

                    newcell = newrow.insertCell(4);
                    newcell.title = "KANBAN";
                    newcell.classList.add('text-center', stskanban);                    
                    newcell.innerHTML = response.data[i].STSKANBAN

                    newcell = newrow.insertCell(5);
                    newcell.title = "PCB";
                    newcell.classList.add('text-center', stspcb);
                    newText = document.createTextNode(response.data[i].STSPCB);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newcell.title = "PREPARE";
                    newcell.classList.add('text-center', stsprepare);
                    newText = document.createTextNode(response.data[i].STSPREPARE);
                    newcell.appendChild(newText);                    
                    newcell = newrow.insertCell(7);
                    newcell.title = "SP";
                    newcell.classList.add('text-center', stssp);
                    newText = document.createTextNode(response.data[i].STSSP);
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr,xopt,xthrow){
                document.getElementById('stakit_btn_findjob').disabled = false;
                document.getElementById('stakit_btn_findjob').innerHTML = `<i class="fas fa-search"></i>`
                alertify.error(xthrow);                
            }
        });
    }
    function stakit_btn_simulate_e_click(){
        document.getElementById('stakit_btn_simulate').disabled = true;
        document.getElementById('stakit_btn_simulate').innerHTML = "<i class='fas fa-spinner fa-spin'></i>";
        let bg = document.getElementById('stakit_bisgrup').value;
        document.getElementById('stakit_txt_job').value = ''
        $("#stakit_tbl tbody").empty();
        $.ajax({            
            url: "<?=base_url('SPL/get_kitting_status')?>",            
            dataType: "json",
            data: {bg : bg},
            success: function (response) {
                document.getElementById('stakit_btn_simulate').innerHTML = "All outstanding";
                document.getElementById('stakit_btn_simulate').disabled = false;
                let ttlrows = response.data.length;
                let mydes = document.getElementById("stakit_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("stakit_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("stakit_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
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
                for (let i = 0; i<ttlrows; i++){
                    //INIT STYLE
                    switch(response.data[i].STSCHIP)
                    {
                        case 'O':
                            stschip = 'hijau';break;
                        case 'T':
                            stschip = 'kuning';break;
                        case 'X':
                            stschip = 'merah';break;
                    }
                    switch(response.data[i].STSHW)
                    {
                        case 'O':
                            stshw = 'hijau';break;
                        case 'T':
                            stshw = 'kuning';break;
                        case 'X':
                            stshw = 'merah';break;
                    }
                    switch(response.data[i].STSIC)
                    {
                        case 'O':
                            stsic = 'hijau';break;
                        case 'T':
                            stsic = 'kuning';break;
                        case 'X':
                            stsic = 'merah';break;
                    }
                    switch(response.data[i].STSKANBAN)
                    {
                        case 'O':
                            stskanban = 'hijau';break;
                        case 'T':
                            stskanban = 'kuning';break;
                        case 'X':
                            stskanban = 'merah';break;
                    }
                    switch(response.data[i].STSPCB)
                    {
                        case 'O':
                            stspcb = 'hijau';break;
                        case 'T':
                            stspcb = 'kuning';break;
                        case 'X':
                            stspcb = 'merah';break;
                    }
                    switch(response.data[i].STSPREPARE)
                    {
                        case 'O':
                            stsprepare = 'hijau';break;
                        case 'T':
                            stsprepare = 'kuning';break;
                        case 'X':
                            stsprepare = 'merah';break;
                    }
                    switch(response.data[i].STSSP)
                    {
                        case 'O':
                            stssp = 'hijau';break;
                        case 'T':
                            stssp = 'kuning';break;
                        case 'X':
                            stssp = 'merah';break;
                    }

                    //
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.title = "PSN";
                    newcell.style.cssText = "cursor:pointer";
                    newcell.ondblclick = () => {stakti_searchjob(response.data[i].SPL_DOC)};
                    newText = document.createTextNode(response.data[i].SPL_DOC);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newcell.title = "CHIP";
                    newcell.classList.add('text-center', stschip);                    
                    newText = document.createTextNode(response.data[i].STSCHIP);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newcell.title = "HW";
                    newcell.classList.add('text-center', stshw);
                    newText = document.createTextNode(response.data[i].STSHW);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newcell.title = "IC";
                    newcell.classList.add('text-center', stsic);
                    newText = document.createTextNode(response.data[i].STSIC);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newcell.title = "KANBAN";
                    newcell.classList.add('text-center', stskanban);
                    newText = document.createTextNode(response.data[i].STSKANBAN);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(5);
                    newcell.title = "PCB";
                    newcell.classList.add('text-center', stspcb);
                    newText = document.createTextNode(response.data[i].STSPCB);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newcell.title = "PREPARE";
                    newcell.classList.add('text-center', stsprepare);
                    newText = document.createTextNode(response.data[i].STSPREPARE);
                    newcell.appendChild(newText);                    
                    newcell = newrow.insertCell(7);
                    newcell.title = "SP";
                    newcell.classList.add('text-center', stssp);
                    newText = document.createTextNode(response.data[i].STSSP);
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr,xopt,xthrow){
                document.getElementById('stakit_btn_simulate').disabled = false;
                document.getElementById('stakit_btn_simulate').innerHTML = "All outstanding";
                alertify.error(xthrow);
            }
        });        
    }
    function stakti_searchjob(ppsn){
        $("#STAKIT_JOB_MOD").modal('show');
        document.getElementById('stakit_job_lblinfo_h').innerText = "Please wait...";
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/get_job_bypsn')?>",
            data: {psn: ppsn},
            dataType: "json",
            success: function (response) {
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
                tableku2.innerHTML='';
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);                    
                    newText = document.createTextNode(response.data[i].PPSN1_WONO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newcell.classList.add("text-end");
                    newText = document.createTextNode(numeral(response.data[i].PDPP_WORQT).format(','));
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);                
            }
        });
    }
</script>