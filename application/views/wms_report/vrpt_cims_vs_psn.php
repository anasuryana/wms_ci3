<style type="text/css">
	.tagbox-remove{
		display: none;
	}    
</style>
<div style="padding: 10px">
    <div class="container-fluid">                     
        <div class="row">            
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text">TX ID</span>                    
                    <input type="text" class="form-control" id="cimsvspsn_txt_id" required readonly>                    
                    <button class="btn btn-outline-primary" type="button" id="cimsvspsn_btn_findmod" onclick="cimsvspsn_btn_findmod_e_click()"><i class="fas fa-search"></i></button>
                    <button class="btn btn-outline-primary" type="button" id="cimsvspsn_btn_sync" onclick="cimsvspsn_btn_sync_e_click()" title="Refresh"><i class="fas fa-sync"></i></button>                    
                </div>
            </div>
            <div class="col-md-8 mb-1">
                <span id="cimsvspsn_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="cimsvspsn_divku">
                    <table id="cimsvspsn_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr>
                                <th  class="align-middle">Job Number</th>
                                <th  class="align-middle">Assy Code</th>
                                <th  class="align-middle">Assy Name</th>
                                <th  class="align-middle">Raw Material Status</th>
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
<div class="modal fade" id="cimsvspsn_DTLMOD">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">TX List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">              
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">Search</label>                        
                        <input type="text" class="form-control" id="cimsvspsn_txt_search">
                    </div>
                </div>
            </div>   
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text"><input type="checkbox" checked id="cimsvspsn_ck"></label>                        
                        <select id="cimsvspsn_monthfilter" class="form-select">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>                        
                        <input type="number" class="form-control" id="cimsvspsn_year" maxlength="4">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">Date</label>                        
                        <input type="text" class="form-control" id="cimsvspsn_datefilter" readonly>                        
                        <button class="btn btn-secondary" id="cimsvspsn_btn_filterdate" onclick="cimsvspsn_btn_filterdate_e_click()"><i class="fas fa-backspace"></i> </button>                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1 text-center">
                    <button class="btn btn-outline-primary btn-sm" id="cimsvspsn_btn_filter" onclick="cimsvspsn_btn_filter_e_click()" title="filter"><i class="fas fa-filter"></i></button>
                </div>
            </div>        
            <div class="row">
                <div class="col text-end mb-1">
                    <span class="badge bg-info" id="lblinfo_cimsvspsn_tbldono"></span>
                </div>
            </div>
            <div class="row">
                <div class="col" id="cimsvspsn_divku_search">
                    <table id="cimsvspsn_tbltxid" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th>TX ID</th>
                                <th>Business Group</th>
                                <th>Customer</th>                                
                                <th>Status</th>
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
<div id="cimsvspsn_w_jobreq"  class="easyui-window" title="Simulation" 
    data-options="modal:false,closed:true,iconCls:'icon-analyze',collapsible:true,minimizable:false,
    top: 305,
    left: 0,
    onClose:function(){
        $('#cimsvspsn_reqtbl_rm tbody').empty();
        }" 
    style="width:600px;height:350px;padding:5px;" >
    <div style="padding:1px" >
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Job Number</span>                        
                        <input type="text" class="form-control" id="cimsvspsn_req_job" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Lot Size</span>                        
                        <input type="text" class="form-control" id="cimsvspsn_req_itmqty" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Assy Code</span>                        
                        <input type="text" class="form-control" id="cimsvspsn_req_itmcd" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Assy Name</span>                        
                        <input type="text" class="form-control" id="cimsvspsn_req_itmnm" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="cimsvspsn_reqdiv_rm">
                        <table id="cimsvspsn_reqtbl_rm" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-end" colspan="5">Total :</th>
                                    <th class="text-end"><b><span id="cimsvspsn_req_ttl_per"></span></b></th>
                                    <th class="text-end" colspan="4">
                                        <div class="btn-group btn-group" role="group" aria-label="Filter">
                                            <button type="button" class="btn btn-outline-secondary" id="cimsvspsn_req_btn_all" onclick="cimsvspsn_req_btn_all_e_click()">All</button>
                                            <button type="button" class="btn btn-outline-secondary" id="cimsvspsn_req_btn_discrep" onclick="cimsvspsn_req_btn_discrep_e_click()">Discrepancy</button>
                                        </div>
                                    </th>
                                    <th rowspan="2" class="align-middle">Action</th>
                                </tr>
                                <tr>
                                    <th>Line No</th>
                                    <th>Process</th>
                                    <th class="text-center">F/R</th>
                                    <th class="text-center">MC</th>
                                    <th class="text-center">MCZ</th>
                                    <th class="text-center">PER</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th class="text-end">QTY</th>                                    
                                    <th class="text-center">Kind</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="cimsvspsn_reqdiv_rm_resume">
                        <table id="cimsvspsn_reqtbl_rm_resume" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">   
                                <tr>
                                    <th colspan="5">Resume</th>
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <th>Process</th>
                                    <th>MC</th>
                                    <th>MCZ</th>
                                    <th class="text-end">Req. Per</th>
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
<div id="cimsvspsn_w_psnjob"  class="easyui-window" title="Detail of PSN" 
    data-options="modal:false,closed:true,iconCls:'icon-analyze',collapsible:true, minimizable:false,    
    right: 0,
    top: 0,    
    onClose:function(){
        $('#cimsvspsn_psn_list').tagbox('setValues', []);        
    }" 
    style="width:100%;height:300px;padding:5px;">
    <div style="padding:1px" >
        <div class="container-fluid">  
            <div class="row">
                <div class="col-md-12 mb-1">
                    <input type="text" style="width:100%" id="cimsvspsn_psn_list" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="cimsvspsn_divdetailpsn">
                        <table id="cimsvspsn_tbldetailpsn" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">                                
                                <tr>
                                    <th>DOC NO</th>
                                    <th>PSN NO</th>
                                    <th>LINE NO</th>
                                    <th>Process</th>
                                    <th>FR</th>
                                    <th>Category</th>
                                    <th class="text-center">MC</th>
                                    <th class="text-center">MCZ</th>          
                                    <th class="text-center">S/M</th>
                                    <th class="text-center">Item Code</th>
                                    <th class="text-center">Item Name</th>
                                    <th class="text-center">Kind</th>
                                    <th class="text-end">REQ QTY</th>
                                    <th class="text-end">ACT QTY</th>
                                    <th class="text-end">RTN QTY</th>
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
<div id="cimsvspsn_w_calculation"  class="easyui-window" title="Calculation Result" 
    data-options="modal:false,closed:true,iconCls:'icon-analyze',collapsible:true,minimizable:false,
    right:601,    
    top:305,    
    onClose:function(){
        $('#cimsvspsn_caltbl_rm tbody').empty();
        $('#cimsvspsn_w_psnjob').window('close');
        $('#cimsvspsn_w_jobreq').window('close');
        }"
    style="width:600px;height:350px;padding:10px;" >
    <div style="padding:1px" >
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >ID Sample</span>                        
                        <input type="text" class="form-control" id="cimsvspsn_cal_ID" readonly>
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="cimsvspsn_caldiv_rm">
                        <table id="cimsvspsn_caltbl_rm" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-end" colspan="5">Total :</th>
                                    <th class="text-end"><b><span id="cimsvspsn_cal_ttl_per"></span></b></th>
                                    <th colspan="2"></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>Line No</th>
                                    <th>Process</th>
                                    <th class="text-center">F/R</th>
                                    <th class="text-center">MC</th>
                                    <th class="text-center">MCZ</th>
                                    <th class="text-center">PER</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th class="text-end">DLV QTY</th>                                    
                                    <th class="text-center">Kind</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="cimsvspsn_caldiv_rm_resume">
                        <table id="cimsvspsn_caltbl_rm_resume" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="5">Resume</th>
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <th>Process</th>
                                    <th>MC</th>
                                    <th>MCZ</th>
                                    <th class="text-end">Result. PER</th>
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
<div id="cimsvspsn_w_psnfilter"  class="easyui-window" title="Action" 
    data-options="modal:true,closed:true,iconCls:'icon-analyze',collapsible:true, minimizable:false,    
    right: 0,
    top: 0,
    cls: 'c6',
    onClose:function(){
        $('#cimsvspsn_alert').html('');
        }
    " 
    style="width:100%;height:500px;padding:5px;">
    <div style="padding:1px" >
        <div class="container-fluid"> 
            <input type="hidden" id="cimsvspsn_txt_line">
            <input type="hidden" id="cimsvspsn_txt_mc">
            <input type="hidden" id="cimsvspsn_txt_mcz">
            <input type="hidden" id="cimsvspsn_txt_per">
            <div class="row">
                <div class="col-md-12 mb-1 text-center">
                    <button class="btn btn-sm btn-primary" id="cimsvspsn_add" onclick="cimsvspsn_e_addto_cal()">Add selected data to calculation</button>
                    <button class="btn btn-sm btn-outline-primary" onclick="cimsvspsn_e_flagok()">Flag as OK</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1 text-center" id="cimsvspsn_alert">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <table id="cimsvspsn_tblpsnfilter" class="table table-sm table-striped table-bordered" style="width:100%;cursor:pointer">
                        <thead class="table-light">
                            <tr>
                                <th>Doc. No</th><!-- 0 -->
                                <th>PSN No</th><!-- 1 -->
                                <th>Process</th><!-- 2 -->
                                <th>Line</th><!-- 3 -->
                                <th>FR</th><!-- 4 -->
                                <th>Category</th><!-- 5 -->
                                <th>MC</th><!-- 6 -->
                                <th>MCZ</th><!-- 7 -->
                                <th>Item Code</th><!-- 8 -->
                                <th>Item Name</th><!-- 9 -->
                                <th>Kind</th><!-- 10 -->
                                <th>ACT QTY</th><!-- 11 -->
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
    var cimsvspsn_DTABLE_psn;
    var cimsvspsn_docno;
    var cimsvspsn_procd;
    var cimsvspsn_req_rows;
    function cimsvspsn_btn_findmod_e_click(){
        $("#cimsvspsn_DTLMOD").modal('show');
    }
    document.getElementById('cimsvspsn_monthfilter').value = (new Date().getMonth()+1)>9 ? (new Date().getMonth()+1) : "0"+(new Date().getMonth()+1);
    document.getElementById('cimsvspsn_year').value = new Date().getFullYear();

    $("#cimsvspsn_datefilter").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });

    function cimsvspsn_btn_filterdate_e_click(){
        document.getElementById('cimsvspsn_datefilter').value="";
    }

    function cimsvspsn_btn_filter_e_click(){
        let msearch = document.getElementById('cimsvspsn_txt_search').value;
        let mpermonth = document.getElementById('cimsvspsn_ck').checked ? 'y' : 'n';
        let mmonth = document.getElementById('cimsvspsn_monthfilter').value;
        let myear = document.getElementById('cimsvspsn_year').value;
        let mdate = document.getElementById('cimsvspsn_datefilter').value;
        document.getElementById('lblinfo_cimsvspsn_tbldono').innerText = "Please wait...";
        document.getElementById('cimsvspsn_btn_filter').disabled = true;
        $("#cimsvspsn_tbltxid tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/searchv')?>",
            data: {insearch: msearch, inmonth:mmonth, inyear:myear, indate:mdate, inispermonth : mpermonth},
            dataType: "json",
            success: function (response) {
                document.getElementById('cimsvspsn_btn_filter').disabled = false;
                let ttlrows  = response.data.length;
                document.getElementById('lblinfo_cimsvspsn_tbldono').innerText = ttlrows+ " row(s) found";
                let mydes = document.getElementById("cimsvspsn_divku_search");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("cimsvspsn_tbltxid");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                
                let tabell = myfrag.getElementById("cimsvspsn_tbltxid");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                for(let i=0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.onclick = function(){cimsvspsn_e_getjob_status(response.data[i].DLV_ID)}; 
                    newText = document.createTextNode(response.data[i].DLV_ID);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].MBSG_DESC);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data[i].MCUS_CUSNM);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.data[i].STATUS);
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('cimsvspsn_btn_filter').disabled = false;
            }
        });
    }

    function cimsvspsn_btn_sync_e_click(){
        let txid = document.getElementById('cimsvspsn_txt_id').value;
        if(txid==''){
            alertify.message("Please select TX ID first");
            document.getElementById('cimsvspsn_btn_findmod').focus();
            return;
        }
        cimsvspsn_e_getjob_status(txid);
    }

    function cimsvspsn_e_getjob_status(pdo){
        $("#cimsvspsn_DTLMOD").modal('hide');
        document.getElementById('cimsvspsn_lblinfo').innerText = "Please wait...";
        document.getElementById('cimsvspsn_txt_id').value=pdo;
        $("#cimsvspsn_tbl tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/getjobstatus')?>",
            data: {indo: pdo },
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                document.getElementById('cimsvspsn_lblinfo').innerText = ttlrows+ " row(s) found";
                let mydes = document.getElementById("cimsvspsn_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("cimsvspsn_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln); 
                let tabell = myfrag.getElementById("cimsvspsn_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                for(let i=0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.data[i].SER_DOC);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].SER_ITMID);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.data[i].STATUS);
                    if(response.data[i].STATUS.substr(0,2)!='OK'){
                        newcell.onclick = function(){cimsvspsn_e_compare(response.data[i].SER_DOC,response.data[i].SER_ITMID, response.data[i].MITM_ITMD1 );};
                        newcell.style.cssText = "cursor:pointer";
                    }
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function cimsvspsn_e_compare(pjob, passycd, passynm){
        document.getElementById('cimsvspsn_req_job').value=pjob;
        document.getElementById('cimsvspsn_req_itmcd').value=passycd;
        document.getElementById('cimsvspsn_req_itmnm').value=passynm;
        let txid = document.getElementById('cimsvspsn_txt_id').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/getjobstatus_compare')?>",
            data: {indo: txid, injob: pjob},
            dataType: "json",
            success: function (response) {
                let ttlrows_req = response.datareq.length;
                let ttlrows_psn = response.datapsn.length;
                let ttlrows_cal = response.datacal.length;
                let ttlrows_msp = response.datamsp.length;
                cimsvspsn_docno = response.datadoc.docno
                let mydes_req = document.getElementById("cimsvspsn_reqdiv_rm");
                let myfrag_req = document.createDocumentFragment();
                let mtabel_req = document.getElementById("cimsvspsn_reqtbl_rm");
                let cln_req = mtabel_req.cloneNode(true);
                myfrag_req.appendChild(cln_req);                
                let tabell_req = myfrag_req.getElementById("cimsvspsn_reqtbl_rm");  
                let percontainer_req = myfrag_req.getElementById("cimsvspsn_req_ttl_per");                  
                let tableku2_req = tabell_req.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2_req.innerHTML='';
                let lotsize = 0;
                let ttlper_req = 0;
                if(ttlrows_req>0){
                    for(let i=0; i<1; i++){
                        lotsize = response.datareq[i].PDPP_WORQT;
                    }                    
                }
                document.getElementById('cimsvspsn_req_itmqty').value = numeral(lotsize).format(',');
                let ca_resumereq = [];                
                for(let i=0; i<ttlrows_req; i++){
                    ttlper_req+=numeral(response.datareq[i].MYPER).value();
                    newrow = tableku2_req.insertRow(-1);
                    for(let n=0; n< ttlrows_cal; n++){
                        if(response.datareq[i].PIS3_LINENO.trim() == response.datacal[n].SERD2_LINENO
                        && response.datareq[i].PIS3_PROCD.trim() == response.datacal[n].SERD2_PROCD
                        && response.datareq[i].PIS3_FR.trim() == response.datacal[n].SERD2_FR
                        && response.datareq[i].PIS3_MC.trim() == response.datacal[n].SERD2_MC
                        && response.datareq[i].PIS3_MCZ.trim() == response.datacal[n].SERD2_MCZ
                        && numeral(response.datareq[i].MYPER).format('0.00') == numeral(response.datacal[n].SERD2_QTPER).format('0.00')
                        ){
                            newrow.classList.add("table-success");
                            break;
                        }
                    }

                    let isfound = false;
                    for(let c in ca_resumereq){
                        if(ca_resumereq[c].process == response.datareq[i].PIS3_PROCD.trim()
                        && ca_resumereq[c].mc == response.datareq[i].PIS3_MC.trim()
                        && ca_resumereq[c].mcz == response.datareq[i].PIS3_MCZ.trim()
                        && ca_resumereq[c].lineno == response.datareq[i].PIS3_LINENO.trim() ){
                            ca_resumereq[c].per += numeral(response.datareq[i].MYPER).value();
                            isfound = true;
                            break;                            
                        }
                    }
                    if(!isfound){
                        let newobku = {
                            process : response.datareq[i].PIS3_PROCD.trim(), 
                            mc : response.datareq[i].PIS3_MC.trim(), 
                            mcz : response.datareq[i].PIS3_MCZ.trim(), 
                            per: numeral(response.datareq[i].MYPER).value(),
                            lineno: response.datareq[i].PIS3_LINENO.trim(),
                            match: false
                        };
                        ca_resumereq.push(newobku);
                    }
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.datareq[i].PIS3_LINENO.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.datareq[i].PIS3_PROCD.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.datareq[i].PIS3_FR.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.datareq[i].PIS3_MC.trim());                    
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(response.datareq[i].PIS3_MCZ.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(5);
                    newcell.style.cssText = "text-align:right";
                    newText = document.createTextNode(numeral(response.datareq[i].MYPER).format('0.00'));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.datareq[i].PIS3_MPART.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(7);
                    newText = document.createTextNode(response.datareq[i].MITM_SPTNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(8);
                    newcell.style.cssText = "text-align:right";
                    newText = document.createTextNode(numeral(response.datareq[i].PIS3_REQQTSUM).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(9);
                    newText = document.createTextNode(response.datareq[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(10);
                    newText = document.createTextNode('');
                    newcell.appendChild(newText);
                }                
                percontainer_req.innerText = ttlper_req;
                let mrows_req = tableku2_req.getElementsByTagName("tr");                
                let mrows_req_length = mrows_req.length;
                

                //Calculation result                
                let mydes = document.getElementById("cimsvspsn_caldiv_rm");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("cimsvspsn_caltbl_rm");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("cimsvspsn_caltbl_rm");
                let percontainer = myfrag.getElementById("cimsvspsn_cal_ttl_per");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML=''; 
                if(ttlrows_cal>0){
                    for(let i=0; i<1; i++){
                        document.getElementById('cimsvspsn_cal_ID').value = response.datacal[i].SERD2_SER;
                    }
                }
                let ttlper_cal = 0;
                let ca_resumecal = [];
                for(let i=0; i<ttlrows_cal; i++){
                    let isfound = false;
                    for(let c in ca_resumecal){
                        if(ca_resumecal[c].process == response.datacal[i].SERD2_PROCD
                        && ca_resumecal[c].mc == response.datacal[i].SERD2_MC
                        && ca_resumecal[c].mcz == response.datacal[i].SERD2_MCZ
                        && ca_resumecal[c].lineno == response.datacal[i].SERD2_LINENO ){
                            ca_resumecal[c].per += numeral(response.datacal[i].SERD2_QTPER).value();
                            isfound = true;
                            break;
                        }
                    }
                    if(!isfound){
                        let newobku = {
                            process : response.datacal[i].SERD2_PROCD, 
                            mc: response.datacal[i].SERD2_MC,
                            mcz: response.datacal[i].SERD2_MCZ,
                            per: numeral(response.datacal[i].SERD2_QTPER).value(),
                            lineno: response.datacal[i].SERD2_LINENO
                            };
                        ca_resumecal.push(newobku);
                    }
                    ttlper_cal+=numeral(response.datacal[i].SERD2_QTPER).value();
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.datacal[i].SERD2_LINENO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.datacal[i].SERD2_PROCD);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.datacal[i].SERD2_FR);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.datacal[i].SERD2_MC);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(response.datacal[i].SERD2_MCZ);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(5);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(response.datacal[i].SERD2_QTPER).format('0.00'));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.datacal[i].SERD2_ITMCD);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(7);
                    newText = document.createTextNode(response.datacal[i].MITM_SPTNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(8);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(response.datacal[i].SERD2_QTY).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(9);
                    newText = document.createTextNode(response.datacal[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                }
                percontainer.innerText = ttlper_cal;
                mydes.innerHTML='';
                mydes.appendChild(myfrag);

                //req. resume
                mydes = document.getElementById("cimsvspsn_reqdiv_rm_resume");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("cimsvspsn_reqtbl_rm_resume");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("cimsvspsn_reqtbl_rm_resume");
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML='';
                for(let c in ca_resumereq){
                    newrow = tableku2.insertRow(-1);
                    for(let i in ca_resumecal){
                        if(ca_resumereq[c].process.trim()==ca_resumecal[i].process.trim() 
                        && ca_resumereq[c].mc.trim()==ca_resumecal[i].mc.trim()
                        && ca_resumereq[c].mcz.trim()==ca_resumecal[i].mcz.trim()
                        && ca_resumereq[c].lineno==ca_resumecal[i].lineno
                        && ca_resumereq[c].per==ca_resumecal[i].per
                        ){
                            newrow.classList.add("table-success");
                            ca_resumereq[c].match = true;
                            break;
                        }
                    }
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(ca_resumereq[c].lineno);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(ca_resumereq[c].process);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(ca_resumereq[c].mc);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(ca_resumereq[c].mcz);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(ca_resumereq[c].per).format('0.00'));
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);

                let ca_unmatch = [];
                let ca_unmatch_2 = [];
                for(let r in ca_resumereq){
                    if(!ca_resumereq[r].match){
                        ca_unmatch.push(ca_resumereq[r]);
                    }
                }
                for(let u in ca_unmatch){                    
                    for(let n=0; n<ttlrows_cal; n++){                        
                        if(
                        response.datacal[n].SERD2_PROCD==ca_unmatch[u].process
                        && response.datacal[n].SERD2_MC==ca_unmatch[u].mc
                        && response.datacal[n].SERD2_MCZ==ca_unmatch[u].mcz
                        && response.datacal[n].SERD2_LINENO==ca_unmatch[u].lineno 
                        )
                        {
                            ca_unmatch[u].itemcode = response.datacal[n].SERD2_ITMCD;                            
                            break;
                        }
                    }
                }                
                for(let u in ca_unmatch){                    
                    for(let n=0; n<ttlrows_cal; n++){                        
                        if(
                        response.datacal[n].SERD2_PROCD==ca_unmatch[u].process
                        && response.datacal[n].SERD2_MC==ca_unmatch[u].mc
                        && response.datacal[n].SERD2_MCZ==ca_unmatch[u].mcz
                        && response.datacal[n].SERD2_LINENO==ca_unmatch[u].lineno
                        )
                        {
                            let nob = {
                                        process: ca_unmatch[u].process
                                        ,mc: ca_unmatch[u].mc
                                        ,mcz: ca_unmatch[u].mcz
                                        ,lineno: ca_unmatch[u].lineno
                                        ,itemcode: response.datacal[n].SERD2_ITMCD
                                        ,fr: response.datacal[n].SERD2_FR
                                    };
                            ca_unmatch_2.push(nob);                            
                        }
                    }
                }                
                //show modified req. table
                for(let u in ca_unmatch){
                    for(let i = 0; i<mrows_req_length; i++ ){
                        if(ca_unmatch[u].process==tableku2_req.rows[i].cells[1].innerText
                        && ca_unmatch[u].mc==tableku2_req.rows[i].cells[3].innerText
                        && ca_unmatch[u].mcz==tableku2_req.rows[i].cells[4].innerText
                        && ca_unmatch[u].lineno==tableku2_req.rows[i].cells[0].innerText)
                        {
                            if(ca_unmatch[u].itemcode!=tableku2_req.rows[i].cells[6].innerText){
                                tableku2_req.rows[i].classList.remove('table-success');                               
                            }
                        }
                    }
                }

                //new marking logic
                // reset specific data display first, per mcz
                for(let u in ca_unmatch){
                    for(let i = 0; i<mrows_req_length; i++ ){
                        if(ca_unmatch[u].process==tableku2_req.rows[i].cells[1].innerText
                        && ca_unmatch[u].mc==tableku2_req.rows[i].cells[3].innerText
                        && ca_unmatch[u].mcz==tableku2_req.rows[i].cells[4].innerText
                        && ca_unmatch[u].lineno==tableku2_req.rows[i].cells[0].innerText)
                        {
                            tableku2_req.rows[i].classList.remove('table-success');
                        }
                    }
                }

                for(let u in ca_unmatch_2){
                    for(let i = 0; i<mrows_req_length; i++ ){
                        if(ca_unmatch_2[u].process==tableku2_req.rows[i].cells[1].innerText
                        && ca_unmatch_2[u].mc==tableku2_req.rows[i].cells[3].innerText
                        && ca_unmatch_2[u].mcz==tableku2_req.rows[i].cells[4].innerText
                        && ca_unmatch_2[u].itemcode.trim()==tableku2_req.rows[i].cells[6].innerText.trim()
                        && ca_unmatch_2[u].fr.trim()==tableku2_req.rows[i].cells[2].innerText.trim()
                        && ca_unmatch_2[u].lineno.trim()==tableku2_req.rows[i].cells[0].innerText.trim())
                        {
                            tableku2_req.rows[i].classList.add('table-success');                            
                        }
                    }
                }

                //sub checking
                for(let u in ca_unmatch_2){
                    for(let i = 0; i<mrows_req_length; i++ ){
                        if(ca_unmatch_2[u].process==tableku2_req.rows[i].cells[1].innerText
                        && ca_unmatch_2[u].mc==tableku2_req.rows[i].cells[3].innerText
                        && ca_unmatch_2[u].mcz==tableku2_req.rows[i].cells[4].innerText
                        && ca_unmatch_2[u].lineno==tableku2_req.rows[i].cells[0].innerText
                        )
                        {                            
                            for(let n =0 ; n<ttlrows_cal; n++){
                                let issubpriority = false;
                                for(let s=0; s<ttlrows_msp; s++ ){
                                    if(response.datamsp[s].MSPP_BOMPN==tableku2_req.rows[i].cells[6].innerText.trim() 
                                    && response.datamsp[s].MSPP_SUBPN==response.datacal[n].SERD2_ITMCD){
                                        issubpriority = true;
                                        break;
                                    }
                                }
                                if(ca_unmatch_2[u].process==response.datacal[n].SERD2_PROCD
                                && ca_unmatch_2[u].mc==response.datacal[n].SERD2_MC
                                && ca_unmatch_2[u].mcz==response.datacal[n].SERD2_MCZ
                                && ca_unmatch_2[u].lineno==response.datacal[n].SERD2_LINENO
                                && issubpriority){
                                    tableku2_req.rows[i].classList.add('table-success');
                                }
                            }
                            
                                                        
                        }
                    }
                }
                //n sub checking

                for(let i = 0; i<mrows_req_length; i++ ){                        
                    if(!tableku2_req.rows[i].classList.contains('table-success')){
                        tableku2_req.rows[i].cells[10].innerHTML = "<span class='fas fa-screwdriver text-warning'></span>";
                        tableku2_req.rows[i].cells[10].style.cssText = "cursor:pointer;text-align:center";
                        tableku2_req.rows[i].cells[10].title = "action is required";
                        let objk = {
                            docno: cimsvspsn_docno
                            ,line: tableku2_req.rows[i].cells[0].innerText
                            ,process: tableku2_req.rows[i].cells[1].innerText
                            ,mc: tableku2_req.rows[i].cells[3].innerText
                            ,mcz: tableku2_req.rows[i].cells[4].innerText
                            ,fr: tableku2_req.rows[i].cells[2].innerText
                            ,per: tableku2_req.rows[i].cells[5].innerText
                            ,mpart: tableku2_req.rows[i].cells[6].innerText
                        };
                        tableku2_req.rows[i].cells[10].onclick =  function(){cimsvspsn_show_possible_supply(objk)};
                    }
                }
                // end new marking logic                
                cimsvspsn_req_rows =  tabell_req.cloneNode(true);

                mydes_req.innerHTML='';
                mydes_req.appendChild(myfrag_req);                

                //Calculation result resume
                mydes = document.getElementById("cimsvspsn_caldiv_rm_resume");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("cimsvspsn_caltbl_rm_resume");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("cimsvspsn_caltbl_rm_resume");                
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML='';
                for(let c in ca_resumecal){
                    newrow = tableku2.insertRow(-1);
                    for(let i in ca_resumereq){
                        if(ca_resumereq[i].process.trim()==ca_resumecal[c].process.trim() 
                        && ca_resumereq[i].mc.trim()==ca_resumecal[c].mc.trim()
                        && ca_resumereq[i].mcz.trim()==ca_resumecal[c].mcz.trim()
                        && ca_resumereq[i].per==ca_resumecal[c].per
                        ){
                            newrow.classList.add("table-success");
                            break;
                        }
                    }
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(ca_resumecal[c].process);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(ca_resumecal[c].mc);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(ca_resumecal[c].mcz);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(ca_resumecal[c].per).format('0.00'));
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);


                //PSN
                mydes = document.getElementById("cimsvspsn_divdetailpsn");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("cimsvspsn_tbldetailpsn");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("cimsvspsn_tbldetailpsn");                
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML = '';
                let apsn = [];
                for(let i=0; i<ttlrows_psn; i++){
                    if(!apsn.includes(response.datapsn[i].PPSN2_PSNNO.trim())){
                        apsn.push(response.datapsn[i].PPSN2_PSNNO.trim());
                    }
                    newrow = tableku2.insertRow(-1);
                    for(let n=0; n< ttlrows_req; n++){                        
                        if(response.datareq[n].PIS3_LINENO.trim() == response.datapsn[i].PPSN2_LINENO.trim()
                        && response.datareq[n].PIS3_PROCD.trim() == response.datapsn[i].PPSN2_PROCD.trim()
                        && response.datareq[n].PIS3_FR.trim() == response.datapsn[i].PPSN2_FR.trim()
                        && response.datareq[n].PIS3_MC.trim() == response.datapsn[i].PPSN2_MC.trim()
                        && response.datareq[n].PIS3_MCZ.trim() == response.datapsn[i].PPSN2_MCZ.trim()
                        ){
                            newrow.classList.add("table-success");
                            break;
                        }
                    }
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_DOCNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_PSNNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_LINENO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_PROCD);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_FR);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_ITMCAT);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_MC);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(7);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_MCZ);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(8);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_MSFLG);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(9);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_SUBPN);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(10);
                    newText = document.createTextNode(response.datapsn[i].MITM_SPTNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(11);
                    newText = document.createTextNode(response.datapsn[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(12);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(response.datapsn[i].PPSN2_REQQT).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(13);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(response.datapsn[i].PPSN2_ACTQT).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(14);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(response.datapsn[i].PPSN2_RTNQT).format(','));
                    newcell.appendChild(newText);
                }
                $('#cimsvspsn_psn_list').tagbox('setValues', apsn);
                mydes.innerHTML='';
                mydes.appendChild(myfrag);           
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
        $('#cimsvspsn_w_jobreq').window('open');
        $('#cimsvspsn_w_psnjob').window('open');
        $('#cimsvspsn_w_calculation').window('open');
    }
    $('#cimsvspsn_psn_list').tagbox({
        label: 'PSN No',        
        onRemoveTag :function(e) {
            e.preventDefault();           
        }
    });

    function cimsvspsn_e_addto_cal(){
        let datanya = cimsvspsn_DTABLE_psn.rows( { selected: true } ).nodes()[0];        
        if (typeof datanya == 'undefined'){            
            alert("there is no selected data");
        } else {
            let ttluse = numeral(document.getElementById('cimsvspsn_cal_ttl_per').innerText).value();
            let idsample = document.getElementById("cimsvspsn_cal_ID").value;
            let job = document.getElementById("cimsvspsn_req_job").value;
            let line = document.getElementById("cimsvspsn_txt_line").value;
            let mc = document.getElementById("cimsvspsn_txt_mc").value;
            let mcz = document.getElementById("cimsvspsn_txt_mcz").value;
            let per = document.getElementById("cimsvspsn_txt_per").value;
            let psn = datanya.cells[1].innerText.trim();
            let itmcat = datanya.cells[5].innerText.trim();
            let fr = datanya.cells[4].innerText.trim();
            let itemcd = datanya.cells[8].innerText.trim();
            let actqty = numeral(datanya.cells[11].innerText).value();
            let strdis = "Add below data <br>"+
                "<b>PSN</b>: "+psn+
                "<br><b>Process</b>: "+cimsvspsn_procd+
                "<br><b>Line</b>: "+line+
                "<br><b>Category</b>: "+itmcat+
                "<br><b>F/R</b>: "+fr+
                "<br><b>Job</b>: "+job+
                "<br><b>PER</b>: "+per+
                "<br><b>MC</b>: "+mc+
                "<br><b>MCZ</b>: "+mcz+
                "<br><b>Item Code</b>: "+itemcd+
                "<br>=======TO======="+
                "<br><b>ID</b>: "+idsample+" alike";

            $.messager.confirm('Decide', strdis, function(r){
                if (r){
                    document.getElementById('cimsvspsn_add').disabled=true;
                    $("#cimsvspsn_alert").html('<i span="badge bg-info">Please wait</i>');
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('SER/add_rm_to_boxID')?>",
                        data: {inpsn: psn, inprocd: cimsvspsn_procd, inline: line, initmcat: itmcat
                        ,infr: fr, injob: job, inactqt: actqty, inper: per, inmc: mc, inmcz: mcz
                        ,initmcd: itemcd, inttluse: ttluse },
                        dataType: "json",
                        success: function (response) {   
                            document.getElementById('cimsvspsn_add').disabled=false;
                            if(response.status[0].cd=='1'){
                                $("#cimsvspsn_alert").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                                response.status[0].msg+
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'+                                       
                                '</div>');
                            } else {
                                $("#cimsvspsn_alert").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                                response.status[0].msg+
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">'+                                        
                                    '</button>'+
                                '</div>');
                            }
                        }, error(xhr, xopt, xthrow){
                            alertify.error(xthrow);
                            document.getElementById('cimsvspsn_add').disabled=false;
                        }
                    });
                }
            });            
        }
    }

    function cimsvspsn_req_btn_discrep_e_click(){
        $("#cimsvspsn_reqtbl_rm tbody").empty();
        let tabell = document.getElementById("cimsvspsn_reqtbl_rm");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let obody = cimsvspsn_req_rows.getElementsByTagName("tbody")[0];
        let obody_row = obody.getElementsByTagName("tr");
        let obody_row_length = obody_row.length;
        let newrow, newcell, newText;
        for(let i=0; i<obody_row_length; i++){
            if(!obody.rows[i].classList.contains("table-success")){
                newrow = tableku2.insertRow(-1);
                newrow.innerHTML = obody.rows[i].innerHTML;
                let objk = {
                    docno: cimsvspsn_docno
                    ,line: newrow.cells[0].innerText
                    ,process: newrow.cells[1].innerText
                    ,mc: newrow.cells[3].innerText
                    ,mcz: newrow.cells[4].innerText
                    ,fr: newrow.cells[2].innerText
                    ,per: newrow.cells[5].innerText
                    ,mpart: newrow.cells[6].innerText
                };
                newrow.cells[10].onclick = function(){cimsvspsn_show_possible_supply(objk)};
            }
        }        
    }

    function cimsvspsn_req_btn_all_e_click(){
        let tabell = document.getElementById("cimsvspsn_reqtbl_rm");
        let obody = cimsvspsn_req_rows.getElementsByTagName("tbody")[0];
        tabell.getElementsByTagName("tbody")[0].innerHTML = obody.innerHTML;
    }

    function cimsvspsn_show_possible_supply(pdata){ 
        document.getElementById("cimsvspsn_txt_line").value = pdata.line;
        document.getElementById("cimsvspsn_txt_mc").value = pdata.mc;
        document.getElementById("cimsvspsn_txt_mcz").value = pdata.mcz;
        document.getElementById("cimsvspsn_txt_per").value = pdata.per;
        cimsvspsn_procd = pdata.process;
        let assycode = document.getElementById('cimsvspsn_req_itmcd').value;
        let mjob = document.getElementById('cimsvspsn_req_job').value;
        $('#cimsvspsn_w_psnfilter').window('open');
        cimsvspsn_DTABLE_psn =  $('#cimsvspsn_tblpsnfilter').DataTable({
            select: true,
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
            ajax: {
                url : '<?=base_url("SPL/get_psn_process")?>',
                type: 'get',
                data: {indocno: pdata.docno, inprc: pdata.process, infr: pdata.fr
                , inmpart: pdata.mpart, inassy: assycode, injob: mjob}
            },
            columns:[
                { "data": 'PPSN2_DOCNO'},
                { "data": 'PPSN2_PSNNO'},
                { "data": 'PPSN2_PROCD'},
                { "data": 'PPSN2_LINENO'},
                { "data": 'PPSN2_FR'},
                { "data": 'PPSN2_ITMCAT'},
                { "data": 'PPSN2_MC'},
                { "data": 'PPSN2_MCZ'},
                { "data": 'PPSN2_SUBPN'},
                { "data": 'MITM_SPTNO'},
                { "data": 'MITM_ITMD1'},
                { "data": 'PPSN2_ACTQT', render: $.fn.dataTable.render.number(',', '.', 0,'')}
            ], 
            columnDefs: [
                {
                    targets: 11,
                    className: 'text-end'
                }
            ]
        });
    }

    function cimsvspsn_e_flagok(){
        if(confirm("Are you sure ?")){            
            let sjob = document.getElementById('cimsvspsn_req_job').value;
            let ttluse = numeral(document.getElementById('cimsvspsn_cal_ttl_per').innerText).value();
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/flag_rmuse_ok')?>",
                data: {injob: sjob, inttluse: ttluse},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==1){                        
                        $("#cimsvspsn_alert").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                        response.status[0].msg+
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                '<span aria-hidden="true">&times;</span>'+
                            '</button>'+
                        '</div>');
                    } else {
                        $("#cimsvspsn_alert").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                        response.status[0].msg+
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                '<span aria-hidden="true">&times;</span>'+
                            '</button>'+
                        '</div>');
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    document.getElementById('cimsvspsn_btn_filter').disabled = false;
                }
            });
        }
    }
</script>