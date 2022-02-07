<div style="padding: 5px">
	<div class="container-fluid">  
        <div class="row">
            <div class="col-md-3 mb-1">
                <h2 ><span class="badge bg-info">1 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">From</span></h2>
            </div>            
        </div>      
        <div class="row">   
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Old Reff No</span>                    
                    <input type="text" class="form-control" id="relable_export_oldreff" required>                    
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Job Number</span>                    
                    <input type="text" class="form-control" id="relable_export_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="relable_export_olditemcd" required readonly>
                </div>
            </div>
        </div>
        <div class="row">   
            <div class="col-md-6 mb-1 pr-1">                
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Lot Number</span>                    
                    <input type="text" class="form-control" id="relable_export_lotno" required readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Qty</span>                    
                    <input type="text" class="form-control" id="relable_export_oldqty" required readonly>
                </div>
            </div>            
        </div>        
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h3>Transaction History</h3>
                <div class="table-responsive" id="relable_export_divku">
                    <table id="relable_export_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
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
            <div class="col-md-3 mb-1">
                <h2 ><span class="badge bg-info">2 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">To</span></h2>
            </div>           
        </div>      
        <div id="relable_export_div_rnm">
            <div class="row">
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Item Code</span>
                        <input type="text" class="form-control" id="relable_export_newitem" onkeypress="relable_export_newitem_e_keypress(event)" required> 
                    </div>
                </div>
                                             
                <div class="col-md-4 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Lot Number</span>                        
                        <input type="text" class="form-control" id="relable_export_newlot" required onkeypress="relable_export_newlot_e_keypress(event)">                        
                    </div>
                </div>
                
                <div class="col-md-2 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Qty</span>                        
                        <input type="text" class="form-control" id="relable_export_newqty" required onkeypress="relable_export_newqty_e_keypress(event)">
                    </div>
                </div>
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Reff No</span>                        
                        <input type="text" class="form-control" id="relable_export_newreff" required>                        
                    </div>
                </div>
            </div>
        </div>
       
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="relable_export_btn_new"><i class="fas fa-file"></i></button>
                    <button title="Save" type="button" class="btn btn-outline-primary" id="relable_export_btn_save"><i class="fas fa-save"></i></button>                                                           
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function relable_export_newitem_e_keypress(e){
        if(e.which==13){
            let mitmcdold = document.getElementById('relable_export_olditemcd').value.substr(0,9);
            let mitmcdnew = document.getElementById('relable_export_newitem').value;
            if(mitmcdold==mitmcdnew){
                document.getElementById('relable_export_newitem').readOnly=true;
                document.getElementById('relable_export_newlot').focus();
            } else {
                alertify.message('Item code is not same');
                document.getElementById('relable_export_newitem').focus();
            }
        }
    }

    function relable_export_newlot_e_keypress(e){
        if(e.which==13){
            let mlotold = document.getElementById('relable_export_lotno').value;
            let mlotnew = document.getElementById('relable_export_newlot').value;
            if(mlotold==mlotnew){
                document.getElementById('relable_export_newlot').readOnly=true;
                document.getElementById('relable_export_newqty').focus();
            } else {
                alertify.message('Lot Number is not same');
                document.getElementById('relable_export_newlot').focus();
            }
        }
    }

    function relable_export_newqty_e_keypress(e){
        if(e.which==13){
            let mqtyold = numeral(document.getElementById('relable_export_oldqty').value).value();
            let mqtynew = numeral(document.getElementById('relable_export_newqty').value).value();
            if(mqtyold==mqtynew){
                document.getElementById('relable_export_newqty').readOnly=true;
                document.getElementById('relable_export_newreff').focus();
            } else {
                alertify.message('QTY is not same');
                document.getElementById('relable_export_newqty').focus();
            }
        }
    }

    function relable_export_e_clear(){
        document.getElementById('relable_export_oldreff').value='';
        document.getElementById('relable_export_oldjob').value='';
        document.getElementById('relable_export_olditemcd').value='';        
        document.getElementById('relable_export_lotno').value='';
        document.getElementById('relable_export_oldqty').value='';
        document.getElementById('relable_export_newreff').value='';
        document.getElementById('relable_export_newlot').value='';
        document.getElementById('relable_export_newitem').value='';
        document.getElementById('relable_export_newqty').value='';
        document.getElementById('relable_export_oldreff').readOnly=false;
        $("#relable_export_tbl tbody").empty();
    }
    $("#relable_export_btn_new").click(function (e) {         
        relable_export_e_clear();
        document.getElementById('relable_export_oldreff').readOnly=false;
        document.getElementById('relable_export_newreff').readOnly=false;
        document.getElementById('relable_export_newitem').readOnly=false;
        document.getElementById('relable_export_newlot').readOnly=false;
        document.getElementById('relable_export_newqty').readOnly=false;
        document.getElementById('relable_export_newreff').readOnly=false;
        document.getElementById('relable_export_oldreff').focus();
    });
    $("#relable_export_oldreff").keypress(function (e) { 
        if(e.which==13){
            let moldreff = $(this).val();
            if(moldreff.trim()==''){
                alertify.warning('Please entry reff no !');
                return;
            }
            if(moldreff.includes("|")){
                let ar = moldreff.split("|");
                moldreff = ar[5].substr(2,ar[5].length-2);
                $(this).val(moldreff);
            }
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/getproperties_n_tx')?>",
                data: {inid:moldreff },
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='1'){
                        if(response.data[0].SER_ITMID.trim().includes("KD") || response.data[0].SER_ITMID.trim().includes("ASP")) {
                        } else {
                            alertify.message('Could not relabel from status label')
                            document.getElementById('relable_export_oldreff').value=''
                            return
                        }
                        document.getElementById('relable_export_oldreff').readOnly=true;
                        document.getElementById('relable_export_oldjob').value=response.data[0].SER_DOC;
                        document.getElementById('relable_export_olditemcd').value=response.data[0].SER_ITMID.trim();
                        document.getElementById('relable_export_lotno').value=response.data[0].SER_LOTNO;
                        document.getElementById('relable_export_oldqty').value= numeral(response.data[0].SER_QTY).format(',');
                    
                        
                        ///load tx history
                        let ttlrows = response.tx.length;
                        let mydes = document.getElementById("relable_export_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("relable_export_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("relable_export_tbl");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
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
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                        ///end load               
                        document.getElementById('relable_export_newitem').focus();
                    } else {
                        alertify.message(response.status[0].msg);
                        document.getElementById('relable_export_oldreff').value='';
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });

    $("#relable_export_newreff").keypress(function (e) { 
        if(e.which==13){
            let nreff = $(this).val();
            let rawtext = nreff;
            let oldreff = document.getElementById('relable_export_oldreff').value;
            let olditem = document.getElementById('relable_export_olditemcd').value;
            let oldqty = document.getElementById('relable_export_oldqty').value;
            let oldjob = document.getElementById('relable_export_oldjob').value.trim();
            let oldyear = oldjob.substr(0,2);
            oldqty = numeral(oldqty).value();   

            if(!nreff.includes("|") && (nreff.length==16 || nreff.length==18)){
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SER/validate_export_label')?>",
                    data: {innewreff: nreff},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd==1){
                            document.getElementById('relable_export_newreff').readOnly = true;
                            document.getElementById('relable_export_btn_save').focus();
                        } else {
                            alertify.warning(response.status[0].msg);
                        }
                    }, error(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            } else {
                alertify.warning('label is not recognized');
            }
        }
    });
    $("#relable_export_btn_save").click(function (e) {
        let oldreff = document.getElementById('relable_export_oldreff').value;
        let oldjob = document.getElementById('relable_export_oldjob').value;
        let olditemcd = document.getElementById('relable_export_olditemcd').value;
        let rawtxt1 = document.getElementById('relable_export_newreff').value
        let oldqty = document.getElementById('relable_export_oldqty').value;
        let newqty = document.getElementById('relable_export_newqty').value;
        if(oldqty!=newqty){
            alertify.warning('qty must be same')
            return
        }
        if(document.getElementById('relable_export_oldreff').readOnly 
        && document.getElementById('relable_export_newreff').readOnly ){
            let konf = confirm('Are You sure ?');
            if (!konf){
                alertify.message('You are not sure');
                return;
            }
            $.ajax({
                type: "post",
                url: "<?=base_url('SER/validate_relable')?>",
                data: {inoldreff: oldreff,  innewreff: rawtxt1},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){
                        relable_export_e_clear();
                        alertify.success(response.status[0].msg);
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {
            if(!document.getElementById('relable_export_newreff').readOnly) {
                document.getElementById('relable_export_newreff').focus()
                alertify.message('please enter to confirm')
            }
        }
    });
</script>

