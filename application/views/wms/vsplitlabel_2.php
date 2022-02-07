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
                    <input type="text" class="form-control" id="splitlabel2_oldreff" required>
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Job Number</span>                    
                    <input type="text" class="form-control" id="splitlabel2_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="splitlabel2_olditemcd" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" title="Production Date" >Date</span>                    
                    <input type="text" class="form-control" id="splitlabel2_proddate" required readonly>
                </div>
            </div>
        </div>
        <div class="row">   
            <div class="col-md-3 mb-1 pr-1">                
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >SPQ</span>                    
                    <input type="text" class="form-control" id="splitlabel2_spq" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">                
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Qty</span>                    
                    <input type="text" class="form-control" id="splitlabel2_oldqty" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">                
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >sheet</span>                    
                    <input type="text" class="form-control" id="splitlabel2_sheet" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">                
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >QTY / sheet</span>                    
                    <input type="text" class="form-control" id="splitlabel2_qtysheet" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Line</span>                    
                    <input type="text" class="form-control" id="splitlabel2_line" required readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Shift</span>                    
                    <input type="text" class="form-control" id="splitlabel2_shift" required readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Rank</span>                    
                    <input type="text" class="form-control" id="splitlabel2_rank" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h3>Transaction History</h3>
                <div class="table-responsive" id="splitlabel2_divku">
                    <table id="splitlabel2_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
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
        <div id="splitlabel2_div_rnm">
            <div class="row">
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Qty</span>                        
                        <input type="text" class="form-control" id="splitlabel2_newqty" required>                   
                    </div>
                </div>                
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Sheet</span>                        
                        <input type="text" class="form-control" id="splitlabel2_newsheet" required>
                    </div>
                </div>                
                <div class="col-md-6 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Reff Code</span>                        
                        <input type="text" class="form-control" id="splitlabel2_newreff" required readonly>                        
                    </div>
                </div>
            </div>           
        </div>        
       
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="splitlabel2_btn_new"><i class="fas fa-file"></i></button>                    
                    <button title="Save" type="button" class="btn btn-outline-primary" id="splitlabel2_btn_save"><i class="fas fa-save"></i></button>                                        
                    <button title="Print" type="button" class="btn btn-outline-primary" id="splitlabel2_btn_print"><i class="fas fa-print"></i></button>                                        
                </div>
            </div>
        </div>        
    </div>
