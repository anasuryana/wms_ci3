<div style="padding: 5px">
	<div class="col-md-12 order-md-1">  
        <div class="row">
            <div class="col-md-3 mb-1">
                <h2 ><span class="badge badge-info">1 <i class="fas fa-hand-point-right" ></i></span> <span class="badge badge-info">From</span></h2>
            </div>            
        </div>      
        <div class="row">   
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Old Reff No</span>
                    </div>
                    <input type="text" class="form-control" id="splitlabel1_oldreff" required>
                   
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Job Number</span>
                    </div>
                    <input type="text" class="form-control" id="splitlabel1_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Item Code</span>
                    </div>
                    <input type="text" class="form-control" id="splitlabel1_olditemcd" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Qty</span>
                    </div>
                    <input type="text" class="form-control" id="splitlabel1_oldqty" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h3>Transaction History</h3>
                <div class="table-responsive" id="splitlabel1_divku">
                    <table id="splitlabel1_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="thead-light">
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
                <h2 ><span class="badge badge-info">2 <i class="fas fa-hand-point-right" ></i></span> <span class="badge badge-info">To</span></h2>
            </div>
            <div class="col-md-9 mb-1">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="splitlabel1_typefg" id="splitlabel1_typereg" value="" checked>
                    <label class="form-check-label" for="splitlabel1_typereg">Regular</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="splitlabel1_typefg" id="splitlabel1_typekd" value="KD">
                    <label class="form-check-label" for="splitlabel1_typekd">KD</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="splitlabel1_typefg" id="splitlabel1_typeasp" value="ASP" >
                    <label class="form-check-label" for="splitlabel1_typeasp">ASP</label>
                </div>               
            </div>
        </div>
        <div class="d-none" id="splitlabel1_div_ka">
            <div class="row" >                
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Remark</span>
                        </div>
                        <input type="text" class="form-control" id="splitlabel1_txt_newmodel_ka" maxlength="5" required style="text-align: right">                         
                    </div>
                </div>
            </div>
            <div class="row" >
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Item Code</span>
                        </div>
                        <input type="text" class="form-control" id="splitlabel1_txt_itemcode" maxlength="50" required>  
                        <div class="input-group-append">
                            <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Lot No</span>
                        </div>
                        <input type="text" class="form-control" id="splitlabel1_txt_lotno" maxlength=50 required>  
                        <div class="input-group-append">
                            <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Qty</span>
                        </div>
                        <input type="text" class="form-control" id="splitlabel1_txt_qty" maxlength="5" required style="text-align: right">  
                        <div class="input-group-append">
                            <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Reff Code</span>
                        </div>
                        <input type="text" class="form-control" id="splitlabel1_txt_code" maxlength="25" required style="text-align: right">  
                        <div class="input-group-append">
                            <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                        </div>                        
                    </div>
                </div>
            </div>
            
        </div>
        <div id="splitlabel1_div_rnm">
            <div class="row" >               
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Remark</span>
                        </div>
                        <input type="text" class="form-control" id="splitlabel1_txt_newmodel" maxlength="5" required style="text-align: right">                          
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >New Reff No</span>
                        </div>
                        <input type="text" class="form-control" id="splitlabel1_newreff" required>                   
                    </div>
                </div>
                <div class="col-md-4 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Job Number</span>
                        </div>
                        <input type="text" class="form-control" id="splitlabel1_newjob" required readonly>
                    </div>
                </div>
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Item Code</span>
                        </div>
                        <input type="text" class="form-control" id="splitlabel1_newitemcd" required readonly>
                    </div>
                </div>
                <div class="col-md-2 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Qty</span>
                        </div>
                        <input type="text" class="form-control" id="splitlabel1_newqty" required readonly>
                    </div>
                </div>   
                <input type="hidden" id="splitlabel1_rawtxt1">
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-3 mb-1">
                <h2 ><span class="badge badge-info">3 <i class="fas fa-hand-point-right" ></i></span> <span class="badge badge-info">To</span></h2>
            </div>
            <div class="col-md-9 mb-1">
                <div class="form-check form-check-inline ">
                    <input class="form-check-input" type="radio" name="splitlabel1_goorno" id="splitlabel1_go" value="1" checked>
                    <label class="form-check-label bg-success" for="splitlabel1_go">GOOD</label>
                </div>
                <div class="form-check form-check-inline ">
                    <input class="form-check-input" type="radio" name="splitlabel1_goorno" id="splitlabel1_no" value="0">
                    <label class="form-check-label bg-danger" for="splitlabel1_no">NOT GOOD</label>
                </div>                           
            </div>
        </div>
        <div class="row" >               
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Remark</span>
                    </div>
                    <input type="text" class="form-control" id="splitlabel1_txt_newmodel2" maxlength="5" required style="text-align: right">                          
                </div>
            </div>
        </div>  
        <div class="row">            
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >New Reff No 2</span>
                    </div>
                    <input type="text" class="form-control" id="splitlabel1_newreff2" required>                   
                </div>
            </div>
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Job Number</span>
                    </div>
                    <input type="text" class="form-control" id="splitlabel1_newjob2" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Item Code</span>
                    </div>
                    <input type="text" class="form-control" id="splitlabel1_newitemcd2" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Qty</span>
                    </div>
                    <input type="text" class="form-control" id="splitlabel1_newqty2" required readonly>
                </div>
            </div> 
            <input type="hidden" id="splitlabel1_rawtxt2">
        </div>
                
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="splitlabel1_btn_new"><i class="fas fa-file"></i></button>                    
                    <button title="Save" type="button" class="btn btn-outline-primary" id="splitlabel1_btn_save"><i class="fas fa-save"></i></button>                                        
                </div>
            </div>
        </div>        
    </div>
