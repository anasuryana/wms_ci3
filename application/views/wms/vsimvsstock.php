<style>   
    .anastylesel_sim{
        background: red;
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
</style>
<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row" id="simvsstock_stack1">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Business Group</span>                    
                    <select class="form-select" id="simvsstock_bisgrup" onchange="simvsstock_bisgrup_e_change()">
                    </select>
                    <button class="btn btn-success" id="simvsstock_refreshBG" onclick="simvsstock_getBG()" title="refresh BG data"><i class="fas fa-sync-alt"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <span id="simvsstock_lblgetsimjobinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row" id="simvsstock_stack2">
            <div class="col-md-12 mb-1" onclick="simvsstock_divresume_e_click(event)">
                <div class="table-responsive" id="simvsstock_divku_resume">
                    <table id="simvsstock_tbl_resume" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th >Simulation Number</th>                                
                            </tr>
                        </thead>
                        <tbody>                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row" id="simvsstock_stack3">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="simvsstock_btn_simulate" onclick="simvsstock_btn_simulate_e_click()">Process</button>
                </div>
            </div>
        </div>
        <div class="row" >
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="simvsstock_divku">
                    <table id="simvsstock_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr>                            
                                <th rowspan="2" class="align-middle">Simulation Number</th>
                                <th rowspan="2" class="align-middle">Job Number</th>
                                <th rowspan="2" class="align-middle">Item Code</th>
                                <th rowspan="2" class="align-middle">Item Name</th>
                                <th rowspan="2" class="align-middle">Item Code SUB</th>                                
                                <th colspan="4" class="text-center">Qty</th>
                            </tr>
                            <tr>
                                <th class="text-end">Requirement</th>
                                <th class="text-end">Plot</th>
                                <th class="text-end">Plot SUB</th>
                                <th class="text-end">Shortage</th>
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
    var simvsstock_a_simno = [];
    function simvsstock_btn_simulate_e_click(){
        if(simvsstock_a_simno.length>0){
            if(confirm("Are you sure ?")){
                document.getElementById('simvsstock_btn_simulate').innerText = "Please wait";
                document.getElementById('simvsstock_btn_simulate').disabled = true;
                $("#simvsstock_tbl tbody").empty();
                $.ajax({
                    type: "post",
                    url: "<?=base_url('SPL/simulate_sim_vs_stock')?>",
                    data: {insimno: simvsstock_a_simno},
                    dataType: "json",
                    success: function (response) {
                        document.getElementById('simvsstock_btn_simulate').innerText = "Process";
                        document.getElementById('simvsstock_btn_simulate').disabled = false;
                        let ttlrows = response.data.length;
                        let mydes = document.getElementById("simvsstock_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("simvsstock_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);                    
                        let tabell = myfrag.getElementById("simvsstock_tbl");                    
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        let tmpnomor = '';
                        let simno = '';
                        let simno_dis = '';
                        let jobno = '';
                        let jobno_dis = '';
                        let shortage = 0;      
                        for (let i = 0; i<ttlrows; i++){
                            if(simno!=response.data[i].SIMNO){
                                simno=response.data[i].SIMNO;
                                simno_dis = simno;
                                jobno = response.data[i].PIS3_WONO;
                                jobno_dis = jobno;
                            } else {
                                simno_dis ='';
                                if(jobno != response.data[i].PIS3_WONO){
                                    jobno = response.data[i].PIS3_WONO;
                                    jobno_dis = jobno;
                                } else {
                                    jobno_dis = '';
                                }
                            }
                            shortage = numeral(response.data[i].REQQTY).value() - (numeral(response.data[i].PLOTQTY).value()+numeral(response.data[i].PLOTSUBQTY).value());
                            newrow = tableku2.insertRow(-1);
                            newcell = newrow.insertCell(0);                            
                            newText = document.createTextNode(simno_dis);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);                            
                            newText = document.createTextNode(jobno_dis);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(response.data[i].PIS3_MPART);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);
                            newText = document.createTextNode(response.data[i].MITM_SPTNO);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(4);
                            newText = document.createTextNode(response.data[i].PIS3_ITMCD);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(5);
                            newcell.title = "Requirement";
                            newcell.classList.add('text-end');
                            newText = document.createTextNode(numeral(response.data[i].REQQTY).format(','));                            
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(6);
                            newcell.title = "PLOT";
                            newcell.classList.add('text-end');
                            newText = document.createTextNode(numeral(response.data[i].PLOTQTY).format(','));
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(7);
                            newcell.title = "PLOT SUB";
                            newcell.classList.add('text-end');
                            newText = document.createTextNode(numeral(response.data[i].PLOTSUBQTY).format(','));
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(8);
                            newcell.title = "Shortage";
                            newcell.classList.add('text-end');
                            if(shortage>0){
                                newcell.classList.add('text-danger');
                            }
                            newText = document.createTextNode(numeral(shortage).format(','));
                            newcell.appendChild(newText);
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    }, error : function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                        document.getElementById('simvsstock_btn_simulate').innerText = "Process";
                        document.getElementById('simvsstock_btn_simulate').disabled = false;
                    }
                });
            }
        } else {
            alertify.message('Please select simulation no. first !');
        }
    }
    function simvsstock_divresume_e_click(e){          
        if(e.ctrlKey){   
            if(e.target.tagName.toLowerCase()=='td'){
                if(e.target.cellIndex==0){
                    let mtbl = document.getElementById('simvsstock_tbl_resume');
                    let tableku2 = mtbl.getElementsByTagName("tbody")[0]
                    let mtbltr = tableku2.getElementsByTagName('tr');
                    let ttlrows = mtbltr.length;
                    if(e.target.classList.contains('anastylesel_sim')){
                        e.target.classList.remove('anastylesel_sim'); 
                        let getINDX = simvsstock_a_simno.indexOf(e.target.textContent);
                        if(getINDX > -1){
                            simvsstock_a_simno.splice(getINDX, 1);
                        }
                    } else {
                        if(e.target.textContent.length!=0){
                            simvsstock_a_simno.push(e.target.textContent);
                            e.target.classList.add('anastylesel_sim');
                        }
                    }
                }
            }
        }
    }
    $("#simvsstock_divku_resume").css('height', $(window).height()*30/100);
    $("#simvsstock_divku").css('height', $(window).height()   
    -document.getElementById('simvsstock_stack1').offsetHeight 
    -document.getElementById('simvsstock_stack2').offsetHeight    
    -document.getElementById('simvsstock_stack3').offsetHeight    
    -100);
    simvsstock_getBG();
    function simvsstock_getBG(){
        document.getElementById('simvsstock_bisgrup').innerHTML = "<option value='-'>Please wait...</option>";
        $.ajax({
            url: "<?=base_url('SPL/get_bg_not_in_psn')?>",            
            dataType: "json",
            success: function (response) {
                let ttldata = response.data.length;
                let strbg = '<option value="-">Choose</option>';
                for(let i=0; i< ttldata; i++){
                    strbg += '<option value="'+response.data[i].MBSG_BSGRP+'">'+response.data[i].MBSG_DESC+'</option>';
                }
                if(ttldata==0){
                    strbg = "<option value='-'>nothing</option>";
                }
                document.getElementById('simvsstock_bisgrup').innerHTML = strbg;
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    function simvsstock_bisgrup_e_change(){
        simvsstock_a_simno = [];
        let selBG = document.getElementById('simvsstock_bisgrup').value;
        document.getElementById('simvsstock_lblgetsimjobinfo').innerText = "Please wait...";
        $("#simvsstock_tbl_resume tbody").empty();
        $("#simvsstock_tbl tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/get_sim_job_not_in_psn')?>",
            data: {inBG: selBG},
            dataType: "json",
            success: function (response) {
                document.getElementById('simvsstock_lblgetsimjobinfo').innerText = "";
                let ttlrows = response.data.length;
                let mydes = document.getElementById("simvsstock_divku_resume");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("simvsstock_tbl_resume");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("simvsstock_tbl_resume");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let simno = '';
                let simno_dis = '';
                let ttlqty = 0;                
                for (let i = 0; i<ttlrows; i++){
                    if(simno!=response.data[i].SIMNO){
                        simno=response.data[i].SIMNO;
                        simno_dis = simno;
                    } else {
                        simno_dis ='';
                    }
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.title = "Simulation Number";
                    newText = document.createTextNode(simno_dis);
                    newcell.appendChild(newText);                                                     
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
</script>