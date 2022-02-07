<div style="padding: 5px">
	<div class="container-fluid">  
        <div class="row">
            <div class="col-md-3 mb-1">
                <h2 ><span class="badge bg-info">1<i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">From</span></h2>
            </div>            
        </div>      
        <div class="row">   
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Old Reff No</span>                    
                    <input type="text" class="form-control" id="splitstatuslabel_oldreff" required>                   
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Job Number</span>                    
                    <input type="text" class="form-control" id="splitstatuslabel_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="splitstatuslabel_olditemcd" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" title="Production Date" >Prod. Date</span>                    
                    <input type="text" class="form-control" id="splitstatuslabel_proddate" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Qty</span>
                    <input type="text" class="form-control" id="splitstatuslabel_oldqty" required readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Line</span>
                    <input type="text" class="form-control" id="splitstatuslabel_line" required readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Shift</span>
                    <input type="text" class="form-control" id="splitstatuslabel_shift" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h3>Transaction History</h3>
                <div class="table-responsive" id="splitstatuslabel_divku">
                    <table id="splitstatuslabel_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
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
        <div id="splitstatuslabel_div_rnm">
            <div class="row">
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Qty 1</span>                        
                        <input type="text" class="form-control" id="splitstatuslabel_newqty" required>                        
                    </div>
                </div>                              
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Qty 2</span>
                        <input type="text" class="form-control" id="splitstatuslabel_newqty2" required readonly>
                    </div>
                </div>                              
                <div class="col-md-6 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Reff Code</span>                        
                        <input type="text" class="form-control" id="splitstatuslabel_newreff" required readonly>                        
                    </div>
                </div>
            </div>           
        </div>        
       
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="splitstatuslabel_btn_new"><i class="fas fa-file"></i></button>                    
                    <button title="Save" type="button" class="btn btn-outline-primary" id="splitstatuslabel_btn_save"><i class="fas fa-save"></i></button>                                        
                    <button title="Print" type="button" class="btn btn-outline-primary" id="splitstatuslabel_btn_print"><i class="fas fa-print"></i></button>                                        
                </div>
            </div>
        </div>        
    </div>
