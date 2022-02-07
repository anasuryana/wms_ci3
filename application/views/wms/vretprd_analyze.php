<!-- <style type="text/css">
	.tagbox-remove{
		display: none;
	}
</style>
<div style="padding: 10px">
	<div class="container-xxl">       
        <div class="row">				
            <div class="col-md-9 mb-1">
                <div class="input-group input-group-sm mb-1">        
                    <span class="input-group-text" >PSN No.</span>                    
                    <input type="text" class="form-control" id="retanztxt_txno" required>                   
                </div>
            </div>     
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Date</span>                    
                    <input type="text" class="form-control" id="retanztxt_txdate" required readonly>                   
                </div>
            </div>       
        </div>    
        <div class="row">            
            <div class="col-md-12 mb-1">                
                <input type="text" style="width:100%" id="retanztxt_job" readonly>                
            </div>
               
        </div>         
        <div class="row">
            <div class="col-md-6 mb-1">
                
            </div>
            <div class="col-md-6 mb-3">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="retanzbtnnew" class="btn btn-outline-primary" ><i class="fas fa-file"></i></button>                    
                    <button title="Export to MEGA" class="btn btn-outline-success" id="retanzbtn_export" onclick="retanzevent_export()"><i class="fas fa-file-excel"></i></button>                    
                    <button class="btn btn-primary" id="retanz_btnconform">Conform</button>
                </div>                
            </div>   
        </div>           
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="retanzdivku">
                    <table id="retanztbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:75%">
                        <thead class="table-light">
                            <tr>         
                                <th>Item Code</th>           
                                <th>Item Name</th>                                
                                <th>Logical Return Qty</th>
                                <th>Actual Return Qty</th>
                                <th>Discrepancy</th>
                                <th><input type="checkbox" title="check all" class="form-check-input" id="retanzckall"></th>
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
<div class="modal fade" id="RETANZ_DETAILRET">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">              
        <div class="modal-header">
            <h4 class="modal-title">Detail Return</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
                
        <div class="modal-body">  
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">Item Code</label>                        
                        <input type="text" class="form-control" id="retanz_d_item" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">SPT No</label>                        
                        <input type="text" class="form-control" id="retanz_d_itemspt" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">Logical Return</label>                        
                        <input type="text" class="form-control" id="retanz_d_logic" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">Actual Return</label>                        
                        <input type="text" class="form-control" id="retanz_d_actual" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">Discrepancy</label>                        
                        <input type="text" class="form-control" id="retanz_d_discrep" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" id="retanz_btnsave"><i class="fas fa-save"></i></button>                                                
                    </div>
                </div>
            </div>       
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="retanz_d_divku">
                        <table id="retanz_d_tblret" class="table table-hover table-sm table-bordered" style="width:100%;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Machine</th>
                                    <th>Lotno</th>
                                    <th>Qty</th>
                                    <th class="text-center"> <input type="checkbox" class="form-check-input" title="Set checked or unchecked all" id="retanz_ck_print"></th>    
                                    <th>SAVED</th>
                                    <th>Category</th>
                                    <th>Line</th>
                                    <th>FR</th>                                    
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
</div> -->
<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading"><i class="fas fa-info-circle"></i></h4>
                    This menu is available for Desktop only 
                </div>
            </div>
        </div>
    </div>
