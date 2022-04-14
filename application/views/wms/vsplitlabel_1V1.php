<div style="padding: 5px">
	<div class="container-fluid">  
        <div class="row">
            <div class="col-md-3 mb-1">
                <h3 ><span class="badge bg-info">1 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">From</span></h3>
            </div>            
        </div>      
        <div class="row">   
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Old Reff No</span>                    
                    <input type="text" class="form-control" id="splitlabel1V1_oldreff" required>                   
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                
                    <span class="input-group-text" >Job Number</span>                    
                    <input type="text" class="form-control" id="splitlabel1V1_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="splitlabel1V1_olditemcd" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Qty</span>                    
                    <input type="text" class="form-control" id="splitlabel1V1_oldqty" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h4>Transaction History <span class="fas fa-circle-info" id="splitlabel1V1_lbl_cat"></span></h4>
                <input type="hidden" id="splitlabel1V1_txt_activewh">
                <div class="table-responsive" id="splitlabel1V1_divku">
                    <table id="splitlabel1V1_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th  class="align-middle">FORM</th>
                                <th  class="align-middle">Warehouse</th>
                                <th  class="align-middle">Location</th>
                                <th  class="align-middle">Document</th>
                                <th  class="text-end">Qty</th>                                                              
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
                <h3 ><span class="badge bg-info">2 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">To</span></h3>
            </div>
            <div class="col-md-9 mb-1">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="splitlabel1V1_typefg" id="splitlabel1V1_typereg" value="" checked>
                    <label class="form-check-label" for="splitlabel1V1_typereg">Regular</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="splitlabel1V1_typefg" id="splitlabel1V1_typekd" value="KD">
                    <label class="form-check-label" for="splitlabel1V1_typekd">KD</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="splitlabel1V1_typefg" id="splitlabel1V1_typeasp" value="ASP" >
                    <label class="form-check-label" for="splitlabel1V1_typeasp">ASP</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="splitlabel1V1_typefg" id="splitlabel1V1_typekdasp" value="KDES" >
                    <label class="form-check-label" for="splitlabel1V1_typekdasp">KDES</label>
                </div>
            </div>
        </div>
        <div class="d-none" id="splitlabel1V1_div_ka">
            <div class="row" >
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Remark</span>                        
                        <input type="text" class="form-control" id="splitlabel1V1_txt_newmodel_ka" maxlength="5" required style="text-align: right">                         
                    </div>
                </div>
            </div>
            <div class="row" >
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Item Code</span>                        
                        <input type="text" class="form-control" id="splitlabel1V1_txt_itemcode" maxlength="50" required>                          
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                        
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Lot No</span>                        
                        <input type="text" class="form-control" id="splitlabel1V1_txt_lotno" maxlength=50 required>                          
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                        
                    </div>
                </div>
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Qty</span>                        
                        <input type="text" class="form-control" id="splitlabel1V1_txt_qty" maxlength="5" required style="text-align: right">                          
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                        
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Reff Code</span>                        
                        <input type="text" class="form-control" id="splitlabel1V1_txt_code" maxlength="500" >
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                    
                    </div>
                </div>
            </div>
            
        </div>
        <div id="splitlabel1V1_div_rnm">
            <div class="row" >               
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Remark</span>                        
                        <input type="text" class="form-control" id="splitlabel1V1_txt_newmodel" maxlength="5" required style="text-align: right">                          
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >New Reff No</span>                        
                        <input type="text" class="form-control" id="splitlabel1V1_newreff" required>                   
                    </div>
                </div>
                <div class="col-md-4 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Job Number</span>                        
                        <input type="text" class="form-control" id="splitlabel1V1_newjob" required readonly>
                    </div>
                </div>
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Item Code</span>
                        <input type="text" class="form-control" id="splitlabel1V1_newitemcd" required readonly>
                    </div>
                </div>
                <div class="col-md-2 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Qty</span>                        
                        <input type="text" class="form-control" id="splitlabel1V1_newqty" required readonly>
                    </div>
                </div>   
                <input type="hidden" id="splitlabel1V1_rawtxt1">
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <h4>Detail Split</h4>                
                <table id="splitlabel1V1_tbl_to" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                    <thead class="table-light">
                        <tr>                               
                            <th  class="align-middle">Reff No</th>
                            <th  class="align-middle">KD/ASP</th>
                            <th  class="align-middle">Assy Code</th>
                            <th  class="align-middle">Lot Number</th>
                            <th  class="text-end">QTY</th>
                            <th  class="text-center">Good ?</th>                                                              
                            <th  class="align-middle">Reason</th>
                            <th  class="d-none">RAWTEXT</th>
                            <th  class="align-middle">...</th>
                        </tr>                      
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end">Total</td>
                            <td class="text-end"><span id="splitlabel1V1_spn_total"></span></td>
                            <td colspan="4"></td>
                        </tr>                        
                    </tfoot>
                </table>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="splitlabel1V1_btn_new"><i class="fas fa-file"></i></button>                    
                    <button title="Save" type="button" class="btn btn-outline-primary" id="splitlabel1V1_btn_save"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>        
    </div>