</div>
<script>
    $("#splitlabel2_btn_new").click(function (e) {                 
        document.getElementById('splitlabel2_oldreff').focus();
        document.getElementById('splitlabel2_oldreff').readOnly=false;
        document.getElementById('splitlabel2_btn_save').disabled=false;
        
        splitlabel2_e_clearinput();
    });
    $("#splitlabel2_oldreff").keypress(function (e) { 
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
                        if(response.data[0].SER_DOC){
                            if(response.data[0].SER_DOC.substr(3,1)=='C'){
                                alertify.warning('Coult not split combined job with different job');
                                return;
                            }
                        }
                        document.getElementById('splitlabel2_oldreff').readOnly=true;
                        document.getElementById('splitlabel2_oldjob').value=response.data[0].SER_DOC;
                        document.getElementById('splitlabel2_olditemcd').value=response.data[0].SER_ITMID.trim();
                        document.getElementById('splitlabel2_oldqty').value= numeral(response.data[0].SER_QTY).format(',');
                        document.getElementById('splitlabel2_proddate').value=response.data[0].SER_PRDDT;
                        document.getElementById('splitlabel2_spq').value = numeral(response.data[0].MITM_SPQ).format(',');
                        document.getElementById('splitlabel2_sheet').value=response.data[0].SER_SHEET;
                        document.getElementById('splitlabel2_qtysheet').value=response.data[0].MITM_SHTQTY;
                        document.getElementById('splitlabel2_line').value=response.data[0].SER_PRDLINE;
                        document.getElementById('splitlabel2_shift').value=response.data[0].SER_PRDSHFT;
                        document.getElementById('splitlabel2_rank').value=response.data[0].SER_GRADE

                        ///load tx history
                        let ttlrows = response.tx.length;
                        let mydes = document.getElementById("splitlabel2_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("splitlabel2_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("splitlabel2_tbl");
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

                        document.getElementById('splitlabel2_newqty').value='';
                        document.getElementById('splitlabel2_newsheet').value='';
                        if(numeral(response.data[0].MITM_SHTQTY).value()>0){
                            document.getElementById('splitlabel2_newsheet').focus();
                            document.getElementById('splitlabel2_newqty').readOnly=true;
                            document.getElementById('splitlabel2_newsheet').readOnly=false;
                        } else {
                            document.getElementById('splitlabel2_newqty').focus();
                            document.getElementById('splitlabel2_newqty').readOnly=false;
                            document.getElementById('splitlabel2_newsheet').readOnly=true;
                        }
                    } else {
                        alertify.message(response.status[0].msg);
                        document.getElementById('splitlabel2_oldreff').value='';
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
   
    function splitlabel2_e_clearinput(){
        document.getElementById('splitlabel2_oldreff').value='';       
        document.getElementById('splitlabel2_oldjob').value='';
        document.getElementById('splitlabel2_olditemcd').value='';
        document.getElementById('splitlabel2_oldqty').value='';
        document.getElementById('splitlabel2_proddate').value='';
        document.getElementById('splitlabel2_spq').value='';
        document.getElementById('splitlabel2_sheet').value='';
        document.getElementById('splitlabel2_qtysheet').value='';
        document.getElementById('splitlabel2_line').value='';
        document.getElementById('splitlabel2_shift').value='';

        document.getElementById('splitlabel2_newreff').value='';        
        document.getElementById('splitlabel2_newqty').value='';   
        document.getElementById('splitlabel2_newsheet').value='';
        $("#splitlabel2_tbl tbody").empty();
    }

    function splitlabel2_e_clearinput_to(){
        document.getElementById('splitlabel2_newqty').value='';
        document.getElementById('splitlabel2_newreff').value='';            
    }

    $("#splitlabel2_btn_save").click(function (e) {
        let moldreff = document.getElementById('splitlabel2_oldreff').value;
        let moldjob = document.getElementById('splitlabel2_oldjob').value;
        let molditem = document.getElementById('splitlabel2_olditemcd').value;
        let oldqty = document.getElementById('splitlabel2_oldqty').value;
        let oldprd_date = document.getElementById('splitlabel2_proddate').value;
        let oldsht = document.getElementById('splitlabel2_sheet').value;
        let mnewqty = document.getElementById('splitlabel2_newqty').value;
        let mnewsht = document.getElementById('splitlabel2_newsheet').value;
        let mline = document.getElementById('splitlabel2_line').value;
        let mshift = document.getElementById('splitlabel2_shift').value;
        const rank = document.getElementById('splitlabel2_rank').value
        if(mnewqty.trim()==''){
            alertify.warning('New Qty could not be empty !');
            return;
        }
        mnewqty = numeral(mnewqty).value();
        oldqty = numeral(oldqty).value();
        mnewsht = numeral(mnewsht).value();
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
            document.getElementById('splitlabel2_btn_save').disabled=true;
            $.ajax({
                type: "post",
                url: "<?=base_url('SER/validate_prep')?>",
                data: {inoldqty: oldqty, inoldreff : moldreff, inoldjob: moldjob , inprddt : oldprd_date , inolditem: molditem, inoldsht : oldsht ,
                innewqty: mnewqty, innewsht: mnewsht, inline: mline, inshift: mshift, rank: rank},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){
                        document.getElementById('splitlabel2_newreff').value = response.status[0].reffcode;
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
    
    $("#splitlabel2_newqty").keyup(function (e) { 
        let newval = $(this).val();
        newval = numeral(newval).format(',');
        $(this).val(newval);
    });
    $("#splitlabel2_newsheet").keyup(function (e) { 
        let cttlsht = $(this).val();
        let cshtqty = document.getElementById('splitlabel2_qtysheet').value;
        let theqty = numeral(cttlsht).value()*numeral(cshtqty).value();
        document.getElementById('splitlabel2_newqty').value= theqty;
    });    
    $("#splitlabel2_btn_print").click(function (e) { 
        let oldser = document.getElementById('splitlabel2_oldreff').value;
        let newser = document.getElementById('splitlabel2_newreff').value;

        if(newser.trim()==''){
            alertify.warning('New Serial could not be empty');
            return;
        }

        let serlist = [];
        serlist.push(oldser);
        serlist.push(newser);
        
        Cookies.set('PRINTLABEL_FG', serlist, {expires:365});
        Cookies.set('PRINTLABEL_FG_LBLTYPE', '1', {expires:365});
        Cookies.set('PRINTLABEL_FG_SIZE', 'A4', {expires:365});
        window.open("<?=base_url('printlabel_fg')?>",'_blank');        
    });
    
</script>