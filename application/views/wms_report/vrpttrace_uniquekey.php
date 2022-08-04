<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                   
                    <input type="text" class="form-control font-monospace" id="vtraceuniq_reffno" onkeypress="vtraceuniq_reffno_e_keypress(event)" placeholder="uniquekey / reff no" style="text-align:center">                    
                    <button title="Search" class="btn btn-primary" id="vtraceuniq_btn_gen" onclick="vtraceuniq_btn_gen_e_click()"> <i class="fas fa-search"></i> </button>                    
                </div>
            </div>
        </div>             
    
        <div class="row">                        
            <div class="col-md-12 mb-1 text-end">
                <span id="vtraceuniq_status" class="badge bg-info"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="vtraceuniq_divku">
                    <table id="vtraceuniq_tbl" class="table table-striped table-bordered table-sm table-hover caption-top" style="font-size:85%">
                        <caption>Master Label</caption>
                        <thead class="table-light">
                            <tr>
                                <th  class="align-middle">ID</th>
                                <th  class="align-middle">Parent ID</th>
                                <th  class="align-middle">Assy Code</th>
                                <th  class="align-middle">Job Number</th>
                                <th  class="text-end">Label Qty</th>
                                <th  class="text-end">LotQty</th>
                                <th  ></th>
                                <th  ></th>
                            </tr>                           
                        </thead>
                        <tbody>                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">                        
            <div class="col-md-12 mb-1 text-end">
                <span id="vtraceuniq_status_child" class="badge bg-info"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="vtraceuniq_divku_child">
                    <table id="vtraceuniq_tbl_child" class="table table-striped table-bordered table-sm table-hover caption-top" style="font-size:85%">
                        <caption>Split and Convert History</caption>
                        <thead class="table-light">                           
                            <tr>
                                <th  class="align-middle">ID</th>
                                <th  class="text-end">Label Qty</th>
                                <th  class="text-end">LotQty</th>
                                <th  ></th>
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
                <div class="table-responsive" id="vtraceuniq_divku_relable">
                    <table id="vtraceuniq_tbl_relable" class="table table-striped table-bordered table-sm table-hover caption-top" style="font-size:85%">
                        <caption>Relabel History</caption>
                        <thead class="table-light">                            
                            <tr>
                                <th class="align-middle">Old ID</th>
                                <th class="align-middle">New ID</th>
                                <th class="text-end">Label Qty</th>
                                <th class="">Relabeled By</th>
                                <th class="">Time</th>
                                <th  ></th>
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
                <div class="table-responsive" id="vtraceuniq_divku_ml">
                    <table id="vtraceuniq_tbl_ml" class="table table-striped table-bordered table-sm table-hover caption-top" style="font-size:85%">
                        <caption>Multilayer</caption>
                        <thead class="table-light">
                            <tr>                                
                                <th class="align-middle">Component ID</th>
                                <th class="align-middle">Component Job Number</th>
                                <th class="text-end">Label Qty</th>
                                <th class="">PIC</th>
                                <th class="">Time</th>
                                <th  ></th>
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
                <div class="table-responsive" id="vtraceuniq_divku_del">
                    <table id="vtraceuniq_tbl_del" class="table table-striped table-bordered table-sm table-hover caption-top" style="font-size:85%">
                        <caption>Delete History</caption>
                        <thead class="table-light">
                            <tr>                               
                                <th rowspan="2" class="align-middle">FORM</th>
                                <th rowspan="2" class="align-middle">Warehouse</th>
                                <th rowspan="2" class="align-middle">Location</th>
                                <th  rowspan="2" class="align-middle">Document</th>
                                <th  rowspan="2" class="align-middle text-end">Qty</th>                                                                                              
                                <th  colspan="2" class="align-middle text-center">Time</th>
                                <th  rowspan="2" class="align-middle">PIC</th>
                            </tr>
                            <tr>
                                <th  class="align-middle text-center">Transaction</th>
                                <th  class="align-middle text-center">Deleted</th>
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
                <div class="table-responsive" id="vtraceuniq_divku_combine">
                    <table id="vtraceuniq_tbl_combine" class="table table-bordered table-sm table-hover caption-top" style="font-size:85%">
                        <caption>Combined Job</caption>
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Child ID</th>
                                <th class="align-middle">Job</th>
                                <th class="align-middle text-end">Qty</th>
                                <th class="align-middle text-center">Time</th>
                                <th class="align-middle">PIC</th>
                                <th class="align-middle">RM</th>
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
                <div class="table-responsive" id="vtraceuniq_divku_jm">
                    <table id="vtraceuniq_tbl_jm" class="table table-striped table-bordered table-sm table-hover caption-top" style="font-size:85%">
                        <caption>JM Label</caption>
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">JM</th>
                                <th class="align-middle">Sq_no</th>
                                <th class="align-middle text-center">Time</th>
                                <th class="align-middle">PIC</th>
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
<div class="modal fade" id="vtraceuniq_modhistory">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Transaction History</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <span id="vtraceuniq_history_status" class="badge bg-info"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="vtraceuniq_divhistory">
                        <table id="vtraceuniq_tblhistory" class="table table-hover table-sm table-bordered" style="font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th>Assy Code</th>                                    
                                    <th>Event</th>
                                    <th>Date</th>
                                    <th>Document</th>
                                    <th>QTY</th>
                                    <th>Warehouse</th>
                                    <th>Location</th>
                                    <th>Time</th>
                                    <th>Remark</th>                                
                                    <th>PIC</th>                                
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
    function vtraceuniq_reffno_e_keypress(e){
        if (e.which==13){
            const reffno =  document.getElementById('vtraceuniq_reffno').value.trim();
            $("#vtraceuniq_tbl tbody").empty();
            $("#vtraceuniq_tbl_child tbody").empty();
            $("#vtraceuniq_tbl_relable tbody").empty();
            $("#vtraceuniq_tbl_ml tbody").empty();
            $("#vtraceuniq_tbl_del tbody").empty();
            vtraceuniq_find(reffno);
        }
    }    

    function vtraceuniq_btn_gen_e_click(){
        const reffno =  document.getElementById('vtraceuniq_reffno').value.trim();
        $("#vtraceuniq_tbl tbody").empty();
        $("#vtraceuniq_tbl_child tbody").empty();
        $("#vtraceuniq_tbl_relable tbody").empty();
        $("#vtraceuniq_tbl_ml tbody").empty();
        $("#vtraceuniq_tbl_del tbody").empty();
        vtraceuniq_find(reffno);
    }

    function vtraceuniq_e_showhistory(preffno){
        $("#vtraceuniq_modhistory").modal('show');
        $('#vtraceuniq_tblhistory tbody').empty();
        document.getElementById('vtraceuniq_history_status').innerText = "Please wait....";
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/traceid')?>",
            data: {inid: preffno},
            dataType: "json",
            success: function (response) {
                document.getElementById('vtraceuniq_history_status').innerText = "";
                if(response.status[0].cd!='0'){
                    let ttlrows = response.data.length;
                    let mydes = document.getElementById("vtraceuniq_divhistory");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("vtraceuniq_tblhistory");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);                    
                    let tabell = myfrag.getElementById("vtraceuniq_tblhistory");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';                                 
                    for (let i = 0; i<ttlrows; i++){                        
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0); 
                        newText = document.createTextNode(response.data[i].ITH_ITMCD);
                        newcell.appendChild(newText);         
                        newcell = newrow.insertCell(1); 
                        newText = document.createTextNode(response.data[i].ITH_FORM);
                        newcell.appendChild(newText);         
                        newcell = newrow.insertCell(2); 
                        newText = document.createTextNode(response.data[i].ITH_DATE);
                        newcell.appendChild(newText);         
                        newcell = newrow.insertCell(3); 
                        newText = document.createTextNode(response.data[i].ITH_DOC);
                        newcell.appendChild(newText);         
                        newcell = newrow.insertCell(4); 
                        newcell.classList.add('text-right');
                        newText = document.createTextNode(numeral(response.data[i].ITH_QTY).format(','));
                        newcell.appendChild(newText);         
                        newcell = newrow.insertCell(5); 
                        newText = document.createTextNode(response.data[i].ITH_WH);
                        newcell.appendChild(newText);         
                        newcell = newrow.insertCell(6); 
                        newText = document.createTextNode(response.data[i].ITH_LOC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(7); 
                        newText = document.createTextNode(response.data[i].ITH_LUPDT);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(8); 
                        newText = document.createTextNode(response.data[i].ITH_REMARK);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(9); 
                        newText = document.createTextNode(response.data[i].PIC);
                        newcell.appendChild(newText);
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                } else {
                    alertify.message(response.status[0].msg);
                }
            }, error : function(xhr, xopt, xthrow){
                document.getElementById('vtraceuniq_history_status').innerText = "";
                alertify.error(xthrow);
            }
        })
    }

    function vtraceuniq_find(preffno){
        if(preffno.trim()==''){
            return;
        }
        document.getElementById('vtraceuniq_status').innerText = "Please wait...";
        document.getElementById('vtraceuniq_reffno').value='';
        $.ajax({
            type: "get",
            url: "<?=base_url('SER/traceid')?>",
            data: {inid: preffno},
            dataType: "json",
            success: function (response) {
                document.getElementById('vtraceuniq_status').innerText = "";
                if(response.status[0].cd!='0'){
                    let tabell = document.getElementById("vtraceuniq_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let ttlrows= tableku2.getElementsByTagName('tr').length;
                    let newrow, newcell, newText;
                    let isfound=false;
                    for(let i=0;i<ttlrows;i++){
                        if(tableku2.rows[i].cells[0].innerText==response.data[0].SER_ID){
                            isfound=true;
                            break;
                        }
                    }
                    if(!isfound){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.classList.add('font-monospace')
                        newcell.style.cssText= "cursor:pointer";
                        newcell.onclick= function(){vtraceuniq_get_childs(response.data[0].SER_ID)};
                        newText = document.createTextNode(response.data[0].SER_ID);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newcell.classList.add('font-monospace')
                        newcell.style.cssText= "cursor:pointer";
                        newcell.onclick= function(){vtraceuniq_get_childs(response.data[0].SER_REFNO)};
                        newText = document.createTextNode(response.data[0].SER_REFNO);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newcell.classList.add('font-monospace')                        
                        newText = document.createTextNode(response.data[0].SER_ITMID);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newcell.classList.add('font-monospace')
                        newText = document.createTextNode(response.data[0].SER_DOC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newcell.style.cssText='text-align:right';
                        newText = document.createTextNode(numeral(response.data[0].SER_QTY).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(5);
                        newcell.style.cssText='text-align:right';
                        newText = document.createTextNode(response.data[0].SER_QTYLOT);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(6);                    
                        if(response.data[0].SER_ID==response.data[0].SER_REFNO){
                            newcell.style.cssText='text-align:center';
                            newText = document.createTextNode('');                         
                        } else {
                            newcell.style.cssText='text-align:center;cursor:pointer;';
                            newcell.classList.add('bg-primary');
                            newcell.classList.add('text-white');
                            newText = document.createTextNode('get parent'); 
                            newcell.onclick= function(){ vtraceuniq_find(response.data[0].SER_REFNO) };
                        }
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(7);
                        newcell.style.cssText='text-align:center;cursor:pointer;';
                        newcell.classList.add('bg-primary');
                        newcell.classList.add('text-white');                        
                        newcell.innerHTML="<i class='fas fa-history'></i>";
                        newcell.title="get transaction history";
                        newcell.onclick = function(){vtraceuniq_e_showhistory(response.data[0].SER_ID)};
                    }

                    ttlrows = response.jm.length;
                    let mydes = document.getElementById("vtraceuniq_divku_jm");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("vtraceuniq_tbl_jm");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);                    
                    tabell = myfrag.getElementById("vtraceuniq_tbl_jm");                    
                    tableku2 = tabell.getElementsByTagName("tbody")[0]
                    tableku2.innerHTML=''
                    for(let i=0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.jm[i].JM_No
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.jm[i].Sq_no
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.jm[i].cdate
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = response.jm[i].FULLNM
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)

                    ttlrows = response.combine.length;
                    mydes = document.getElementById("vtraceuniq_divku_combine");
                    myfrag = document.createDocumentFragment();
                    mtabel = document.getElementById("vtraceuniq_tbl_combine");
                    cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);                    
                    tabell = myfrag.getElementById("vtraceuniq_tbl_combine");                    
                    tableku2 = tabell.getElementsByTagName("tbody")[0]
                    tableku2.innerHTML=''
                    for(let i=0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.combine[i].SERC_COMID
                        if(response.combine[i].SERC_COMJOB) {
                            if(response.combine[i].SERC_COMJOB.toUpperCase().includes('-C') && !response.combine[i].SERD2_SER) {
                                newcell.style.cssText = 'cursor:pointer'
                                newcell.title = `this Child ID need to be calculate`                                    
                                newcell.onclick = (e) => {
                                    if(confirm('Are you sure ?')) {
                                        tableku2.rows[e.target.parentNode.rowIndex-1].cells[5].innerText = `Please wait`
                                        $.ajax({
                                            type: "POST",
                                            url: "<?=base_url('SER/calculateCombinedJob')?>",
                                            data: {inunique: response.combine[i].SERC_COMID,
                                                inunique_qty: response.combine[i].SERC_COMQTY*1,  
                                                inunique_job: response.combine[i].SERC_COMJOB
                                            },
                                            dataType: "json",
                                            success: function (response) {
                                                alertify.message(response.status.msg)
                                                if(response.status.cd===1) {
                                                    e.target.title = ''
                                                    tableku2.rows[e.target.parentNode.rowIndex-1].cells[5].innerText = response.status.msgreff
                                                }
                                            }, error : function(xhr, xopt, xthrow){
                                                tableku2.rows[e.target.parentNode.rowIndex-1].cells[5].innerText = `Not available`
                                                alertify.error(xthrow);
                                            }
                                        })
                                    }
                                }
                            }
                        }
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.combine[i].SERC_COMJOB
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.combine[i].SERC_COMQTY
                        newcell = newrow.insertCell(3)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = response.combine[i].SERC_LUPDT
                        newcell = newrow.insertCell(4)
                        newcell.innerHTML = response.combine[i].FULLNM
                        newcell = newrow.insertCell(5)
                        newcell.innerHTML = response.combine[i].SERD2_SER ? 'Available' : 'Not available'
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                    
                } else {
                    let isfound = false;
                    const rowdellength = response.data_delete.length
                    if(response.data_relable.length>0){
                        isfound = true;
                        let tabell = document.getElementById("vtraceuniq_tbl_relable");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let ttlrows = tableku2.getElementsByTagName('tr').length;
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0)
                        newcell.classList.add('font-monospace')
                        newcell.innerHTML = response.data_relable[0].SER_OLDID;
                        newcell = newrow.insertCell(1)
                        newcell.classList.add('font-monospace','bg-success','text-light')
                        newcell.innerHTML = response.data_relable[0].SER_NEWID
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data_relable[0].SER_QTY
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = response.data_relable[0].PIC
                        newcell = newrow.insertCell(4)
                        newcell.innerHTML = response.data_relable[0].LOGSER_DT
                        newcell = newrow.insertCell(5)
                        newcell.style.cssText='text-align:center;cursor:pointer;';
                        newcell.classList.add('bg-primary','text-white')
                        newcell.innerHTML="<i class='fas fa-history'></i>";
                        newcell.title="get transaction history";
                        newcell.onclick = () => {vtraceuniq_e_showhistory(response.data_relable[0].SER_NEWID)}                        
                    } 
                    if(rowdellength >0){
                        isfound = true
                        let mydes = document.getElementById("vtraceuniq_divku_del");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("vtraceuniq_tbl_del");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        tabell = myfrag.getElementById("vtraceuniq_tbl_del");
                        tableku2 = tabell.getElementsByTagName("tbody")[0];                        
                        tableku2.innerHTML='';
                        for (let i = 0; i<rowdellength; i++){                    
                            newrow = tableku2.insertRow(-1);                            
                            newcell = newrow.insertCell(0);
                            newText = document.createTextNode(response.data_delete[i].ITH_FORM);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.data_delete[i].ITH_WH);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(response.data_delete[i].ITH_LOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);
                            newText = document.createTextNode(response.data_delete[i].ITH_DOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(4);
                            newText = document.createTextNode(numeral(response.data_delete[i].ITH_QTY).format(','));
                            newcell.style.cssText = 'text-align: right';
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(5);
                            newcell.classList.add("text-center");
                            newText = document.createTextNode(response.data_delete[i].ITH_LUPDT);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(6);
                            newcell.classList.add("text-center");
                            newText = document.createTextNode(response.data_delete[i].ITH_LUPDTBIN);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(7);
                            newText = document.createTextNode(response.data_delete[i].PIC);
                            newcell.appendChild(newText);
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    }
                    if(!isfound){
                        alertify.message(response.status[0].msg);
                    }

                }
            }, error : function(xhr, xopt, xthrow){
                document.getElementById('vtraceuniq_status').innerText = "";
                alertify.error(xthrow);
            }
        });
    }

    function vtraceuniq_get_childs(pid){
        document.getElementById('vtraceuniq_status_child').innerText = "Please wait...";
        $("#vtraceuniq_tbl_child tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('SER/getchilds')?>",
            data: {inid: pid},
            dataType: "json",
            success: function (response) {
                document.getElementById('vtraceuniq_status_child').innerText = "";
                if(response.status[0].cd!='0'){
                    let mydes = document.getElementById("vtraceuniq_divku_child");
                    let myfrag = document.createDocumentFragment();                  
                    let mtabel = document.getElementById("vtraceuniq_tbl_child");  
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("vtraceuniq_tbl_child");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML='';
                    let ttlrows= response.data.length;
                    let newrow, newcell, newText;
                    for(let i=0;i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);            
                        newcell.classList.add('font-monospace')
                        newText = document.createTextNode(response.data[i].SER_ID);
                        newcell.appendChild(newText);                        
                        newcell = newrow.insertCell(1);
                        newcell.style.cssText='text-align:right';
                        newText = document.createTextNode(numeral(response.data[i].SER_QTY).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newcell.style.cssText='text-align:right';
                        newText = document.createTextNode(response.data[i].SER_QTYLOT);
                        newcell.appendChild(newText);                                               
                        newcell = newrow.insertCell(3);
                        newcell.style.cssText='text-align:center;cursor:pointer;';
                        newcell.classList.add('bg-primary');
                        newcell.classList.add('text-white');
                        newText = document.createTextNode('get transaction');
                        newcell.innerHTML="<i class='fas fa-history'></i>";
                        newcell.title="get transaction history";
                        newcell.onclick = function(){vtraceuniq_e_showhistory(response.data[i].SER_ID)};                    
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);                   
                } else {
                     //multi layer
                    $("#vtraceuniq_tbl_ml tbody").empty();
                    tabell = document.getElementById("vtraceuniq_tbl_ml");
                    tableku2 = tabell.getElementsByTagName("tbody")[0];
                    ttlrows = response.data_multi.length;
                    if(ttlrows>0){
                        for(let i=0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1);                            
                            newcell = newrow.insertCell(0)
                            newcell.classList.add('font-monospace')
                            newcell.innerHTML = response.data_multi[i].SERML_COMID
                            newcell = newrow.insertCell(1)
                            newcell.classList.add('font-monospace')
                            newcell.innerHTML = response.data_multi[i].COMPJOB
                            newcell = newrow.insertCell(2)
                            newcell.classList.add('font-monospace','text-end')
                            newcell.innerHTML = numeral(response.data_multi[i].COMPQTY).format(',')
                            newcell = newrow.insertCell(3)
                            newcell.innerHTML = response.data_multi[i].PIC
                            newcell = newrow.insertCell(4)
                            newcell.innerHTML = response.data_multi[i].SERML_LUPDT
                            newcell = newrow.insertCell(5)
                            newcell.style.cssText='text-align:center;cursor:pointer;';
                            newcell.classList.add('bg-primary');
                            newcell.classList.add('text-white');                        
                            newcell.innerHTML="<i class='fas fa-history'></i>";
                            newcell.title="get transaction history";
                            newcell.onclick = function(){vtraceuniq_e_showhistory(response.data_multi[i].SERML_COMID)};
                        }
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                }
            }, error : function(xhr, xopt, xthrow){                
                alertify.error(xthrow);
            }
        });
    }
</script>