</div>
<script>
     function splitlabel1V1_e_clearinput_to(){
        document.getElementById('splitlabel1V1_txt_itemcode').value='';
        document.getElementById('splitlabel1V1_txt_lotno').value='';
        document.getElementById('splitlabel1V1_txt_qty').value='';
        document.getElementById('splitlabel1V1_txt_code').value='';             
        document.getElementById('splitlabel1V1_newreff').value='';
        document.getElementById('splitlabel1V1_newjob').value='';
        document.getElementById('splitlabel1V1_newqty').value='';        
    }
    $("#splitlabel1V1_txt_newmodel").keypress(function (e) { 
        if(e.which==13){
            document.getElementById('splitlabel1V1_newreff').focus();
        }
    });

    function splitlabel1V1_e_checktempkeys(reffNo){
        let mtbl = document.getElementById('splitlabel1V1_tbl_to');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let isok = true;
        for(let i=0;i<ttlrows;i++){                    
            if(tableku2.rows[i].cells[0].innerText==reffNo){
                isok = false; break;
            }
        }
        return isok;
    }
    function splitlabel1V1_e_checkqtytemp(pqty){
        let maxqty =  numeral(document.getElementById('splitlabel1V1_oldqty').value).value();
        let mtbl = document.getElementById('splitlabel1V1_tbl_to');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
       
        let qty = 0;
        for(let i=0;i<ttlrows;i++){ 
            qty += numeral(tableku2.rows[i].cells[4].innerText).value();                        
        }
        qty += numeral(pqty).value();
        
        let isok = (qty <= maxqty) ? true : false;
        return isok;
    }
    function splitlabel1V1_e_deleterow(pindex){
        let konfr = confirm('Are You sure ?');
        if(konfr){
            let mtbl = document.getElementById('splitlabel1V1_tbl_to');
            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
            tableku2.deleteRow(pindex);
            let mrows = tableku2.getElementsByTagName("tr");    
            let ttlqty = 0;        
            for(let x=0;x<mrows.length;x++){
                tableku2.rows[x].cells[8].onclick = function(){splitlabel1V1_e_deleterow(x)};  
                ttlqty += numeral(tableku2.rows[x].cells[4].innerText).value();          
            }
            document.getElementById('splitlabel1V1_spn_total').innerText = numeral(ttlqty).format(',');
        }
    }
    $("#splitlabel1V1_newreff").keypress(function (e) { 
        if(e.which==13){
            let nreff = $(this).val();
            let rawtext = nreff;
            let oldreff = document.getElementById('splitlabel1V1_oldreff').value;
            let olditem = document.getElementById('splitlabel1V1_olditemcd').value.toUpperCase();            
            let oldjob = document.getElementById('splitlabel1V1_oldjob').value.trim();
            let oldyear = oldjob.substr(0,2);
            let remark1 = document.getElementById('splitlabel1V1_txt_newmodel').value;
            let aoldjob = oldjob.split('-');            
            let tempoldjob = aoldjob[1];
            if(!tempoldjob){
                tempoldjob = oldjob.substr(3,5);
                if(tempoldjob.substr(0,1)=='0'){
                    tempoldjob = tempoldjob.substr(1,4);
                }
            }
            
            if(remark1.includes("KD")){ ///user must select radio 'fgexport type'
                alertify.warning('Please do not entry KD here');
                document.getElementById('splitlabel1V1_txt_newmodel').focus();
                return;
            }
            if(remark1.includes("ASP")){ ///user must select radio 'fgexport type'
                alertify.warning('Please do not entry ASP here');
                document.getElementById('splitlabel1V1_txt_newmodel').focus();
                return;
            }
            if(olditem.toUpperCase().includes('ES')){

            } else {
                
                if(remark1.includes("ES")){
                    alertify.warning('Could not split from current label to ES');
                    document.getElementById('splitlabel1V1_txt_newmodel').value='';
                    document.getElementById('splitlabel1V1_newreff').value='';
                    document.getElementById('splitlabel1V1_txt_newmodel').focus();
                    return;
                }
            }
            if(nreff.includes("|")){
                document.getElementById('splitlabel1V1_rawtxt1').value=nreff;                
                let ar = nreff.split("|");
                let newitem = ar[0].substr(2,ar[0].length-2);
                let newqty = ar[3].substr(2,ar[3].length-2);
                let thelot = ar[2].substr(2,ar[2].length-2);
                let tempjob = thelot.substr(3,5);
                nreff = ar[5].substr(2,ar[5].length-2);

                if(!splitlabel1V1_e_checktempkeys(nreff)){
                    alertify.warning('The label is already added');
                    document.getElementById('splitlabel1V1_newreff').value='';
                    return;
                }
                if(!splitlabel1V1_e_checkqtytemp(newqty)){
                    alertify.warning('Total Qty > Old QTY !');
                    document.getElementById('splitlabel1V1_newreff').value='';
                    return;
                }

                if(tempjob.substr(0,1)=='0'){
                    tempjob = tempjob.substr(1,4);
                }  else if( tempjob.substr(0,1)=='C'){
                    alertify.warning('Please split on dedicated menu !');
                    return;
                }
                
                ///#1 check item code
                if( newitem!=olditem.substr(0,9) ){
                    if( newitem!=olditem.substr(0,10) ){
                        alertify.warning('New Item is not same with old item, please compare the label !');                        
                        $(this).val('');
                        return;
                    }
                }
                
                ///# check job
                if(tempoldjob.toUpperCase()!=tempjob.toUpperCase()){
                    alertify.warning('job is not same, please check the label again '+ tempoldjob + ' != ' + tempjob);
                    $(this).val('');
                    return;
                }
                
                if(nreff==oldreff){
                    alertify.warning('could not transfer to the same reff no');
                    $(this).val('');
                    return;
                }
                $(this).val(nreff);
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SER/validate_newreff')?>",
                    data: {inrawtext: rawtext, inremark:remark1 },
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd!='0'){
                            let _activewh = document.getElementById('splitlabel1V1_txt_activewh').value;                            
                            let remark1 = document.getElementById('splitlabel1V1_txt_newmodel').value;
                            let newreff = response.status[0].rawtext;
                            let ar = newreff.split("|");
                            let refno = ar[5].substr(2,ar[5].length-2);
                            let newitem = ar[0].substr(2,ar[0].length-2);
                            let newqty = ar[3].substr(2,ar[3].length-2);
                            let thelot = ar[2].substr(2,ar[2].length-2);                            
                            let mtbl = document.getElementById('splitlabel1V1_tbl_to');
                            let tableku2 = mtbl.getElementsByTagName("tbody")[0];                            
                            let newrow, newcell, newText;

                            newrow = tableku2.insertRow(-1);                                    
                            newcell = newrow.insertCell(0);
                            newText = document.createTextNode(refno);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode('');
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(newitem+remark1);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);
                            newText = document.createTextNode(thelot);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(4);
                            newText = document.createTextNode(numeral(newqty).value());
                            newcell.style.cssText = 'text-align: right;';
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(5);
                            newText = document.createElement("input");
                            newText.setAttribute('type', 'checkbox');
                            newText.setAttribute('checked', true);
                            if(_activewh=='AFWH3'){
                                newText.setAttribute('disabled', true );

                            } else {

                            }
                            

                            newcell.style.cssText = 'text-align: center;';
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(6);
                            // newText = document.createTextNode(remark1);                            
                            newcell.innerHTML = `<select class="form-select form-select-sm" >
                        <option value="-">-</option>
                        <option value="SCRAP">SCRAP</option>
                        <option value="WAITING QA CONFIRMATION">WAITING QA CONFIRMATION</option>
                        <option value="WAITING REPAIR">WAITING REPAIR</option>
                    </select>`
                            newcell = newrow.insertCell(7);
                            newText = document.createTextNode(newreff);                            
                            newcell.style.cssText = 'display:none;';
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(8);
                            newText = document.createElement('I');
                            newText.classList.add("fas", "fa-trash","text-danger");                           
                            newcell.style.cssText = 'text-align: center;cursor:pointer;';
                            newcell.appendChild(newText);
                            

                            document.getElementById('splitlabel1V1_newreff').value='';
                            document.getElementById('splitlabel1V1_newjob').value='';
                            document.getElementById('splitlabel1V1_newitemcd').value='';
                            document.getElementById('splitlabel1V1_newqty').value='';
                            

                            let mrows = tableku2.getElementsByTagName("tr");
                            let ttlqty = 0;
                            for(let x=0;x<mrows.length;x++){
                                tableku2.rows[x].cells[8].onclick = function(){splitlabel1V1_e_deleterow(x)};
                                ttlqty += numeral(tableku2.rows[x].cells[4].innerText).value();
                            }
                            document.getElementById('splitlabel1V1_spn_total').innerText = numeral(ttlqty).format(',');
                        } else {
                            alertify.warning(response.status[0].msg);
                            document.getElementById('splitlabel1V1_newreff').value='';
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
    $("input[name='splitlabel1V1_typefg']").change(function(){        
        let curv = $(this).val();
        splitlabel1V1_e_clearinput_to();
        if(curv=='KD' || curv=='ASP' || curv=='KDES'){
            $("#splitlabel1V1_div_ka").removeClass("d-none");
            $("#splitlabel1V1_div_rnm").addClass("d-none");
            document.getElementById("splitlabel1V1_txt_code").focus();
            document.getElementById("splitlabel1V1_txt_itemcode").readOnly=false;
            document.getElementById("splitlabel1V1_txt_lotno").readOnly=false;
            document.getElementById("splitlabel1V1_txt_qty").readOnly=false;
            document.getElementById("splitlabel1V1_txt_code").readOnly=false;            
        } else if (curv=='') {
            $("#splitlabel1V1_div_ka").addClass("d-none");
            $("#splitlabel1V1_div_rnm").removeClass("d-none");
            document.getElementById('splitlabel1V1_newreff').focus();
            document.getElementById('splitlabel1V1_newreff').readOnly=false;
        }
    });

    $("#splitlabel1V1_txt_newmodel_ka").keypress(function (e) { 
        if(e.which==13){
            document.getElementById('splitlabel1V1_txt_itemcode').focus();
        }
    });
    $("#splitlabel1V1_txt_itemcode").keypress(function (e) { 
        if(e.which==13){
            let olditem = document.getElementById('splitlabel1V1_olditemcd').value.toUpperCase();
            let newitem = document.getElementById('splitlabel1V1_txt_itemcode').value;
            let remarkka = document.getElementById('splitlabel1V1_txt_newmodel_ka').value;
            if(remarkka.includes("KD")){ ///user must select radio 'fgexport type'
                alertify.warning('Please do not entry KD here');
                document.getElementById('splitlabel1V1_txt_newmodel_ka').focus();
                return;
            }
            if(remarkka.includes("ASP")){ ///user must select radio 'fgexport type'
                alertify.warning('Please do not entry ASP here');
                document.getElementById('splitlabel1V1_txt_newmodel_ka').focus();
                return;
            }
            if(olditem.includes('ES')){
                document.getElementById('splitlabel1V1_txt_lotno').focus();  
            } else {
                
                if(remarkka.includes("ES")){
                    alertify.warning('Could not split from current label to ES');
                    document.getElementById('splitlabel1V1_txt_itemcode').value='';
                    document.getElementById('splitlabel1V1_txt_newmodel_ka').value='';
                    document.getElementById('splitlabel1V1_txt_newmodel_ka').focus();
                    return;
                }

                ///#1 check item code
                if( newitem!=olditem.substr(0,9) ){
                    alertify.warning('New Item is not same with old item, please compare the label !');
                    document.getElementById('splitlabel1V1_txt_itemcode').value='';
                    return;
                }
                document.getElementById('splitlabel1V1_txt_lotno').focus();                
            }
        }
    });

    $("#splitlabel1V1_txt_lotno").keypress(function (e) { 
        if(e.which==13){
            let cval = $(this).val();
            if(cval.trim()==""){
                alertify.warning("Please enter lot no");
            } else {                
                let oldjob = document.getElementById('splitlabel1V1_oldjob').value.trim();                                
                let aoldjob = oldjob.split('-');
                let tempoldjob = aoldjob[1];
                if(cval.substr(0,3)=='070'){
                    let tempjob = cval.substr(3,5);
                    if(tempjob.substr(0,1)=='0'){
                        tempjob = tempjob.substr(1,4);
                    }                    
                    if(tempoldjob.toUpperCase()!=tempjob.toUpperCase()){
                        alertify.warning('Job is not same, please check lot number on the label !');
                        $(this).val('');
                        return;
                    }
                    document.getElementById("splitlabel1V1_txt_qty").focus();                    
                } else {
                    alertify.warning('Lot Number is not valid');
                    $(this).val('');
                }                
            }
        }
    });

    $("#splitlabel1V1_txt_qty").keypress(function (e) { 
        if(e.which==13){
            let cval = $(this).val();            
            if(cval.trim()==''){
                alertify.warning("Please enter valid number !");
                $(this).val('');
                return;
            } else {
                if(cval.length<5){
                    cval = numeral(cval).value();                    
                    if(cval>0 ){
                        if(!splitlabel1V1_e_checkqtytemp(cval)){
                            alertify.warning('new qty >  old qty !');
                            return;
                        }                                                                        
                        document.getElementById('splitlabel1V1_txt_code').focus();                        
                    } else {
                        document.getElementById("splitlabel1V1_txt_qty").value="";
                    }
                } else {
                    alertify.warning('QTY is not valid');
                    document.getElementById("splitlabel1V1_txt_qty").value="";
                }                
            }
        }
    });

    $("#splitlabel1V1_txt_code").keypress(function (e) { 
        if(e.which==13){
            //#1 validateitemcode
            let kurkey = document.getElementById('splitlabel1V1_txt_code').value
            if(kurkey.includes('|')){
                const akurkey = kurkey.split("|")
                document.getElementById('splitlabel1V1_txt_itemcode').value = akurkey[0].substr(2,9)
                document.getElementById('splitlabel1V1_txt_lotno').value = akurkey[2].substr(2,akurkey[2].length)
                document.getElementById('splitlabel1V1_txt_qty').value = akurkey[3].substr(2,akurkey[3].length)
                document.getElementById('splitlabel1V1_txt_code').value = akurkey[5].substr(2,akurkey[5].length)
            }

            let olditem = document.getElementById('splitlabel1V1_olditemcd').value.toUpperCase();
            let newitem = document.getElementById('splitlabel1V1_txt_itemcode').value;
            let remarkka = document.getElementById('splitlabel1V1_txt_newmodel_ka').value;
            if(remarkka.includes("KD")){ ///user must select radio 'fgexport type'
                alertify.warning('Please do not entry KD here');
                document.getElementById('splitlabel1V1_txt_newmodel_ka').focus();
                return;
            }
            if(remarkka.includes("ASP")){ ///user must select radio 'fgexport type'
                alertify.warning('Please do not entry ASP here');
                document.getElementById('splitlabel1V1_txt_newmodel_ka').focus();
                return;
            }
            if(olditem.includes('ES')){

            } else {
                
                if(remarkka.includes("ES")){
                    alertify.warning('Could not split from current label to ES');
                    document.getElementById('splitlabel1V1_txt_itemcode').value='';
                    document.getElementById('splitlabel1V1_txt_newmodel_ka').value='';
                    document.getElementById('splitlabel1V1_txt_newmodel_ka').focus();
                    return;
                }

                ///#1 check item code
                if( newitem!=olditem.substr(0,9) ){
                    alertify.warning('New Item is not same with old item, please compare the label !');
                    console.log(newitem+"!="+olditem.substr(0,9))
                    document.getElementById('splitlabel1V1_txt_itemcode').value='';
                    return;
                }                
            }
            //#1 END

            //#2 validatejob
            let clot = $('#splitlabel1V1_txt_lotno').val();
            if(clot.trim()==""){
                alertify.warning("Please enter lot no");
            } else {                
                let oldjob = document.getElementById('splitlabel1V1_oldjob').value.trim();                                
                let aoldjob = oldjob.split('-');
                let tempoldjob = aoldjob[1].toUpperCase()
                if(clot.substr(0,3)=='070'){
                    let tempjob = clot.substr(3,5);
                    if(tempjob.substr(0,1)=='0'){
                        tempjob = tempjob.substr(1,4).toUpperCase()
                    }                    
                    if(tempoldjob!=tempjob){
                        alertify.warning('Job is not same, please check lot number on the label !');
                        $('#splitlabel1V1_txt_lotno').val('');
                        return;
                    }
                    document.getElementById("splitlabel1V1_txt_qty").focus();                    
                } else {
                    alertify.warning('Lot Number is not valid');
                    $('#splitlabel1V1_txt_lotno').val('');
                }                
            }
            //#2 END

            //#3 validateqty
            let cqty = $('#splitlabel1V1_txt_qty').val();            
            if(cqty.trim()==''){
                alertify.warning("Please enter valid number !");
                $('#splitlabel1V1_txt_qty').val('');
                return;
            } else {
                if(cqty.length<5){
                    cqty = numeral(cqty).value();                    
                    if(cqty>0 ){
                        if(!splitlabel1V1_e_checkqtytemp(cqty)){
                            alertify.warning('new qty >  old qty !');
                            return;
                        }                                                                        
                        document.getElementById('splitlabel1V1_txt_code').focus();                        
                    } else {
                        document.getElementById("splitlabel1V1_txt_qty").value="";
                    }
                } else {
                    alertify.warning('QTY is not valid');
                    document.getElementById("splitlabel1V1_txt_qty").value="";
                }                
            }
            //#3 END


            //#4 validatereff
            let reff_ka = $(this).val();
            if(!splitlabel1V1_e_checktempkeys(reff_ka)){
                alertify.warning('The label is already added');
                document.getElementById('splitlabel1V1_txt_code').value='';
                return;
            }
            let oldreff = document.getElementById('splitlabel1V1_oldreff').value;
            if(reff_ka.trim()==''){
                alertify.warning('Reff No could not be empty!');
                return;
            }
            if (reff_ka==oldreff){
                alertify.warning('Reff no could not be same');
                $(this).val('');
                return;
            }
            // if(reff_ka.length!=18){
            if(reff_ka.length!=16){
                alertify.warning('Please enter valid Reff No');
                return;
            }
            //#4 END


            let mtypefg = $("input[name='splitlabel1V1_typefg']:checked").val();
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/validate_newreff')?>",
                data: {inrawtext: reff_ka, initemcd : newitem ,intypefg : mtypefg, inremark : remarkka, inlot: clot , inqty: cqty},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){ 
                        let _activewh = document.getElementById('splitlabel1V1_txt_activewh').value;       
                        let mtbl = document.getElementById('splitlabel1V1_tbl_to');
                        let tableku2 = mtbl.getElementsByTagName("tbody")[0];                            
                        let newrow, newcell, newText;
                        
                        newrow = tableku2.insertRow(-1);                                    
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response.status[0].reffno);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.status[0].typefg);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.status[0].assycode);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.status[0].lot.toUpperCase())
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(numeral(response.status[0].qty).value());
                        newcell.style.cssText = 'text-align: right;';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(5);
                        newText = document.createElement("input");
                        newText.setAttribute('type', 'checkbox');
                        newText.setAttribute('checked', true);
                        newText.setAttribute('disabled', _activewh=='AFWH3' ? true: false );
                        newcell.style.cssText = 'text-align: center;';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(6);
                        // newText = document.createTextNode(response.status[0].remark);                            
                        newcell.innerHTML = `<select class="form-select form-select-sm">
                        <option value="-">-</option>
                        <option value="SCRAP">SCRAP</option>
                        <option value="WAITING QA CONFIRMATION">WAITING QA CONFIRMATION</option>
                        <option value="WAITING REPAIR">WAITING REPAIR</option>
                    </select>`
                        newcell = newrow.insertCell(7);
                        newText = document.createTextNode('');                            
                        newcell.style.cssText = 'display:none;';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(8);
                        newText = document.createElement('I');
                        newText.classList.add("fas", "fa-trash","text-danger");                           
                        newcell.style.cssText = 'text-align: center;cursor:pointer;';
                        newcell.appendChild(newText);

                        document.getElementById('splitlabel1V1_txt_code').focus();
                        document.getElementById('splitlabel1V1_txt_itemcode').value='';
                        document.getElementById('splitlabel1V1_txt_lotno').value='';
                        document.getElementById('splitlabel1V1_txt_qty').value='';
                        document.getElementById('splitlabel1V1_txt_code').value='';                        

                        let mrows = tableku2.getElementsByTagName("tr");
                        let ttlqty = 0;
                        for(let x=0;x<mrows.length;x++){
                            tableku2.rows[x].cells[8].onclick = function(){splitlabel1V1_e_deleterow(x)};
                            ttlqty += numeral(tableku2.rows[x].cells[4].innerText).value();
                        }
                        document.getElementById('splitlabel1V1_spn_total').innerText = numeral(ttlqty).format(',');
                    } else {
                        alertify.warning(response.status[0].msg);
                        document.getElementById('splitlabel1V1_txt_code').value='';
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });

    $("#splitlabel1V1_btn_new").click(function (e) {         
        splitlabel1V1_e_new();
    });
    $("#splitlabel1V1_oldreff").keypress(function (e) { 
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
            document.getElementById('splitlabel1V1_oldreff').readOnly=true
            const lblcat = document.getElementById('splitlabel1V1_lbl_cat')
            lblcat.classList.remove(...lblcat.classList)
            lblcat.classList.add('fas','fa-circle-info')
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/getproperties_n_tx_splitplant1')?>",
                data: {inid:moldreff },
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='1'){
                        let mwh = '??';
                        if ( typeof response.lastwh[0] !== 'undefined'){
                            mwh = response.lastwh[0].ITH_WH;                         
                        }
                        document.getElementById('splitlabel1V1_txt_activewh').value = mwh;
                        if(numeral(response.data[0].SER_QTY).value()<=0){
                            alertify.warning('Could not split 0 qty');
                            return;
                        }
                        document.getElementById('splitlabel1V1_oldjob').value=response.data[0].SER_DOC;
                        document.getElementById('splitlabel1V1_olditemcd').value=response.data[0].SER_ITMID.trim();
                        document.getElementById('splitlabel1V1_oldqty').value= numeral(response.data[0].SER_QTY).format(',');                       
                                                
                        ///load tx history
                        let ttlrows = response.tx.length;
                        let mydes = document.getElementById("splitlabel1V1_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("splitlabel1V1_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("splitlabel1V1_tbl");
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
                        mydes.appendChild(myfrag)
                        if(response.data[0].SER_CAT=='2'){
                            lblcat.classList.add('text-warning')
                        }
                        ///end load                        
                    } else {
                        alertify.message(response.status[0].msg);
                        document.getElementById('splitlabel1V1_oldreff').value='';
                        document.getElementById('splitlabel1V1_oldreff').readOnly=false;
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });

    function splitlabel1V1_e_new(){
        document.getElementById('splitlabel1V1_oldreff').value='';
        document.getElementById('splitlabel1V1_oldjob').value='';
        document.getElementById('splitlabel1V1_olditemcd').value='';
        document.getElementById('splitlabel1V1_oldqty').value='';   
        document.getElementById('splitlabel1V1_txt_newmodel').value='';   
        
        document.getElementById('splitlabel1V1_newreff').value='';   
        document.getElementById('splitlabel1V1_newjob').value='';   
        document.getElementById('splitlabel1V1_newitemcd').value='';   
        document.getElementById('splitlabel1V1_newqty').value='';   
        document.getElementById('splitlabel1V1_rawtxt1').value='';   

        document.getElementById('splitlabel1V1_txt_newmodel_ka').value='';   
        document.getElementById('splitlabel1V1_txt_itemcode').value='';   
        document.getElementById('splitlabel1V1_txt_lotno').value='';   
        document.getElementById('splitlabel1V1_txt_qty').value='';   
        document.getElementById('splitlabel1V1_txt_code').value='';   


        $("#splitlabel1V1_tbl tbody").empty();       
        $("#splitlabel1V1_tbl_to tbody").empty();    
        document.getElementById('splitlabel1V1_spn_total').innerText= '';
        document.getElementById('splitlabel1V1_oldreff').readOnly=false;
        document.getElementById('splitlabel1V1_newreff').readOnly=false;
        document.getElementById('splitlabel1V1_oldreff').focus()

        const lblcat = document.getElementById('splitlabel1V1_lbl_cat')
        lblcat.classList.remove(...lblcat.classList)
        lblcat.classList.add('fas','fa-circle-info')
    }

    $("#splitlabel1V1_btn_save").click(function (e) { 
        let olditem = document.getElementById('splitlabel1V1_olditemcd').value;
        let oldjob = document.getElementById('splitlabel1V1_oldjob').value;
        let oldreff = document.getElementById('splitlabel1V1_oldreff').value;
        let oldqty = document.getElementById('splitlabel1V1_oldqty').value;
        let ttlqty = document.getElementById('splitlabel1V1_spn_total').innerText;
        if(oldreff.trim()==''){
            alertify.warning('Old Reff No could not be empty');
            document.getElementById('splitlabel1V1_oldreff').focus();
            return;
        }

        if(numeral(oldqty).value() == numeral(ttlqty).value()){
            let mtbl = document.getElementById('splitlabel1V1_tbl_to');
            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
            let mtbltr = tableku2.getElementsByTagName('tr');
            let ttlrows = mtbltr.length;
        
            let areff = [];
            let afgtype = [];
            let aitemcd = [];
            let alot = [];
            let aqty = [];
            let aisok = [];
            let aremark = [];
            let arawtxt = [];
            for(let i=0;i<ttlrows;i++){ 
                areff.push(tableku2.rows[i].cells[0].innerText); 
                afgtype.push(tableku2.rows[i].cells[1].innerText); 
                aitemcd.push(tableku2.rows[i].cells[2].innerText);
                alot.push(tableku2.rows[i].cells[3].innerText);
                aqty.push(numeral(tableku2.rows[i].cells[4].innerText).value());
                let cktemp = tableku2.rows[i].cells[5].getElementsByTagName('input')[0];
                const reason = tableku2.rows[i].cells[6].getElementsByTagName('select')[0]
                if(cktemp.checked){
                    aisok.push('1');
                } else {
                    aisok.push('0');                    
                    if(reason.value=='-'){
                        alertify.warning('reason is required')
                        reason.focus()
                        return
                    }
                }
                aremark.push(reason.value);
                arawtxt.push(tableku2.rows[i].cells[7].innerText);
            }
            let konfr = confirm("Are You sure ?");
            if(konfr){
                oldqty = numeral(oldqty).value();
                $.ajax({
                    type: "post",
                    url: "<?=base_url('SER/prc_splitplant1')?>",
                    data: {inoldreff: oldreff, inolditem: olditem, inoldjob: oldjob, inoldqty: oldqty
                    ,ina_reff: areff, ina_fgtype : afgtype, ina_itmcd: aitemcd
                    ,ina_lot: alot, ina_qty: aqty, ina_isok: aisok
                    ,ina_remark: aremark, ina_rawtxt: arawtxt},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd!='0'){
                            alertify.success(response.status[0].msg);
                            splitlabel1V1_e_new();
                        } else {
                            alertify.message(response.status[0].msg);
                        }
                    }, error(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        } else {
            alertify.warning('Total QTY must be same');
        }
        
    });
</script>