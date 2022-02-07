<div style="padding: 5px">
	<div class="container-xxl">            
        <div class="row">   
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Reff No</span>                    
                    <input type="text" class="form-control" id="deletelabel_oldreff" onkeypress="deletelabel_oldreff_keypress(event)" required>                   
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Job Number</span>                    
                    <input type="text" class="form-control" id="deletelabel_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="deletelabel_olditemcd" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Qty</span>                    
                    <input type="text" class="form-control" id="deletelabel_oldqty" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h3>Transaction History</h3>
                <div class="table-responsive" id="deletelabel_divku">
                    <table id="deletelabel_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr>                               
                                <th  class="align-middle">FORM</th>
                                <th  class="align-middle">Warehouse</th>
                                <th  class="align-middle">Location</th>
                                <th  class="align-middle">Document</th>
                                <th  class="text-right">Qty</th>                                                              
                                <th  class="align-middle">Time</th>
                                <th  class="align-middle">PIC</th>
                            </tr>                           
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">   
            <div class="col-md-12 mb-1 pr-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="deletelabel_btnnew"><i class="fas fa-file"></i></button>
                    <button class="btn btn-danger" id="deletelabel_btndel"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h3>Deleted Label</h3>
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Search by</span>                    
                    <select class="form-select" id="deletelabel_sel" onchange="deletelabel_sel_change()">
                        <option value="date">Date</option>
                        <option value="job">Job Number</option>
                        <option value="reff">Reff Number</option>
                    </select>
                    <input type="text" class="form-control" id="deletelabel_sel_txtsearch" onkeypress="deletelabel_sel_txtsearch_e_keypress(event)">
                    <button class="btn btn-primary" id="deletelabel_btnsearch" onclick="deletelabel_e_searchbin()"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">                                
                <div class="table-responsive" id="deletelabelbin_divku">
                    <table id="deletelabelbin_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
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
    </div>
</div>
<script>
    function deletelabel_sel_change(){
        document.getElementById('deletelabel_sel_txtsearch').focus();
    }
    function deletelabel_e_searchbin(){
        let searchBy = document.getElementById('deletelabel_sel').value;
        let searchVal = document.getElementById('deletelabel_sel_txtsearch').value;
        if(searchVal.trim().length<3){
            alertify.message("at least 3 characters required");
            document.getElementById('deletelabel_sel_txtsearch').focus();
            return;
        }
        document.getElementById('deletelabel_btnsearch').disabled = true;
        $("#deletelabelbin_tbl tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/searchbin')?>",
            data: {insearch_by: searchBy, insearch_val : searchVal},
            dataType: "json",
            success: function (response) {
                document.getElementById('deletelabel_btnsearch').disabled = false;
                if(response.status[0].cd=='1'){                   
                    let ttlrows = response.tx.length;
                    let mydes = document.getElementById("deletelabelbin_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("deletelabelbin_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("deletelabelbin_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){                    
                        newrow = tableku2.insertRow(-1);                            
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response.tx[i].ITH_FORM);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.tx[i].ITH_WH);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.tx[i].ITH_LOC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.tx[i].ITH_DOC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(numeral(response.tx[i].ITH_QTY).format(','));
                        newcell.style.cssText = 'text-align: right';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(5);
                        newcell.classList.add("text-center");
                        newText = document.createTextNode(response.tx[i].ITH_LUPDT);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(6);
                        newcell.classList.add("text-center");
                        newText = document.createTextNode(response.tx[i].ITH_LUPDTBIN);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(7);
                        newText = document.createTextNode(response.tx[i].PIC);
                        newcell.appendChild(newText);
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                } else {
                    alertify.message(response.status[0].msg);                    
                }
                
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('deletelabel_btnsearch').disabled = false;
            }
        });
    }
    function deletelabel_sel_txtsearch_e_keypress(e){
        if(e.which==13){
            deletelabel_e_searchbin();
        }
    }
    $("#deletelabel_btndel").click(function (e) { 
        let mreff = document.getElementById('deletelabel_oldreff').value;
        if(mreff.trim()==''){
            alertify.message('Please entry Reff No');
            document.getElementById('deletelabel_oldreff').focus();
            return;
        }
        if(!document.getElementById('deletelabel_oldreff').readOnly){
            document.getElementById('deletelabel_oldreff').focus();
            alertify.message('Please press enter to confirm');
            return;
        }
        let konfrm = confirm('Are You sure ?');
        if(!konfrm){
            alertify.message('You are not sure');
            return;
        }
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/deletelabel1')?>",
            data: {inid: mreff},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    alertify.success(response.status[0].msg);
                    document.getElementById('deletelabel_oldreff').value='';
                } else {
                    alertify.message(response.status[0].msg);
                }
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#deletelabel_btnnew").click(function (e) { 
        $('#deletelabel_tbl tbody').empty();
        document.getElementById('deletelabel_oldreff').value='';
        document.getElementById('deletelabel_oldjob').value='';
        document.getElementById('deletelabel_olditemcd').value='';
        document.getElementById('deletelabel_oldqty').value='';
        document.getElementById('deletelabel_oldreff').readOnly=false;
        document.getElementById('deletelabel_oldreff').focus();
    });
    function deletelabel_oldreff_keypress(e){
        let mreff = document.getElementById('deletelabel_oldreff').value;
        if(mreff.trim()==''){
            alertify.message('there is no empty reff number');
            return;
        }
        if(e.which==13){
            $.ajax({
                type: "GET",
                url: "<?=base_url('SER/getproperties_n_tx')?>",
                data: {inid: mreff },
                dataType: "JSON",
                success: function (response) {
                    if(response.status[0].cd=='1'){
                        document.getElementById('deletelabel_oldreff').readOnly=true;
                        document.getElementById('deletelabel_oldjob').value=response.data[0].SER_DOC;
                        document.getElementById('deletelabel_olditemcd').value=response.data[0].SER_ITMID.trim();
                        document.getElementById('deletelabel_oldqty').value= numeral(response.data[0].SER_QTY).format(',');
                        ///load tx history
                        let ttlrows = response.tx.length;
                        let mydes = document.getElementById("deletelabel_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("deletelabel_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("deletelabel_tbl");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        for (let i = 0; i<ttlrows; i++){                    
                            newrow = tableku2.insertRow(-1);                            
                            newcell = newrow.insertCell(0);
                            newText = document.createTextNode(response.tx[i].ITH_FORM);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.tx[i].ITH_WH);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(response.tx[i].ITH_LOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);
                            newText = document.createTextNode(response.tx[i].ITH_DOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(4);
                            newText = document.createTextNode(numeral(response.tx[i].ITH_QTY).format(','));
                            newcell.style.cssText = 'text-align: right';
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(5);
                            newText = document.createTextNode(response.tx[i].ITH_LUPDT);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(6);
                            newText = document.createTextNode(response.tx[i].PIC);
                            newcell.appendChild(newText);
                        }
                        if(ttlrows==0){
                            newrow = tableku2.insertRow(-1);                            
                            newcell = newrow.insertCell(0);
                            newText = document.createTextNode('There is no transaction');
                            newcell.colSpan="7";    
                            newcell.appendChild(newText);

                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    } else {
                        alertify.message(response.status[0].msg);
                        document.getElementById('deletelabel_oldreff').value='';
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }
</script>