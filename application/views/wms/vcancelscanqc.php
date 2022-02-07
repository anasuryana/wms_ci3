<div style="padding: 5px">
	<div class="container-xxl">             
        <div class="row">   
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Reff No</span>                    
                    <input type="text" class="form-control" id="cancel_scn_qc_oldreff" required>
                   
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Job Number</span>                    
                    <input type="text" class="form-control" id="cancel_scn_qc_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>
                    <input type="text" class="form-control" id="cancel_scn_qc_olditemcd" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Qty</span>                    
                    <input type="text" class="form-control" id="cancel_scn_qc_oldqty" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h3>Transaction History</h3>
                <div class="table-responsive" id="cancel_scn_qc_divku">
                    <table id="cancel_scn_qc_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
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
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="cancel_scn_qc_btn_new"><i class="fas fa-file"></i></button>                    
                    <button title="Save" type="button" class="btn btn-warning" id="cancel_scn_qc_btn_save">Conform</button>                                  
                </div>
            </div>
        </div>        
    </div>
</div>

<script>
    $("#cancel_scn_qc_oldreff").keypress(function (e) { 
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
            if(moldreff.substr(0,1)=='3'){
                document.getElementById('cancel_scn_qc_oldreff').value = '';
                alertify.message('for regular label only')
                return;
            }
            document.getElementById('cancel_scn_qc_oldreff').readOnly=true;
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/getproperties_n_tx')?>",
                data: {inid:moldreff },
                dataType: "json",
                success: function (response) {
                    
                    if(response.status[0].cd=='1'){
                        
                        document.getElementById('cancel_scn_qc_oldjob').value=response.data[0].SER_DOC;
                        document.getElementById('cancel_scn_qc_olditemcd').value=response.data[0].SER_ITMID.trim();
                        document.getElementById('cancel_scn_qc_oldqty').value= numeral(response.data[0].SER_QTY).format(',');                        
                        
                        ///load tx history
                        let ttlrows = response.tx.length;
                        let mydes = document.getElementById("cancel_scn_qc_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("cancel_scn_qc_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("cancel_scn_qc_tbl");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        let mwh = false;
                        for (let i = 0; i<ttlrows; i++){                    
                            if(response.tx[i].ITH_WH.trim()=='AFWH3'){
                                mwh = true;
                                break;
                            }
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
                        if(mwh){
                            tableku2.innerHTML='';
                            alertify.warning('Could not cancel scan, because it is already scanned by PPIC');

                            document.getElementById('cancel_scn_qc_oldreff').readOnly=false;
                            document.getElementById('cancel_scn_qc_oldreff').value='';
                            document.getElementById('cancel_scn_qc_oldjob').value='';
                            document.getElementById('cancel_scn_qc_olditemcd').value='';
                            document.getElementById('cancel_scn_qc_oldqty').value= '';
                            return;
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);                       
                    } else {
                        alertify.message(response.status[0].msg);
                        document.getElementById('cancel_scn_qc_oldreff').value='';
                        document.getElementById('cancel_scn_qc_oldreff').readOnly=false;
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#cancel_scn_qc_btn_new").click(function (e) { 
        document.getElementById('cancel_scn_qc_oldreff').value='';
        document.getElementById('cancel_scn_qc_oldjob').value='';
        document.getElementById('cancel_scn_qc_olditemcd').value='';
        document.getElementById('cancel_scn_qc_oldqty').value='';
        
        document.getElementById('cancel_scn_qc_oldreff').readOnly=false;
        $("#cancel_scn_qc_tbl tbody").empty();
    });

    $("#cancel_scn_qc_btn_save").click(function (e) { 
        let reffno = document.getElementById('cancel_scn_qc_oldreff').value;
        if(reffno.trim()==''){
            alertify.message('Please enter Reff No');
            document.getElementById('cancel_scn_qc_oldreff').focus();
            return;
        }
        if(!document.getElementById('cancel_scn_qc_oldreff').readOnly){
            alertify.message('Please press enter to confirm');
            document.getElementById('cancel_scn_qc_oldreff').focus();
            return;
        }
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/qccancelscan')?>",
            data: {inreff : reffno},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    document.getElementById('cancel_scn_qc_oldreff').value='';
                    $("#cancel_scn_qc_tbl tbody").empty();
                    alertify.success(response.status[0].msg);
                } else {
                    alertify.warning(response.status[0].msg);
                }
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
</script>