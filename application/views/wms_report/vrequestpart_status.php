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
</style>
<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row" id="stapartreq_stack1">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Business Group</span>                    
                    <select class="form-select" id="stapartreq_bisgrup">
                        <option value="">All</option>
                        <?php 
                        $todis = '';
                        foreach($lgroup as $r){
                            $todis .= '<option value="'.trim($r->MBSG_BSGRP).'">'.trim($r->MBSG_DESC).'</option>';
                        }
                        echo $todis;
                        ?>
                    </select>
                    <button class="btn btn-primary" type="button" id="stapartreq_btn_simulate" onclick="stapartreq_btn_simulate_e_click()">All outstanding</button>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="stapartreq_divku">
                    <table id="stapartreq_tbl" class="table table-bordered table-sm table-hover" >
                        <thead class="table-light">
                            <tr> 
                                <th rowspan="2" class="align-middle">PSN</th> 
                                <th rowspan="2" class="align-middle">Remark</th> 
                                <th colspan="1" class="align-middle text-center" >Category</th>
                                <th rowspan="2" class="align-middle text-center">Approval</th> 
                                <th rowspan="2" class="align-middle text-center"></th> 
                            </tr>
                            <tr> 
                            <th class="align-middle text-center">_</th> 
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
<div class="modal fade" id="stapartreq_ost">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Outstanding List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">  
            <div class="row">
                <div class="col mb-1 text-end">
                    <span id="stapartreq_job_lblinfo_h" class="badge bg-info"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="stapartreq_job_divku">
                        <table id="stapartreq_tbljob" class="table table-hover table-sm table-bordered">
                            <thead class="table-light">
                                <tr>                   
                                    <th rowspan="2" class="align-middle">Part Code</th>
                                    <th rowspan="2" class="align-middle">Part Name</th>
                                    <th class="text-center" colspan="2">QTY</th>
                                </tr>
                                <tr>
                                    <th class="text-end">Request</th>
                                    <th class="text-end">Scanned</th>
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
    $("#stapartreq_divku").css('height', $(window).height()   
    -document.getElementById('stapartreq_stack1').offsetHeight     
    -100);
    function pareq_btnshow_approve(pdoc){
        if(wms_usergroupid=="MSPV" || wms_usergroupid=="QACT" ){
            if(confirm("Are You sure ?")){
                $.ajax({
                    type: "post",
                    url: "<?=base_url('SPL/approve_partreq')?>",
                    data: {doc:pdoc},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd==1){
                            alertify.success(response.status[0].msg);
                            stapartreq_btn_simulate_e_click();
                        } else {
                            alertify.warning(response.status[0].msg);
                        }
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        } else {
            alertify.warning("You could not approve this document");
            return
        }        
    }
    function stapartreq_btn_simulate_e_click(){
        document.getElementById('stapartreq_btn_simulate').disabled = true;
        document.getElementById('stapartreq_btn_simulate').innerHTML = "<i class='fas fa-spinner fa-spin'></i>";
        let bg = document.getElementById('stapartreq_bisgrup').value;
        $("#stapartreq_tbl tbody").empty();
        $.ajax({            
            url: "<?=base_url('SPL/get_partreq_status')?>",            
            dataType: "json",
            data: {bg : bg},
            success: function (response) {
                document.getElementById('stapartreq_btn_simulate').innerHTML = "All outstanding";
                document.getElementById('stapartreq_btn_simulate').disabled = false;
                const ttlrows = response.data.length;
                let mydes = document.getElementById("stapartreq_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("stapartreq_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("stapartreq_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let simno = '';
                let simno_dis = '';
                let ttlqty = 0;
                let stschip = '';
                let stssymbol = '';
                for (let i = 0; i<ttlrows; i++){
                    //INIT STYLE
                    switch(response.data[i].STSCHIP)
                    {
                        case 'O':
                            stschip = 'hijau'; stssymbol = 'O' ; break;
                        case 'T':
                            stschip = 'kuning'; stssymbol = '▲';break;
                        case 'X':
                            stschip = 'merah'; stssymbol = 'X';break;
                    }
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.title = "PSN";
                    newcell.style.cssText = "cursor:pointer";
                    newcell.classList.add('font-monospace')
                    newcell.ondblclick = () => {stapartreq_searchjob(response.data[i].SPL_DOC)};
                    newcell.innerHTML = response.data[i].SPL_DOC;

                    newcell = newrow.insertCell(1);
                    newcell.title = "PSN Remark";
                    newcell.innerHTML = response.data[i].RQSRMRK_DESC;

                    newcell = newrow.insertCell(2);
                    newcell.title = "CHIP";
                    newcell.classList.add('text-center', stschip);
                    newcell.innerHTML = stssymbol;

                    newcell = newrow.insertCell(3);
                    
                    newcell.classList.add('text-center');
                    newcell.innerHTML = response.data[i].SPL_APPRV_TM ? 'Approved' : '?'
                    if(!response.data[i].SPL_APPRV_TM){
                        newcell.title = "Approval";
                        newcell.style.cssText = "cursor:pointer"
                        newcell.onclick = () => {  pareq_btnshow_approve(response.data[i].SPL_DOC)  }
                        newcell = newrow.insertCell(4);
                        newcell.title = "Printable ?";
                        newcell.innerHTML = ``
                    } else {
                        newcell.title = `${response.data[i].MSTEMP_FNM} : ${response.data[i].SPL_APPRV_TM}`
                        newcell = newrow.insertCell(4);
                        newcell.title = "Print Kitting Instruction";
                        newcell.classList.add('text-center');
                        newcell.style.cssText = "cursor:pointer"
                        newcell.innerHTML = `<i class="fas fa-print"></i>`
                        newcell.onclick = () => {stapartreq_printki(response.data[i].SPL_DOC)}
                    }
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr,xopt,xthrow){
                document.getElementById('stapartreq_btn_simulate').disabled = false;
                document.getElementById('stapartreq_btn_simulate').innerHTML = "All outstanding";
                alertify.error(xthrow);
            }
        });        
    }

    function stapartreq_printki(pdoc){
        if(confirm("Are you sure ?")){            
            Cookies.set('CKPSI_DPSN', pdoc, {expires:365});
            window.open("<?=base_url('SPL/printkit_all')?>",'_blank');
        }
    }
    function stapartreq_searchjob(ppsn){
        $("#stapartreq_ost").modal('show');
        document.getElementById('stapartreq_job_lblinfo_h').innerText = "Please wait...";
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/get_request_status_d')?>",
            data: {doc: ppsn},
            dataType: "json",
            success: function (response) {
                const ttlrows = response.data.length;
                document.getElementById('stapartreq_job_lblinfo_h').innerText = "";
                let mydes = document.getElementById("stapartreq_job_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("stapartreq_tbljob");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("stapartreq_tbljob");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.data[i].SPL_ITMCD;
                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].MITM_SPTNO;
                    newcell = newrow.insertCell(2);
                    newcell.classList.add("text-end");
                    newcell.innerHTML = numeral(response.data[i].REQ).format(',')
                    newcell = newrow.insertCell(3);
                    newcell.classList.add("text-end");
                    newcell.innerHTML = numeral(response.data[i].SCN).format(',')
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);                
            }
        });
    }
</script>