</div>
<input type="hidden" id="splitstatuslabel_temp_oldreff">
<script>
    $("#splitstatuslabel_btn_new").click(function (e) {                 
        document.getElementById('splitstatuslabel_oldreff').focus();
        document.getElementById('splitstatuslabel_oldreff').readOnly=false;
        document.getElementById('splitstatuslabel_btn_save').disabled=false;
        
        splitstatuslabel_e_clearinput();
    });
    $("#splitstatuslabel_oldreff").keypress(function (e) { 
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
                url: "<?=base_url('SER/getproperties_n_tx_statuslbl')?>",
                data: {inid:moldreff },
                dataType: "json",
                success:  (response) => {
                    if(response.status[0].cd=='1'){
                        document.getElementById('splitstatuslabel_oldreff').readOnly=true;
                        document.getElementById('splitstatuslabel_oldjob').value=response.data[0].SER_DOC;
                        document.getElementById('splitstatuslabel_olditemcd').value=response.data[0].SER_ITMID.trim();
                        document.getElementById('splitstatuslabel_oldqty').value= numeral(response.data[0].SER_QTY).format(',');
                        document.getElementById('splitstatuslabel_proddate').value=response.data[0].SER_PRDDT;                        
                        document.getElementById('splitstatuslabel_line').value=response.data[0].SER_PRDLINE;
                        document.getElementById('splitstatuslabel_shift').value=response.data[0].SER_PRDSHFT;

                        ///load tx history
                        let ttlrows = response.tx.length;
                        let mydes = document.getElementById("splitstatuslabel_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("splitstatuslabel_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("splitstatuslabel_tbl");
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

                        document.getElementById('splitstatuslabel_newqty').value='';                        
                        if(numeral(response.data[0].MITM_SHTQTY).value()>0){
                            
                            document.getElementById('splitstatuslabel_newqty').readOnly=true;                            
                        } else {
                            document.getElementById('splitstatuslabel_newqty').focus();
                            document.getElementById('splitstatuslabel_newqty').readOnly=false;                        
                        }
                    } else {
                        alertify.message(response.status[0].msg);
                        document.getElementById('splitstatuslabel_oldreff').value='';
                    }
                }, error: (xhr, xopt, xthrow) => {
                    alertify.error(xthrow);
                }
            });
        }
    });
   
    function splitstatuslabel_e_clearinput(){
        document.getElementById('splitstatuslabel_oldreff').value='';       
        document.getElementById('splitstatuslabel_temp_oldreff').value='';       
        document.getElementById('splitstatuslabel_oldjob').value='';
        document.getElementById('splitstatuslabel_olditemcd').value='';
        document.getElementById('splitstatuslabel_oldqty').value='';
        document.getElementById('splitstatuslabel_proddate').value='';            
        document.getElementById('splitstatuslabel_line').value='';
        document.getElementById('splitstatuslabel_shift').value='';

        document.getElementById('splitstatuslabel_newreff').value='';        
        document.getElementById('splitstatuslabel_newqty').value='';           
        $("#splitstatuslabel_tbl tbody").empty();
    }

    function splitstatuslabel_e_clearinput_to(){
        document.getElementById('splitstatuslabel_newqty').value='';
        document.getElementById('splitstatuslabel_newreff').value='';            
    }

    $("#splitstatuslabel_btn_save").click(function (e) {
        // let misgood = document.getElementById('splitstatuslabel_ck_isgood').checked;        
        let moldreff = document.getElementById('splitstatuslabel_oldreff').value;
        let moldjob = document.getElementById('splitstatuslabel_oldjob').value;
        let molditem = document.getElementById('splitstatuslabel_olditemcd').value;
        let oldqty = document.getElementById('splitstatuslabel_oldqty').value;
        let oldprd_date = document.getElementById('splitstatuslabel_proddate').value;        
        let mnewqty = document.getElementById('splitstatuslabel_newqty').value
        let mline = document.getElementById('splitstatuslabel_line').value;
        let mshift = document.getElementById('splitstatuslabel_shift').value;
        if(moldreff.trim().length==0){
            alertify.warning('Please select old reff first');
            document.getElementById('splitstatuslabel_oldreff').focus();
            return;
        }
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
        
        if(confirm('Are you sure ?')){
            document.getElementById('splitstatuslabel_btn_save').disabled=true;
            $.ajax({
                type: "post",
                url: "<?=base_url('SER/split_label_status')?>",
                data: {inoldqty: oldqty, inoldreff : moldreff, inoldjob: moldjob , inprddt : oldprd_date , inolditem: molditem, 
                innewqty: mnewqty,  inline: mline, inshift: mshift},
                dataType: "json",
                success:  (response) => {
                    if(response.status[0].cd!='0'){
                        document.getElementById('splitstatuslabel_newreff').value = response.status[0].reffcode;
                        document.getElementById('splitstatuslabel_newqty').value = '';
                        document.getElementById('splitstatuslabel_oldreff').value = '';
                        document.getElementById('splitstatuslabel_temp_oldreff').value = response.status[0].reffcode2
                        alertify.success(response.status[0].msg);
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                }, error : (xhr, xopt, xthrow) => {
                    alertify.error(xthrow);
                }
            }); 
        }
    });    
    
    $("#splitstatuslabel_newqty").keyup( function(e) { 
        let newval = $(this).val();
        newval = numeral(newval).format(',');
        $(this).val(newval);
    });
   
    $("#splitstatuslabel_btn_print").click(function (e) { 
        const oldser = document.getElementById('splitstatuslabel_temp_oldreff').value;
        const newser = document.getElementById('splitstatuslabel_newreff').value;
        if(newser.trim()==''){
            alertify.warning('New Serial could not be empty');
            return;
        }
        const serlist = [oldser,newser];        
        Cookies.set('PRINTLABEL_FG', serlist, {expires:365});
        Cookies.set('PRINTLABEL_FG_LBLTYPE', '1', {expires:365});
        Cookies.set('PRINTLABEL_FG_SIZE', 'A4', {expires:365});
        window.open("<?=base_url('printlabel_fgstatus')?>",'_blank');        
    });
    
</script>