</div>
<script>
    $("#splitlabel1_btn_new").click(function (e) {         
        splitlabel1_e_clearinput();        
        document.getElementById('splitlabel1_newreff').readOnly=false;
        document.getElementById('splitlabel1_newreff2').readOnly=false;
        document.getElementById('splitlabel1_oldreff').readOnly=false;
        document.getElementById('splitlabel1_oldreff').focus();        
    });
    $("#splitlabel1_oldreff").keypress(function (e) { 
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
            document.getElementById('splitlabel1_oldreff').readOnly=true;
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/getproperties_n_tx')?>",
                data: {inid:moldreff },
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='1'){
                        
                        document.getElementById('splitlabel1_oldjob').value=response.data[0].SER_DOC;
                        document.getElementById('splitlabel1_olditemcd').value=response.data[0].SER_ITMID.trim();
                        document.getElementById('splitlabel1_oldqty').value= numeral(response.data[0].SER_QTY).format(',');
                        let ilength = response.data[0].SER_ITMID.trim().length;
                        let theremark = response.data[0].SER_ITMID.trim().substr(ilength-2,ilength);
                        if(theremark=='ES'){
                            document.getElementById('splitlabel1_txt_newmodel_ka').value=theremark;
                            document.getElementById('splitlabel1_txt_newmodel').value=theremark;
                            document.getElementById('splitlabel1_txt_newmodel2').value=theremark;
                        } else {
                            document.getElementById('splitlabel1_txt_newmodel_ka').value='';
                            document.getElementById('splitlabel1_txt_newmodel').value='';
                            document.getElementById('splitlabel1_txt_newmodel2').value='';
                        }
                        
                        
                        ///load tx history
                        let ttlrows = response.tx.length;
                        let mydes = document.getElementById("splitlabel1_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("splitlabel1_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("splitlabel1_tbl");
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
                        let mtypefg = $("input[name='splitlabel1_typefg']:checked").val();
                        if(mtypefg==''){
                            document.getElementById('splitlabel1_newreff').focus();
                        } else {
                            document.getElementById('splitlabel1_txt_itemcode').focus();
                        }
                    } else {
                        alertify.message(response.status[0].msg);
                        document.getElementById('splitlabel1_oldreff').value='';
                        document.getElementById('splitlabel1_oldreff').readOnly=false;
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#splitlabel1_newreff").keypress(function (e) { 
        if(e.which==13){
            let nreff = $(this).val();
            let rawtext = nreff;
            let oldreff = document.getElementById('splitlabel1_oldreff').value;
            let olditem = document.getElementById('splitlabel1_olditemcd').value;
            let oldqty = document.getElementById('splitlabel1_oldqty').value;
            let oldjob = document.getElementById('splitlabel1_oldjob').value.trim();
            let oldyear = oldjob.substr(0,2);
            let remark1 = document.getElementById('splitlabel1_txt_newmodel').value;
            oldqty = numeral(oldqty).value();            
            if(nreff.includes("|")){
                document.getElementById('splitlabel1_rawtxt1').value=nreff;                
                let ar = nreff.split("|");
                let newitem = ar[0].substr(2,ar[0].length-2);
                let newqty = ar[3].substr(2,ar[3].length-2);
                let thelot = ar[2].substr(2,ar[2].length-2);
                let tempjob = thelot.substr(3,5);
                if(tempjob.substr(0,1)=='0'){
                    tempjob = tempjob.substr(1,4);
                }
                newitem+= remark1;
                let newjob = oldyear+'-'+tempjob+'-'+newitem;

                ///#1 check item code
                if( newitem!=olditem ){
                    alertify.warning('New Item is not same with old item, please compare the label !');
                    $(this).val('');
                    return;
                }
                ///# check qty
                if(numeral(newqty).value()> oldqty){
                    alertify.warning('old qty > new qty, please check the label again');
                    $(this).val('');
                    return;
                }
                if(numeral(newqty).value()== oldqty){
                    alertify.warning('could not split because qty is same');
                    $(this).val('');
                    return;
                }

                ///# check job
                if(oldjob!=newjob){
                    alertify.warning('job is not same, please check the label again '+ oldjob + ' != ' + newjob);
                    $(this).val('');
                    return;
                }
                nreff = ar[5].substr(2,ar[5].length-2);
                if(nreff==oldreff){
                    alertify.warning('could not transfer to the same reff no');
                    $(this).val('');
                    return;
                }
                $(this).val(nreff);
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SER/validate_newreff')?>",
                    data: {inrawtext: rawtext},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd!='0'){
                            let remark1 = document.getElementById('splitlabel1_txt_newmodel').value;
                            let newreff = response.status[0].rawtext;
                            let ar = newreff.split("|");
                            let newitem = ar[0].substr(2,ar[0].length-2);
                            let newqty = ar[3].substr(2,ar[3].length-2);
                            let thelot = ar[2].substr(2,ar[2].length-2);
                            let tempjob = thelot.substr(3,5);
                            newitem+=remark1;
                            if(tempjob.substr(0,1)=='0'){
                                tempjob = tempjob.substr(1,4);
                            }
                            let newjob = oldyear+'-'+tempjob+'-'+newitem;
                            document.getElementById('splitlabel1_newreff').readOnly=true;
                            document.getElementById('splitlabel1_newjob').value=newjob;
                            document.getElementById('splitlabel1_newitemcd').value=newitem;
                            document.getElementById('splitlabel1_newqty').value=newqty;
                            document.getElementById('splitlabel1_newreff2').focus();
                        } else {
                            alertify.warning(response.status[0].msg);
                            document.getElementById('splitlabel1_newreff').value='';
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
    function splitlabel1_e_clearinput(){
        document.getElementById('splitlabel1_oldreff').value='';
        document.getElementById('splitlabel1_txt_itemcode').value='';
        document.getElementById('splitlabel1_txt_lotno').value='';
        document.getElementById('splitlabel1_txt_qty').value='';
        document.getElementById('splitlabel1_txt_newmodel_ka').value='';
        document.getElementById("splitlabel1_txt_newmodel").value='';
        document.getElementById('splitlabel1_oldjob').value='';
        document.getElementById('splitlabel1_olditemcd').value='';
        document.getElementById('splitlabel1_oldqty').value='';
        document.getElementById('splitlabel1_newreff').value='';
        document.getElementById('splitlabel1_newjob').value='';
        document.getElementById('splitlabel1_newqty').value='';
        document.getElementById('splitlabel1_newreff2').value='';
        document.getElementById('splitlabel1_newjob2').value='';
        document.getElementById('splitlabel1_newqty2').value='';
        document.getElementById('splitlabel1_rawtxt1').value='';
        document.getElementById('splitlabel1_rawtxt2').value='';
        $("#splitlabel1_tbl tbody").empty();
    }
    function splitlabel1_e_clearinput_to(){
        document.getElementById('splitlabel1_txt_itemcode').value='';
        document.getElementById('splitlabel1_txt_lotno').value='';
        document.getElementById('splitlabel1_txt_qty').value='';
        document.getElementById('splitlabel1_txt_code').value='';             
        document.getElementById('splitlabel1_newreff').value='';
        document.getElementById('splitlabel1_newjob').value='';
        document.getElementById('splitlabel1_newqty').value='';
        document.getElementById('splitlabel1_newreff2').value='';
        document.getElementById('splitlabel1_newjob2').value='';
        document.getElementById('splitlabel1_newqty2').value='';
        
    }
    $("input[name='splitlabel1_typefg']").change(function(){        
        let curv = $(this).val();
        splitlabel1_e_clearinput_to();
        if(curv=='KD' || curv=='ASP'){
            $("#splitlabel1_div_ka").removeClass("d-none");
            $("#splitlabel1_div_rnm").addClass("d-none");
            document.getElementById("splitlabel1_txt_itemcode").focus();
            document.getElementById("splitlabel1_txt_itemcode").readOnly=false;
            document.getElementById("splitlabel1_txt_lotno").readOnly=false;
            document.getElementById("splitlabel1_txt_qty").readOnly=false;
            document.getElementById("splitlabel1_txt_code").readOnly=false;            
        } else if (curv=='') {
            $("#splitlabel1_div_ka").addClass("d-none");
            $("#splitlabel1_div_rnm").removeClass("d-none");
            document.getElementById('splitlabel1_newreff').focus();
            document.getElementById('splitlabel1_newreff').readOnly=false;
        }
    });
    $("input[name='splitlabel1_goorno']").change(function(){
        document.getElementById('splitlabel1_newreff2').focus();
    });
    $("#splitlabel1_newreff2").keypress(function (e) { 
        if(e.which==13){
            let nreff = $(this).val();
            let rawtext2 = nreff;
            let oldreff = document.getElementById('splitlabel1_oldreff').value;            
            let olditem = document.getElementById('splitlabel1_olditemcd').value;
            let oldqty = document.getElementById('splitlabel1_oldqty').value;
            let oldjob = document.getElementById('splitlabel1_oldjob').value.trim();
            let oldyear = oldjob.substr(0,2);     
            let nreff1 = document.getElementById('splitlabel1_newreff').value;   
            let nqty1 = document.getElementById('splitlabel1_newqty').value;   
            let nreffka = document.getElementById('splitlabel1_txt_code').value;
            let nqtyka = document.getElementById('splitlabel1_txt_qty').value;
            let nmodel = document.getElementById('splitlabel1_txt_newmodel2').value;
            oldqty = numeral(oldqty).value();
            nqty1 = numeral(nqty1).value();
            
            if(nreff.includes("|")){
                document.getElementById('splitlabel1_rawtxt2').value=nreff;
                let ar = nreff.split("|");
                let newitem = ar[0].substr(2,ar[0].length-2);
                let newqty = ar[3].substr(2,ar[3].length-2);
                let thelot = ar[2].substr(2,ar[2].length-2);
                let tempjob = thelot.substr(3,5);
                newitem+=nmodel;
                if(tempjob.substr(0,1)=='0'){
                    tempjob = tempjob.substr(1,4);
                }
                let newjob = oldyear+'-'+tempjob+'-'+newitem;
                nreff = ar[5].substr(2,ar[5].length-2);
                if(nreff==oldreff){
                    alertify.warning('could not transfer to the same reff no');
                    $(this).val('');
                    return;
                }     
                ///#1 check item code
                if( newitem!=olditem ){
                    alertify.warning('New Item is not same with old item, please compare the label !, '+newitem + ' != ' + olditem);
                    $(this).val('');
                    return;
                }
                ///# check qty and reff no
                if(newqty> oldqty){
                    alertify.warning('old qty > new qty, please check the label again');
                    $(this).val('');
                    return;
                }
                if(newqty== oldqty){
                    alertify.warning('could not split because qty is same');
                    $(this).val('');
                    return;
                }
                if(nreffka==''){ //IF REGULAR
                    if ( (newqty*1 + nqty1*1) != oldqty ){
                        alertify.warning('total new qty != oldqty '+ (newqty*1 + nqty1*1) + ' != '+ oldqty);
                        $(this).val('');
                        return;
                    }
                    if(nreff==nreff1){
                        alertify.warning('reff no is same with the new one');
                        $(this).val('');
                        return;
                    }
                } else { //if KD ASP
                    if ( (newqty*1 + nqtyka*1) != oldqty ){
                        alertify.warning('total new qty != oldqty '+ (newqty*1 + nqtyka*1) + ' != '+ oldqty);
                        $(this).val('');
                        return;
                    }
                    if(nreff==nreffka){
                        alertify.warning('reff no is same with the new one');
                        $(this).val('');
                        return;
                    }
                }

                ///# check job
                if(oldjob!=newjob){
                    alertify.warning('job is not same, please check the label again '+ oldjob + ' != ' + newjob);
                    $(this).val('');
                    return;
                }
                           
                $(this).val(nreff);
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SER/validate_newreff2')?>",
                    data: {inrawtext2: rawtext2},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd!='0'){
                            let nmodel = document.getElementById('splitlabel1_txt_newmodel2').value;
                            let newreff = response.status[0].rawtext;
                            let ar = newreff.split("|");
                            let newitem = ar[0].substr(2,ar[0].length-2);
                            let newqty = ar[3].substr(2,ar[3].length-2);
                            let thelot = ar[2].substr(2,ar[2].length-2);
                            let tempjob = thelot.substr(3,5);
                            if(tempjob.substr(0,1)=='0'){
                                tempjob = tempjob.substr(1,4);
                            }
                            newitem+=nmodel;
                            let newjob = oldyear+'-'+tempjob+'-'+newitem;
                            document.getElementById('splitlabel1_newreff2').readOnly=true;
                            document.getElementById('splitlabel1_newjob2').value=newjob;
                            document.getElementById('splitlabel1_newitemcd2').value=newitem;
                            document.getElementById('splitlabel1_newqty2').value=newqty;
                            document.getElementById('splitlabel1_btn_save').focus();
                        } else {
                            alertify.warning(response.status[0].msg);
                            document.getElementById('splitlabel1_newreff2').value='';
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
    $("#splitlabel1_btn_save").click(function (e) {             
        let oldreff = document.getElementById('splitlabel1_oldreff').value;
        let olditem = document.getElementById('splitlabel1_olditemcd').value;
        let oldqty = document.getElementById('splitlabel1_oldqty').value;
        let oldjob = document.getElementById('splitlabel1_oldjob').value.trim();
        let oldyear = oldjob.substr(0,2);
        let nreff1 = document.getElementById('splitlabel1_newreff').value;
        let nqty1 = document.getElementById('splitlabel1_newqty').value;
        let rawtext1 = document.getElementById('splitlabel1_rawtxt1').value;
        let rmrk1 = document.getElementById('splitlabel1_txt_newmodel').value;

        let nreff2 = document.getElementById('splitlabel1_newreff2').value;
        let nqty2 = document.getElementById('splitlabel1_newqty2').value;
        let rawtext2 = document.getElementById('splitlabel1_rawtxt2').value;
        let rmrk2 = document.getElementById('splitlabel1_txt_newmodel2').value;
        
        let itemcd = document.getElementById('splitlabel1_txt_itemcode').value;
        let lotno = document.getElementById('splitlabel1_txt_lotno').value;
        let qty = document.getElementById('splitlabel1_txt_qty').value;
        let reffcodeka = document.getElementById('splitlabel1_txt_code').value;
        let remarkka = document.getElementById('splitlabel1_txt_newmodel_ka').value;
        let mtypefg = $("input[name='splitlabel1_typefg']:checked").val();
        let mtypegoor = $("input[name='splitlabel1_goorno']:checked").val();
        if(olditem.trim()==''){
            alertify.warning('Please entry old reff no !');
            return;
        }
        if(document.getElementById('splitlabel1_oldreff').readOnly 
        && (document.getElementById('splitlabel1_newreff').readOnly || document.getElementById('splitlabel1_txt_code').readOnly)
        && document.getElementById('splitlabel1_newreff2').readOnly
         ) 
        {
            nqty1 = numeral(nqty1).value();
            nqty2 = numeral(nqty2).value();
            let konfirm = confirm('Are You sure ?');
            if(!konfirm){
                alertify.message('You are not sure ...');
                return;
            }
            $.ajax({
                type: "post",
                url: "SER/validate_newreffall",
                data: {inoldreff :oldreff, inolditem:olditem , inoldqty:oldqty, inoldjob: oldjob,
                  innewreff1 : nreff1, innewqty1 : nqty1, inraw1 :rawtext1, inrmrk1: rmrk1
                  ,innewreff2 : nreff2, innewqty2 : nqty2, inraw2 :rawtext2, inrmrk2: rmrk2
                  , intypeg : mtypegoor,
                  intypefg: mtypefg,initemcd : itemcd,inlot: lotno, inqty: qty, inreffcdka: reffcodeka,inremarkka : remarkka},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){
                        alertify.success(response.status[0].msg);
                        splitlabel1_e_clearinput();
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {

        }
    });
    $("#splitlabel1_txt_itemcode").keypress(function (e) { 
        if(e.which==13){
            let itemcd = $(this).val();
            let olditem = document.getElementById('splitlabel1_olditemcd').value;
            let remark1 = document.getElementById('splitlabel1_txt_newmodel_ka').value;
            itemcd += remark1;
            if(itemcd.trim()==''){
                alertify.warning('Item Code could not be empty');                
                return;
            }
            if(itemcd!=olditem){
                alertify.warning('Item is not same');
                $(this).val('');
                return;
            }
            $.ajax({
                type: "get",
                url: "<?=base_url("MSTITM/checkexist")?>",
                data: {initem: itemcd},
                dataType: "json",
                success: function (response) {
                    if(response.data[0].cd=="11"){                                 
                        document.getElementById("splitlabel1_txt_lotno").focus();
                        document.getElementById('splitlabel1_txt_itemcode').readOnly=true;
                    } else {
                        document.getElementById("splitlabel1_txt_itemcode").value="";
                        alertify.warning(response.data[0].msg);
                    }
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#splitlabel1_txt_lotno").keypress(function (e) { 
        if(e.which==13){
            let cval = $(this).val();
            if(cval.trim()==""){
                alertify.warning("Please enter lot no");
            } else {
                let newitem = document.getElementById('splitlabel1_txt_itemcode').value;
                let oldjob = document.getElementById('splitlabel1_oldjob').value.trim();
                let rmrk = document.getElementById('splitlabel1_txt_newmodel_ka').value;
                let oldyear = oldjob.substr(0,2);
                newitem+=rmrk;
                if(cval.substr(0,3)=='070'){
                    let tempjob = cval.substr(3,5);
                    if(tempjob.substr(0,1)=='0'){
                        tempjob = tempjob.substr(1,4);
                    }
                    let newjob = oldyear+'-'+tempjob+'-'+newitem;
                    if(oldjob!=newjob){
                        alertify.warning('Job is not same, please check lot number on the label !');
                        $(this).val('');
                        return;
                    }
                    document.getElementById("splitlabel1_txt_qty").focus();
                    document.getElementById("splitlabel1_txt_lotno").readOnly=true;
                } else {
                    alertify.warning('Lot Number is not valid');
                    $(this).val('');
                }                
            }
        }
    });
    $("#splitlabel1_txt_qty").keypress(function (e) { 
        if(e.which==13){
            let cval = $(this).val();
            let oldqty = document.getElementById('splitlabel1_oldqty').value;
            if(cval.trim()==''){
                alertify.warning("Please enter valid number !");
                $(this).val('');
                return;
            } else {
                if(cval.length<5){
                    cval = numeral(cval).value();
                    oldqty = numeral(oldqty).value();
                    if(cval>0 ){
                        if(cval > oldqty){
                            document.getElementById("splitlabel1_txt_qty").value="";
                            alertify.warning('new qty >  old qty !');
                        } else if (cval == oldqty) {
                            alertify.warning('could not split because qty is same');
                            $(this).val('');
                        }
                        else {
                            document.getElementById('splitlabel1_txt_qty').readOnly=true;
                            document.getElementById('splitlabel1_txt_code').focus();
                        }                        
                    } else {
                        document.getElementById("splitlabel1_txt_qty").value="";
                    }
                } else {
                    alertify.warning('QTY is not valid');
                    $(this).val('');
                }                
            }
        }
    });
    $("#splitlabel1_txt_code").keypress(function (e) { 
        if(e.which==13){
            let reff_ka = $(this).val();
            let oldreff = document.getElementById('splitlabel1_oldreff').value;
            if(reff_ka.trim()==''){
                alertify.warning('Reff No could not be empty!');
                return;
            }
            if (reff_ka==oldreff){
                alertify.warning('Reff no could not be same');
                $(this).val('');
                return;
            }
            if(reff_ka.length!=18){
                alertify.warning('Please enter valid Reff No');
                return;
            }
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/validate_newreff')?>",
                data: {inrawtext: reff_ka},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){                            
                        document.getElementById('splitlabel1_newreff2').focus();
                        document.getElementById('splitlabel1_txt_code').readOnly=true;
                    } else {
                        alertify.warning(response.status[0].msg);
                        document.getElementById('splitlabel1_txt_code').value='';
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
</script>