</div>
<script>  
    // var retanz_tbllength         = 1;
    // var retanz_tblrowindexsel;
    // var retanz_tblcolindexsel;
    // var retanz_e_kdown;

    // $("#retanztxt_txdate").datepicker({
    //     format: 'yyyy-mm-dd',
    //     autoclose:true
    // });
    // $("#retanztxt_txdate").datepicker('update', new Date());
    // retanz_e_kdown = function (e) { 
    //     e = e || window.event;
    //     var keyCode = e.keyCode || e.which,
    //     arrow = {left: 37, up: 38, right: 39, down: 40 };
        
    //     if(e.shiftKey && keyCode==9){            
    //         if(retanz_tblcolindexsel>1){
    //             retanz_tblcolindexsel--;
    //         }
    //     } else{
    //         let befval = document.getElementById('retanz_txttemp').value;
    //         var tables = $("#retanz_d_tblret tbody");
    //         let ttlbaris = tables.find('tr').length;
    //         let mtextColor = '';
    //         switch(keyCode){
    //             case 9:
    //                 tables.find('tr').eq(retanz_tblrowindexsel).find('td').eq(retanz_tblcolindexsel).html(befval);          
    //                 break;
    //             case 13:
    //                 tables.find('tr').eq(retanz_tblrowindexsel).find('td').eq(retanz_tblcolindexsel).html(befval);             
    //                 let mlogic =  document.getElementById('retanz_d_logic').value;
    //                 mlogic = numeral(mlogic).value();
    //                 let newval = befval;
    //                 let ttlqty = 0;
    //                 for(let brs = 0 ;brs<ttlbaris;brs++) {
    //                     let qty = tables.find('tr').eq(brs).find('td').eq(retanz_tblcolindexsel).html();
    //                     ttlqty += numeral(qty).value();
    //                 }
    //                 let mdiscrepancy = Number(ttlqty)-Number(mlogic);
    //                 document.getElementById('retanz_d_actual').value = numeral(ttlqty).format('0,0');
    //                 document.getElementById('retanz_d_discrep').value = numeral(mdiscrepancy).format('0,0');
    //                 mtextColor = (mdiscrepancy < 0 ? 'red' : 'black');
    //                 document.getElementById('retanz_d_discrep').style.cssText += 'color:'+mtextColor;                    
    //                 break;
    //             case arrow.up:
    //                 if(retanz_tblrowindexsel>0 && !e.ctrlKey){    
    //                     tables.find('tr').eq(retanz_tblrowindexsel).find('td').eq(retanz_tblcolindexsel).html(befval);
    //                     let mlogic = document.getElementById('retanz_d_logic').value;
    //                     mlogic = numeral(mlogic).value();
    //                     let newval = befval;
    //                     let ttlqty = 0;
    //                     for(let brs = 0 ;brs<ttlbaris;brs++) {
    //                         let qty = tables.find('tr').eq(brs).find('td').eq(retanz_tblcolindexsel).html();
    //                         ttlqty += numeral(qty).value();
    //                     }
    //                     let mdiscrepancy = Number(ttlqty)-Number(mlogic);
    //                     document.getElementById('retanz_d_actual').value = numeral(ttlqty).format('0,0');
    //                     document.getElementById('retanz_d_discrep').value = numeral(mdiscrepancy).format('0,0');
    //                     mtextColor = (mdiscrepancy < 0 ? 'red' : 'black');
    //                     document.getElementById('retanz_d_discrep').style.cssText += 'color:'+mtextColor;

    //                     let curval = tables.find('tr').eq(--retanz_tblrowindexsel).find('td').eq(retanz_tblcolindexsel).text();
    //                     let texttmp = "<input type='text' id='retanz_txttemp' onkeydown='retanz_e_kdown(event)' class='form-control form-control-sm' value='"+curval+"' onfocusout='retanz_e_focout()'>";
    //                     tables.find('tr').eq(retanz_tblrowindexsel).find('td').eq(retanz_tblcolindexsel).html(texttmp);
    //                     document.getElementById('retanz_txttemp').focus();
    //                 }
    //                 break;
    //             case arrow.down:
    //                 if(retanz_tblrowindexsel<(retanz_tbllength-1) && !e.ctrlKey){                                                         
    //                     tables.find('tr').eq(retanz_tblrowindexsel).find('td').eq(retanz_tblcolindexsel).html(befval);
    //                     let mlogic = document.getElementById('retanz_d_logic').value;
    //                     mlogic = numeral(mlogic).value();
    //                     let newval = befval;
    //                     let ttlqty = 0;
    //                     for(let brs = 0 ;brs<ttlbaris;brs++) {
    //                         let qty = tables.find('tr').eq(brs).find('td').eq(retanz_tblcolindexsel).html();
    //                         ttlqty += numeral(qty).value();
    //                     }
    //                     let mdiscrepancy = Number(ttlqty)-Number(mlogic);
    //                     document.getElementById('retanz_d_actual').value = numeral(ttlqty).format('0,0');
    //                     document.getElementById('retanz_d_discrep').value = numeral(mdiscrepancy).format('0,0');
    //                     mtextColor = (mdiscrepancy < 0 ? 'red' : 'black');
    //                     document.getElementById('retanz_d_discrep').style.cssText += 'color:'+mtextColor;

    //                     let curval = tables.find('tr').eq(++retanz_tblrowindexsel).find('td').eq(retanz_tblcolindexsel).text();
    //                     let texttmp = "<input type='text' id='retanz_txttemp' onkeydown='retanz_e_kdown(event)' class='form-control form-control-sm' value='"+curval+"' onfocusout='retanz_e_focout()'>";
    //                     tables.find('tr').eq(retanz_tblrowindexsel).find('td').eq(retanz_tblcolindexsel).html(texttmp);
    //                     document.getElementById('retanz_txttemp').focus();
    //                 }
    //                 break;
    //         }
    //     }
        
    // };
    // $("#retanzdivku").css('height', $(window).height()*57.5/100);
    // $("#retanzbtnnew").click(function(){
    //     $("#retanztxt_txno").focus();
    //     $("#retanztxt_itmcd").val('');
    //     $('#retanztxt_job').tagbox('setValues', []);        

    //     setHeadisROANZ(false);       
    // });

    // function setHeadisROANZ(sts){
    //     $("#retanztxt_txno").prop('readonly', sts);       
    // }

    // $("#retanztxt_txno").focus();
    // $("#retanztxt_txno").keypress(function (e) { 
    //     if(e.which==13){
    //         psnretanzgetdata();
    //     }
    // });
        
    // function psnretanzgetdata(){
    //     let mpsn = $("#retanztxt_txno").val();       
    //     if(mpsn.trim()!=''){
    //         $.ajax({
    //             type: "get",
    //             url: "<?=base_url('SPL/checkPSN_only')?>",
    //             data: {inpsn: mpsn},
    //             dataType: "json",
    //             success: function (response) {
    //                 if(response.status[0].cd=='0'){
    //                     alertify.warning(response[0].msg);
    //                 } else {                            
    //                     //per JOBS
    //                     let mjobs =[];
    //                     for(let x=0;x<response.datahead.length; x++){
    //                         if(!mjobs.includes(response.datahead[x].PPSN1_WONO)){
    //                             mjobs.push(response.datahead[x].PPSN1_WONO);
    //                         }
    //                     }                            
    //                     $('#retanztxt_job').tagbox('setValues', mjobs);                        
    //                     //end per JOBS
    //                     setHeadisROANZ(true);
    //                 }
    //             }, error: function(xhr, xopt, xthrow){
    //                 alertify.error(xthrow);
    //             }
    //         });
    //         $.ajax({
    //             type: "get",
    //             url: "<?=base_url('RETPRD/getlistrecap_psnonly')?>",
    //             data: {inpsn: mpsn},
    //             dataType: "json",
    //             success: function (response) {
    //                 let ttlrows = response.data.length;
    //                 retanz_tbllength = ttlrows;
    //                 let mydes = document.getElementById("retanzdivku");
    //                 let myfrag = document.createDocumentFragment();
    //                 let mtabel = document.getElementById("retanztbl");
    //                 let cln = mtabel.cloneNode(true);
    //                 myfrag.appendChild(cln);
    //                 let tabell = myfrag.getElementById("retanztbl");
    //                 let tableku2 = tabell.getElementsByTagName("tbody")[0];   
    //                 let mckall = myfrag.getElementById("retanzckall");                 
    //                 let newrow, newcell, newText;
    //                 tableku2.innerHTML='';
    //                 let tominqty = 0;
    //                 let tempqty = 0;
    //                 let todisqty = 0;  
    //                 let mm_dif = 0;
    //                 for (let i = 0; i<ttlrows; i++){
    //                     mm_dif = Number(response.data[i].TTLRET)-Number(response.data[i].LOGIC);     
    //                     newrow = tableku2.insertRow(-1);
    //                     newcell = newrow.insertCell(0);            
    //                     newText = document.createTextNode(response.data[i].SPL_ITMCD);            
    //                     newcell.appendChild(newText);
    //                     newcell = newrow.insertCell(1);
    //                     newText = document.createTextNode(response.data[i].MITM_SPTNO);
    //                     newcell.appendChild(newText);                        

    //                     newcell = newrow.insertCell(2);
    //                     newText = document.createTextNode(numeral(response.data[i].LOGIC).format(','));
    //                     newcell.style.cssText = 'text-align: right';
    //                     newcell.appendChild(newText);

    //                     newcell = newrow.insertCell(3);
    //                     newText = document.createTextNode(numeral(response.data[i].TTLRET).format(','));
    //                     newcell.appendChild(newText);                                
    //                     newcell.style.cssText = 'text-align: right;cursor:pointer';

    //                     newcell = newrow.insertCell(4);
    //                     newText = document.createTextNode(numeral(mm_dif).format('0,0'));
    //                     newcell.appendChild(newText);
    //                     newcell.style.cssText = 'text-align: right;';
    //                     if(Number(mm_dif)<0){
    //                         newcell.style.cssText = newcell.style.cssText + 'color: red';
    //                     }
    //                     newcell = newrow.insertCell(5);
    //                     newText = document.createElement('input');
    //                     newText.setAttribute('type', 'checkbox');
    //                     newText.classList.add("form-check-input");
    //                     newcell.appendChild(newText);
    //                     todisqty=0;
    //                 }        
    //                 let mrows = tableku2.getElementsByTagName("tr");      
                        
    //                 function tdcgetval(prow, pirow ,pikol){
    //                     let discrep = prow.getElementsByTagName("td")[4].innerText;
    //                     let mtextColor = '';
    //                     document.getElementById('retanz_d_itemspt').value= prow.getElementsByTagName("td")[1].innerText;
    //                     document.getElementById('retanz_d_logic').value= prow.getElementsByTagName("td")[2].innerText;
    //                     document.getElementById('retanz_d_actual').value= prow.getElementsByTagName("td")[3].innerText;
    //                     document.getElementById('retanz_d_discrep').value= discrep;
    //                     mtextColor = (numeral(discrep).value()<0) ? 'red' :'black';                        
    //                     document.getElementById('retanz_d_discrep').style.cssText += 'color:'+mtextColor;

    //                     let mpsn = document.getElementById('retanztxt_txno').value;                       
    //                     let mitem = prow.getElementsByTagName("td")[0].innerText;
    //                     document.getElementById('retanz_d_item').value=mitem ;
    //                     $.ajax({
    //                         type: "get",
    //                         url: "<?=base_url('RETPRD/getdetail_psnonly')?>",
    //                         data: {inpsn: mpsn, initem: mitem },
    //                         dataType: "json",
    //                         success: function (response) {
    //                             let ttlrows = response.data.length;
    //                             retanz_tbllength = ttlrows;
    //                             let mydes = document.getElementById("retanz_d_divku");
    //                             let myfrag = document.createDocumentFragment();
    //                             let mtabel = document.getElementById("retanz_d_tblret");
    //                             let cln = mtabel.cloneNode(true);
    //                             myfrag.appendChild(cln);
    //                             let tabell = myfrag.getElementById("retanz_d_tblret");
    //                             let tableku2 = tabell.getElementsByTagName("tbody")[0];
    //                             let mckall = myfrag.getElementById("retanz_ck_print");                    
    //                             let newrow, newcell, newText;
    //                             let saved='';
    //                             tableku2.innerHTML='';
    //                             for (let i = 0; i<ttlrows; i++){                                    
    //                                 if(response.data[i].RETSCN_SAVED){
    //                                     saved =response.data[i].RETSCN_SAVED;
    //                                     if(saved=='1'){
    //                                         saved = 'yes';
    //                                     } else {
    //                                         saved='not yet';    
    //                                     }
    //                                 } else {
    //                                     saved='not yet';
    //                                 }
    //                                 newrow = tableku2.insertRow(-1);
    //                                 newcell = newrow.insertCell(0);            
    //                                 newText = document.createTextNode(response.data[i].RETSCN_ID);            
    //                                 newcell.appendChild(newText);
    //                                 newcell = newrow.insertCell(1);
    //                                 newText = document.createTextNode(response.data[i].RETSCN_ORDERNO);
    //                                 newcell.appendChild(newText);
    //                                 newcell = newrow.insertCell(2);
    //                                 newText = document.createTextNode(response.data[i].RETSCN_LOT);
    //                                 newcell.appendChild(newText);
    //                                 newcell = newrow.insertCell(3);
    //                                 newText = document.createTextNode(numeral(response.data[i].RETSCN_QTYAFT).format('0,0'));
    //                                 newcell.style.cssText = 'text-align: right;';
    //                                 newcell.appendChild(newText);

    //                                 newcell = newrow.insertCell(4);
    //                                 newText = document.createElement('input');
    //                                 newText.setAttribute("type", "checkbox");
    //                                 //newText.disabled = saved=='yes' ? true: false;
    //                                 newcell.appendChild(newText);
    //                                 newcell.style.cssText = ''.concat('cursor: pointer;','text-align:center;');
    //                                 newcell = newrow.insertCell(5);
    //                                 newText = document.createTextNode(saved);
    //                                 newcell.appendChild(newText);

    //                                 newcell = newrow.insertCell(6);
    //                                 newText = document.createTextNode(response.data[i].RETSCN_CAT);
    //                                 newcell.appendChild(newText);
    //                                 newcell = newrow.insertCell(7);
    //                                 newText = document.createTextNode(response.data[i].RETSCN_LINE);
    //                                 newcell.appendChild(newText);
    //                                 newcell = newrow.insertCell(8);
    //                                 newText = document.createTextNode(response.data[i].RETSCN_FEDR);
    //                                 newcell.appendChild(newText);

    //                             }
    //                             let mrows = tableku2.getElementsByTagName("tr");
    //                             function editdetail(prow, pirow ,pikol){
    //                                 retanz_tblrowindexsel = pirow;
    //                                 retanz_tblcolindexsel = pikol;
    //                                 let tcell = prow.getElementsByTagName("td")[pikol];
    //                                 let curval  = tcell.innerText;
    //                                 let saved = prow.getElementsByTagName("td")[pikol+2].innerText;
    //                                 let texttmp = "<input type='text' id='retanz_txttemp' onkeydown='retanz_e_kdown(event)' class='form-control form-control-sm' value='"+curval+"' onfocusout='retanz_e_focout()'>";
    //                                 if(saved!='yes'){
    //                                     tcell.innerHTML=texttmp;
    //                                     document.getElementById('retanz_txttemp').focus();
    //                                 }
    //                             }
    //                             for(let x=0;x<mrows.length;x++){
    //                                 tableku2.rows[x].cells[3].ondblclick = function(){editdetail(tableku2.rows[x],x,3)};                       
    //                             }  
    //                             function clooptable_anz(){
    //                                 let cktemp ;
    //                                 for(let x=0;x<mrows.length;x++){
    //                                     cktemp = tableku2.rows[x].cells[4].getElementsByTagName('input')[0];
    //                                     //if(!cktemp.disabled){
    //                                         cktemp.checked=mckall.checked;
    //                                     //}                                        
    //                                 }                        
    //                             }
    //                             mckall.onclick = function(){clooptable_anz()};
    //                             mydes.innerHTML='';                            
    //                             mydes.appendChild(myfrag);
    //                         }, error: function(xhr, xopt, xthrow){
    //                             alertify.error(xthrow);
    //                         }
    //                     });
    //                     $("#RETANZ_DETAILRET").modal('show');
    //                 }      
                      
    //                 for(let x=0;x<mrows.length;x++){
    //                     for(let h=0; h<tableku2.rows[x].cells.length; h++){
    //                         if(h==3){
    //                             tableku2.rows[x].cells[h].onclick = function(){tdcgetval(tableku2.rows[x],x,h)};
    //                         }
    //                     }                        
    //                 }                    
    //                 function clooptable_anz_main(){
    //                     let cktemp ;
    //                     for(let x=0;x<mrows.length;x++){
    //                         cktemp = tableku2.rows[x].cells[5].getElementsByTagName('input')[0];
    //                         //if(!cktemp.disabled){
    //                             cktemp.checked=mckall.checked;
    //                         //}                                        
    //                     }                        
    //                 }
    //                 mckall.onclick = function(){clooptable_anz_main()};
    //                 mydes.innerHTML='';                            
    //                 mydes.appendChild(myfrag);
                   
    //             }, error: function(xhr, xopt, xthrow){
    //                 alertify.error(xthrow);
    //             }
    //         });
    //     } else {
    //         alertify.warning('should not be empty');
    //     }
    // }
    // function retanz_e_focout(){
    //     let tabell = document.getElementById("retanz_d_tblret");        

    //     let tableku2 = tabell.getElementsByTagName("tbody")[0];
    //     let baris =  tableku2.getElementsByTagName('tr')[retanz_tblrowindexsel];
    //     let ttlbaris = tableku2.getElementsByTagName('tr').length;
    //     let kol = baris.getElementsByTagName('td')[retanz_tblcolindexsel];
    //     let mlogic = document.getElementById('retanz_d_logic').value;
    //     mlogic = numeral(mlogic).value();
    //     let newval = document.getElementById('retanz_txttemp').value;
    //     newval = numeral(newval).value();
        
    //     let warna = '';      
    //     kol.innerHTML=newval;
    //     let ttlqty = 0;
    //     for(let brs = 0 ;brs<ttlbaris;brs++) {
    //         let qty = tableku2.getElementsByTagName('tr')[brs].getElementsByTagName('td')[retanz_tblcolindexsel].innerHTML;            
    //         ttlqty += numeral(qty).value();
    //     }
    //     let mdiscrepancy = ttlqty-mlogic;
    //     document.getElementById('retanz_d_actual').value = numeral(ttlqty).format('0,0');
    //     document.getElementById('retanz_d_discrep').value = numeral(mdiscrepancy).format('0,0');
    //     warna = (mdiscrepancy<0) ? 'red':'black';
    //     document.getElementById('retanz_d_discrep').style.cssText += 'color:'+warna;
    // }
      
    // $('#retanztxt_job').tagbox({
    //     label: 'Job No',        
    //     onRemoveTag :function(e) {
    //         e.preventDefault();           
    //     }
    // });    
    
    // function retanzevent_export(){
    //     let mpsn = document.getElementById("retanztxt_txno").value;
       
    //     if(mpsn.trim()==''){
    //         document.getElementById("retanztxt_txno").focus();return;
    //     }
        
    //     Cookies.set('CKPSI_DPSN', mpsn, {expires:365});       
    //     window.open("<?=base_url('RETPRD/export_to_xls')?>",'_blank');
    // }

    // $("#retanz_btnsave").click(function(e){
    //     e.preventDefault();
    //     let konfirm = confirm('Are you sure ?');        
    //     if(konfirm){         
    //         let mpsn = $("#retanztxt_txno").val();
    //         let mdate = document.getElementById('retanztxt_txdate').value;
    //         let mitmcd = document.getElementById('retanz_d_item').value;
    //         mitmcd = mitmcd.trim();
    //         let mtbl = document.getElementById('retanz_d_tblret');
    //         let mtbltr = mtbl.getElementsByTagName('tr');
    //         let ttlrows = mtbltr.length;
    //         let aid = [], aqty =[], amch = [], alot = [];
    //         if(ttlrows>1){                
    //             for(let i=1;i<ttlrows;i++){
    //                 if(mtbl.rows[i].cells[4].getElementsByTagName('input')[0].checked){
    //                     aid.push(mtbl.rows[i].cells[0].innerText);
    //                     amch.push(mtbl.rows[i].cells[1].innerText);
    //                     alot.push(mtbl.rows[i].cells[2].innerText);
    //                     aqty.push(numeral(mtbl.rows[i].cells[3].innerText).value());
    //                 }
    //             }
    //         }
    //         $.ajax({
    //             type: "post",
    //             url: "<?=base_url('RETPRD/editing')?>",
    //             data: {inpsn: mpsn,  inid: aid, inqty: aqty, initemcd: mitmcd, inmch: amch, inlot: alot,
    //             indate: mdate},
    //             dataType: "text",
    //             success: function (response) {
    //                 alertify.message(response);
    //                 $('#RETANZ_DETAILRET').modal('hide');
    //             }, error: function(xhr, xopt, xthrow){
    //                 alertify.error(xthrow);
    //             }
    //         });
    //     }
    // }); 
    // // $("#retanz_btnprintlbl").click(function (e) { 
    // //     e.preventDefault();
    // //     let mtbl = document.getElementById('retanz_d_tblret');        
    // //     let mrows = mtbl.getElementsByTagName("tr");
    // //     let idprints = '';
    // //     if(mrows.length>1){
    // //         for(let x=1;x<mrows.length;x++){
    // //             if(mtbl.rows[x].cells[4].getElementsByTagName('input')[0].checked){
    // //                 idprints += mtbl.rows[x].cells[0].innerText + '.';
    // //             }                
    // //         }
    // //         idprints = idprints.substring(0, idprints.length-1);
    // //         Cookies.set('CKPSI_IDRET', idprints , {expires:365});
    // //         window.open("<?//=base_url('printlabel_ret')?>" ,'_blank');
    // //     }
    // // });

    // $('#RETANZ_DETAILRET').on('hidden.bs.modal', function () {
    //     psnretanzgetdata();
    // });
    // $("#retanz_btnconform").click(function (e) { 
    //     let konfirm = confirm('Are you sure ?');        
    //     if(konfirm){
    //         let mpsn = $("#retanztxt_txno").val();
    //         let mdate = document.getElementById('retanztxt_txdate').value;            
    //         let mtbl = document.getElementById('retanztbl');
    //         let mtbltr = mtbl.getElementsByTagName('tr');
    //         let ttlrows = mtbltr.length;
    //         let aitemcd = [];
    //         if(ttlrows>1){                
    //             for(let i=1;i<ttlrows;i++){
    //                 if(mtbl.rows[i].cells[5].getElementsByTagName('input')[0].checked){
    //                     aitemcd.push(mtbl.rows[i].cells[0].innerText);                       
    //                 }
    //             }
    //             if(aitemcd.length > 0) {
    //                 $.ajax({
    //                     type: "post",
    //                     url: "<?=base_url('RETPRD/editing_byitempsn')?>",
    //                     data: {inpsn: mpsn, initemcd: aitemcd  ,indate: mdate},
    //                     dataType: "text",
    //                     success: function (response) {
    //                         alertify.message(response);                            
    //                     }, error: function(xhr, xopt, xthrow){
    //                         alertify.error(xthrow);
    //                     }
    //                 });
    //             } else {
    //                 alertify.message('Nothing to be conformed');
    //             }
    //         }
    //     }        
    // });
</script>