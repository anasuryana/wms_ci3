<div style="padding: 5px">
	<div class="container-fluid">  
        <div class="row">
            <div class="col-md-3 mb-1">
                <h2 ><span class="badge bg-info">1 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">From</span></h2>
            </div>            
        </div>      
        <div class="row">   
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Old Reff No</span>                    
                    <input type="text" class="form-control" id="splitlabelrtn_oldreff" required>
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Document</span>                    
                    <input type="text" class="form-control" id="splitlabelrtn_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="splitlabelrtn_olditemcd" required readonly>
                </div>
            </div>    
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Qty</span>                    
                    <input type="text" class="form-control" id="splitlabelrtn_oldqty" required readonly>
                </div>
            </div>        
        </div>  
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h3>Transaction History</h3>
                <div class="table-responsive" id="splitlabelrtn_divku">
                    <table id="splitlabelrtn_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
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
        <div id="splitlabelrtn_div_rnm">
            <div class="row">
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Qty</span>                        
                        <input type="text" class="form-control" id="splitlabelrtn_newqty" required>                   
                    </div>
                </div>                          
                <div class="col-md-9 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Reff Code</span>                        
                        <input type="text" class="form-control" id="splitlabelrtn_newreff" required readonly>                        
                    </div>
                </div>
            </div>           
        </div>        
       
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="splitlabelrtn_btn_new"><i class="fas fa-file"></i></button>                    
                    <button title="Save" type="button" class="btn btn-outline-primary" id="splitlabelrtn_btn_save"><i class="fas fa-save"></i></button>                                        
                    <button title="Print" type="button" class="btn btn-outline-primary" id="splitlabelrtn_btn_print"><i class="fas fa-print"></i></button>                                        
                </div>
            </div>
        </div>        
    </div>
</div>
<input type="hidden" id="splitlabelrtn_line">
<input type="hidden" id="splitlabelrtn_prd_date">
<script>
    $("#splitlabelrtn_btn_new").click(function (e) {                 
        document.getElementById('splitlabelrtn_oldreff').focus();
        document.getElementById('splitlabelrtn_oldreff').readOnly=false;
        document.getElementById('splitlabelrtn_btn_save').disabled=false;
        
        splitlabelrtn_e_clearinput();
    });
    $("#splitlabelrtn_oldreff").keypress(function (e) { 
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
            if(moldreff.substr(0,1)!='4'){
                alertify.warning('it was not valid ID');
                return;
            }
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/getproperties_n_tx')?>",
                data: {inid:moldreff },
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='1'){
                        if(response.data[0].SER_DOC){
                            if(response.data[0].SER_DOC.substr(3,1)=='C'){
                                alertify.warning('Coult not split combined job with different job');
                                return;
                            }
                        }
                        if(numeral(response.data[0].SER_QTY).value()<=0){
                            alertify.warning('Coult not split zero value');
                            return;
                        }
                        document.getElementById('splitlabelrtn_oldreff').readOnly = true;
                        document.getElementById('splitlabelrtn_oldjob').value = response.data[0].SER_DOC;
                        document.getElementById('splitlabelrtn_olditemcd').value = response.data[0].SER_ITMID.trim();
                        document.getElementById('splitlabelrtn_oldqty').value = numeral(response.data[0].SER_QTY).format(',');
                        document.getElementById('splitlabelrtn_line').value = response.data[0].SER_PRDLINE;
                        document.getElementById('splitlabelrtn_prd_date').value = response.data[0].SER_PRDDT;
                                              
                        ///load tx history
                        let ttlrows = response.tx.length;
                        let mydes = document.getElementById("splitlabelrtn_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("splitlabelrtn_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("splitlabelrtn_tbl");
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
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);  

                        document.getElementById('splitlabelrtn_newqty').value='';
                        document.getElementById('splitlabelrtn_newqty').focus();
                    } else {
                        alertify.message(response.status[0].msg);
                        document.getElementById('splitlabelrtn_oldreff').value='';
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
   
    function splitlabelrtn_e_clearinput(){
        document.getElementById('splitlabelrtn_oldreff').value='';       
        document.getElementById('splitlabelrtn_oldjob').value='';
        document.getElementById('splitlabelrtn_olditemcd').value='';
        document.getElementById('splitlabelrtn_oldqty').value='';            
        document.getElementById('splitlabelrtn_newreff').value='';        
        document.getElementById('splitlabelrtn_newqty').value='';       
        $("#splitlabelrtn_tbl tbody").empty();
    }

    function splitlabelrtn_e_clearinput_to(){
        document.getElementById('splitlabelrtn_newqty').value='';
        document.getElementById('splitlabelrtn_newreff').value='';            
    }

    $("#splitlabelrtn_btn_save").click(function (e) {
        let moldreff = document.getElementById('splitlabelrtn_oldreff').value;
        let moldjob = document.getElementById('splitlabelrtn_oldjob').value;
        let molditem = document.getElementById('splitlabelrtn_olditemcd').value;
        let oldqty = document.getElementById('splitlabelrtn_oldqty').value;             
        let mnewqty = document.getElementById('splitlabelrtn_newqty').value;        
        let mline = document.getElementById('splitlabelrtn_line').value;
        let mprd_date = document.getElementById('splitlabelrtn_prd_date').value;
        
        if(mnewqty.trim()==''){
            alertify.warning('New Qty could not be empty !');
            return;
        }
        mnewqty = numeral(mnewqty).value();
        oldqty = numeral(oldqty).value();
        
        if(mnewqty > oldqty){
            alertify.warning('Over QTY');
            return;
        }
        if(mnewqty==oldqty){
            alertify.warning('could not split the label, because qty is same');
            return;
        }
        let konfr = confirm('Are you sure ?');
        if(konfr){
            document.getElementById('splitlabelrtn_btn_save').disabled=true;
            $.ajax({
                type: "post",
                url: "<?=base_url('SER/split_returncontrol_label')?>",
                data: {inoldqty: oldqty, inoldreff : moldreff, inoldjob: moldjob , inolditem: molditem, 
                innewqty: mnewqty, inline: mline, inprd_date: mprd_date},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){
                        document.getElementById('splitlabelrtn_newreff').value = response.status[0].reffcode;
                        alertify.success(response.status[0].msg);
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            }); 
        }
    }); 
    
    $("#splitlabelrtn_newqty").keyup(function (e) { 
        let newval = $(this).val();
        newval = numeral(newval).format(',');
        $(this).val(newval);
    });
    
    $("#splitlabelrtn_btn_print").click(function (e) {         
        let oldreff = document.getElementById('splitlabelrtn_oldreff').value;
        let newreff = document.getElementById('splitlabelrtn_newreff').value;       
        if(newreff.trim().length<5){
            alertify.message("new reff is required");
            document.getElementById('splitlabelrtn_newreff').focus();
            return;
        }
        Cookies.set('PRINTLABEL_FG_SIZE', 'A4', {expires:365});       
        let ser_serprint = [];
        ser_serprint.push(oldreff);
        ser_serprint.push(newreff);
        Cookies.set('PRINTLABEL_FGRTN', ser_serprint, {expires:365});     
        window.open("<?=base_url('printlabel_fgrtnstatus')?>",'_blank');
    });
    
</script>