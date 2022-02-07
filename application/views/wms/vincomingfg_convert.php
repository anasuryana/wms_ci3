<style>   
    .anastylesel_wip{
        background: red;  
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
</style>
<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <h3 ><span class="badge bg-info">1 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">Status Label</span></h3>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >ID</span>
                    <input type="text" class="form-control" id="rcvfg_cwip_txt_code" onkeypress="rcvfg_cwip_txt_code_e_keypress(event)" placeholder="scan it here..." required style="text-align:center" autocomplete="off">                    
                </div>
            </div>           
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">                       
                <span id="rcvfg_cwip_lblinfo_lblsts" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1" onclick="rcvfg_cwip_e_click(event)">
                <div class="table-responsive" id="rcvfg_cwip_divsubku" >
                    <table id="rcvfg_cwip_tblsub" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Assy Code</th>
                                <th>Job Number</th>
                                <th class="text-end">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <h3 ><span class="badge bg-info">2 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">Customer Label</span></h3>
            </div> 
            <div class="col-md-6 mb-1">                
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvfg_cwip_typefg" id="rcvfg_cwip_typekd" value="KD">
                    <label class="form-check-label" for="rcvfg_cwip_typekd">KD</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvfg_cwip_typefg" id="rcvfg_cwip_typeasp" value="ASP" checked>
                    <label class="form-check-label" for="rcvfg_cwip_typeasp">ASP</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Item Code</span>
                    <input type="text" class="form-control" id="rcvfg_cwip_txt_itemcode" onkeypress="rcvfg_cwip_txt_itemcode_e_keypress(event)" maxlength="50" required>
                    <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Lot No</span>
                    <input type="text" class="form-control" id="rcvfg_cwip_txt_lotno" maxlength="50" onkeypress="rcvfg_cwip_txt_lotno_e_keypress(event)" required>
                    <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Qty</span>
                    <input type="text" class="form-control" id="rcvfg_cwip_txt_qty" maxlength="5" onkeypress="rcvfg_cwip_txt_qty_e_keypress(event)" required style="text-align: right">
                    <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Reff No.</span>
                    <input type="text" class="form-control" id="rcvfg_cwip_txt_codeext" onkeypress="rcvfg_cwip_txt_codeext_e_keypress(event)" placeholder="scan it here..." required style="text-align:center">                      
                    <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rcvfg_cwip_divext">
                    <table id="rcvfg_cwip_tblext" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Reff No</th>
                                <th>ID</th>
                                <th>Assy Code</th>
                                <th>Lot No.</th>
                                <th>Qty</th>
                                <th class="d-none">InternalQty</th>
                                <th class="d-none">NewJobNo</th>
                                <th class="d-none">OldJobNo</th>
                                <th class="d-none">OldAssy</th>
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
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-primary" id="rcvfg_cwip_btn_new" onclick="rcvfg_cwip_btn_new_e_click()"><i class="fas fa-file"></i></button>  
                    <button type="button" class="btn btn-primary" id="rcvfg_cwip_btn_save" onclick="rcvfg_cwip_btn_save_e_click()"><i class="fas fa-save"></i></button>                
                    <button type="button" class="btn btn-outline-primary" id="rcvfg_cwip_btn_print" onclick="rcvfg_cwip_btn_print_e_click()"><i class="fas fa-print"></i></button>
                </div>
            </div>
        </div>     
    </div>
</div>
<input type="hidden" id="rcvfg_cwip_g_assycd">
<script>
    var rcvfg_cwip_newjob = '';

    function rcvfg_cwip_txt_lotno_e_keypress(e){
        if(e.which==13){
            let mlotno = document.getElementById('rcvfg_cwip_txt_lotno').value.substr(3,5).toUpperCase();
            let mtbl = document.getElementById('rcvfg_cwip_tblsub');
            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
            let mtbltr = tableku2.getElementsByTagName('tr');
            let ttlrows = mtbltr.length;
            let selected_lblitem = [];
            let selected_lbjob = [];
            let selected_lbjobYear = [];
            for(let i=0; i<ttlrows; i++){
                if(tableku2.rows[i].cells[0].classList.contains('anastylesel_wip')){
                    let jobmonth = tableku2.rows[i].cells[2].innerText.split("-");                    
                    if(!selected_lbjob.includes(jobmonth[1])){
                        selected_lblitem.push(tableku2.rows[i].cells[1].innerText);
                        selected_lbjob.push(jobmonth[1]);
                        selected_lbjobYear.push(jobmonth[0]);
                    }
                }
            }
            if(selected_lbjob.length>1){
                if(mlotno.substr(0,1)=='C'){
                    let iscontinue = false;
                    for(let i=0; i< selected_lbjob.length; i++ ){
                        if(selected_lbjob[i]==mlotno.substr(1,4)){
                            rcvfg_cwip_newjob = selected_lbjobYear[i] + "-C" + selected_lbjob[i] + "-" + selected_lblitem[i];
                            iscontinue = true;
                            break;
                        }
                    }
                    if(iscontinue){
                        document.getElementById('rcvfg_cwip_txt_qty').focus();
                        document.getElementById('rcvfg_cwip_txt_lotno').readOnly = true;
                    } else {
                        alertify.warning("The lot number is not recommended");
                    }
                } else {                    
                    alertify.warning("the new lot number must be start with 'C'. Because you are combining different job");
                }
            } else {
                if(mlotno.substr(0,1)=='0'){
                    selected_lbjob = selected_lbjob[0];
                    if(mlotno.substr(1,4)==selected_lbjob){
                        rcvfg_cwip_newjob = selected_lbjobYear[0] + "-" + selected_lbjob + "-" + selected_lblitem[0];
                        document.getElementById('rcvfg_cwip_txt_qty').focus();
                        document.getElementById('rcvfg_cwip_txt_lotno').readOnly = true;
                    } else {
                        alertify.warning("the new lot number does not match");
                    }
                } else {
                    alertify.warning("the new lot number must start with '0'");                    
                }
            }
        }
    }
    function rcvfg_cwip_txt_itemcode_e_keypress(e){
        if(e.which==13){
            let mitmcd = document.getElementById('rcvfg_cwip_txt_itemcode').value;
            let mtypefg = $("input[name='rcvfg_cwip_typefg']:checked").val();
            let mtbl = document.getElementById('rcvfg_cwip_tblsub');
            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
            let mtbltr = tableku2.getElementsByTagName('tr');
            let ttlrows = mtbltr.length;
            let selected_lbl = [];
            let selected_lblitem = [];
            let selected_lblqty = [];
            for(let i=0; i<ttlrows; i++){                        
                if(tableku2.rows[i].cells[0].classList.contains('anastylesel_wip')){
                    selected_lbl.push(tableku2.rows[i].cells[0].innerText);
                    selected_lblitem.push(tableku2.rows[i].cells[1].innerText);
                    selected_lblqty.push(numeral(tableku2.rows[i].cells[3].innerText).value());
                }
            }
            let selected_lblitem_distinct = [...new Set(selected_lblitem)];            
            if(selected_lblitem_distinct.length>1){
                alertify.warning("Please join 1 assy code only");
                return;
            }
            let itemsel = selected_lblitem_distinct[0];
            if(mitmcd!=itemsel){
                alertify.warning("Item selected and new label is must be same !");
                document.getElementById('rcvfg_cwip_txt_itemcode').value = '';
                return;
            }
            mitmcd = mitmcd.concat(mtypefg);
            $.ajax({
                type: "get",
                url: "<?=base_url('MSTITM/checkexist')?>",
                data: {initem: mitmcd},
                dataType: "json",
                success: function (response) {
                    if(response.data[0].cd=='11'){
                        document.getElementById('rcvfg_cwip_txt_lotno').focus();
                        document.getElementById('rcvfg_cwip_txt_itemcode').readOnly = true;
                    } else {
                        alertify.warning(response.data[0].msg);
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }

    function rcvfg_cwip_e_click(e){
        if(e.ctrlKey){
            if(e.target.tagName.toLowerCase()=='td'){
                if(e.target.cellIndex==0){
                    let mtbl = document.getElementById('rcvfg_cwip_tblsub');
                    let tableku2 = mtbl.getElementsByTagName("tbody")[0];
                    let mtbltr = tableku2.getElementsByTagName('tr');
                    let ttlrows = mtbltr.length;
                    let beforeassy = document.getElementById('rcvfg_cwip_g_assycd').value;
                    if(e.target.classList.contains('anastylesel_wip')){
                        e.target.classList.remove('anastylesel_wip');
                        let isanyselected = '';                        
                        for(let i=0;i<ttlrows;i++){                            
                            if(tableku2.rows[i].cells[0].classList.contains("anastylesel_wip")){
                                isanyselected = tableku2.rows[i].cells[1].innerText;
                                break;
                            }
                        }
                        if(isanyselected==''){
                            //reset global string assy code
                            document.getElementById('rcvfg_cwip_g_assycd').value = '';
                        }                        
                    } else {
                        let itemcd = e.target.innerText;                        
                        let mitem ='';
                        let mcnt_sel = 0;
                        let currentassy ='';   
                        let assybeingsel = tableku2.rows[e.target.parentElement.rowIndex-1].cells[1].innerText;                     
                        if(ttlrows>0){
                            for(let i=0;i<ttlrows;i++){
                                //get current selected assy code
                                if(tableku2.rows[i].cells[0].classList.contains("anastylesel_wip")){
                                    currentassy = tableku2.rows[i].cells[1].innerText;
                                    break;
                                }
                            }
                            if(currentassy!=''){                                
                                if(beforeassy!='' ){
                                    if(beforeassy!= assybeingsel){ //check assy being selected
                                        alertify.warning("Different assy code could not be joined");
                                    } else {
                                        e.target.classList.add('anastylesel_wip');
                                        document.getElementById('rcvfg_cwip_g_assycd').value = currentassy;
                                    }
                                } else {
                                    if(beforeassy == currentassy){
                                        e.target.classList.add('anastylesel_wip');
                                        document.getElementById('rcvfg_cwip_g_assycd').value = currentassy;
                                    }
                                }
                            } else {
                                e.target.classList.add('anastylesel_wip');
                                document.getElementById('rcvfg_cwip_g_assycd').value = tableku2.rows[e.target.parentElement.rowIndex-1].cells[1].innerText;
                            }
                        }                                                
                        document.getElementById('rcvfg_cwip_txt_itemcode').focus();                        
                    }                   
                }
            }
        }     
    }    

    $("input[name='rcvfg_cwip_typefg']").change(function(){        
            document.getElementById("rcvfg_cwip_txt_itemcode").focus();
            document.getElementById("rcvfg_cwip_txt_itemcode").readOnly=false;
            document.getElementById("rcvfg_cwip_txt_lotno").readOnly=false;
            document.getElementById("rcvfg_cwip_txt_qty").readOnly=false;
            document.getElementById("rcvfg_cwip_txt_code").readOnly=false;
    });

    function rcvfg_cwip_txt_code_e_keypress(e){
        if(e.which==13){
            let lbl = document.getElementById('rcvfg_cwip_txt_code');
            let url  = new URL('<?=base_url('SER/validate_oldlbl_wip')?>');
            let lblval = lbl.value.trim();
            if(lblval.substr(0,1)!="3"){
                alertify.warning("The ID is not valid");
                return;
            }
            let param = {inid: lblval};
            lbl.value = '';
            if(lblval.length<10){
                alertify.warning('it was not valid ID');                
                return;
            }

            if(!rcvfg_cwip_e_checktempkeys(lblval)){
                alertify.warning("Already added, just check the table below");
                return;
            }

            url.search = new URLSearchParams(param).toString();
            document.getElementById('rcvfg_cwip_lblinfo_lblsts').innerText = "Please wait";
            fetch(url)
            .then(function(resp) {
                if(resp.ok){
                    return resp.json();
                } else {
                    return resp.statusText;
                }
            })
            .then(function(response) { 
                document.getElementById('rcvfg_cwip_lblinfo_lblsts').innerText = "";
                if(typeof response === 'object'){
                    let ttlrows = response.data.length;                    
                    if(response.status[0].cd==1){
                        let mtbl = document.getElementById('rcvfg_cwip_tblsub');
                        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        let ttlrows = tableku2.getElementsByTagName('tr').length;
                        if(ttlrows>0){

                        }
                        newrow = tableku2.insertRow(-1);                            
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response.data[0].SER_ID);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[0].SER_ITMID);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[0].SER_DOC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newcell.classList.add('text-end');
                        newText = document.createTextNode(numeral(response.data[0].SER_QTY).format(','));
                        newcell.appendChild(newText);
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                } else {
                    alertify.warning(response);
                }
            })
            .catch(error => {
                console.log(error);
                alertify.error("duh");
            });
        }
    }
    function rcvfg_cwip_e_checktempkeys(reffNo){
        let mtbl = document.getElementById('rcvfg_cwip_tblsub');
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

    function rcvfg_cwip_btn_new_e_click(){
        document.getElementById('rcvfg_cwip_txt_code').value = '';
        document.getElementById('rcvfg_cwip_txt_codeext').value = '';
        document.getElementById('rcvfg_cwip_txt_code').focus()
        $("#rcvfg_cwip_tblext tbody").empty();
        $("#rcvfg_cwip_tblsub tbody").empty();

        document.getElementById('rcvfg_cwip_txt_itemcode').value = '';
        document.getElementById('rcvfg_cwip_txt_lotno').value = '';
        document.getElementById('rcvfg_cwip_txt_qty').value = '';
        document.getElementById('rcvfg_cwip_txt_codeext').value = '';

        document.getElementById('rcvfg_cwip_txt_itemcode').readOnly = false;
        document.getElementById('rcvfg_cwip_txt_lotno').readOnly = false;
        document.getElementById('rcvfg_cwip_txt_qty').readOnly = false;
        document.getElementById('rcvfg_cwip_txt_codeext').readOnly = false;

        rcvfg_cwip_newjob = "";
    }

    function rcvfg_cwip_txt_qty_e_keypress(e){
        if(e.which==13){
            let mqty = numeral(document.getElementById('rcvfg_cwip_txt_qty').value).value();
            let mtbl = document.getElementById('rcvfg_cwip_tblsub');
            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
            let mtbltr = tableku2.getElementsByTagName('tr');
            let ttlrows = mtbltr.length;
            let ttlqty_sel = 0;
            for(let i=0; i<ttlrows; i++){
                if(tableku2.rows[i].cells[0].classList.contains('anastylesel_wip')){
                    ttlqty_sel += numeral(tableku2.rows[i].cells[3].innerText).value();
                }
            }
            if(mqty==ttlqty_sel){
                document.getElementById('rcvfg_cwip_txt_qty').value = mqty;
                document.getElementById('rcvfg_cwip_txt_codeext').focus();
                document.getElementById('rcvfg_cwip_txt_qty').readOnly = true;
            } else {
                alertify.warning("Qty must be same !");
            }
        }
    }

    function rcvfg_cwip_txt_codeext_e_keypress(e){
        if(e.which==13){
            let codext = document.getElementById('rcvfg_cwip_txt_codeext').value.trim();            
            let mtbl = document.getElementById('rcvfg_cwip_tblsub');
            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
            let mtbltr = tableku2.getElementsByTagName('tr');
            let ttlrows = mtbltr.length;
            let mtblmapbody = document.getElementById('rcvfg_cwip_tblext').getElementsByTagName("tbody")[0];
            let ttlrowsmap = mtblmapbody.getElementsByTagName('tr').length;
            let itemqty = numeral(document.getElementById('rcvfg_cwip_txt_qty').value).value();
            if(codext.length==18 || codext.length==16){
            } else {
                alertify.warning("The Reff No. is not valid");
                return;
            }
            document.getElementById('rcvfg_cwip_txt_codeext').readOnly = true;
            let ttlselectedqty = 0;
            for(let i=0; i<ttlrows; i++){
                if(tableku2.rows[i].cells[0].classList.contains('anastylesel_wip')){
                    ttlselectedqty += numeral(tableku2.rows[i].cells[3].innerText).value();
                    let isalreadyadd = false;
                    let internallbl = tableku2.rows[i].cells[0].innerText;
                    for(let u=0; u<ttlrowsmap; u++){
                        let internallblmap = mtblmapbody.rows[u].cells[1].innerText;
                        if(internallbl==internallblmap){
                            isalreadyadd = true;
                            break;
                        }
                    }

                    //is internal label is already added
                    if(isalreadyadd){
                        alertify.warning("the internal label is already added");
                        rcvfg_cwip_cleardataentry();
                        document.getElementById('rcvfg_cwip_txt_itemcode').focus();
                        return;
                        break;
                    }

                    
                    for(let u=0; u<ttlrowsmap; u++){
                        let externallbl = mtblmapbody.rows[u].cells[0].innerText;
                        if(externallbl==codext){
                            isalreadyadd = true;
                            break;
                        }
                    }

                    //is external label is already added
                    if(isalreadyadd){
                        alertify.warning("the reff no is already added");
                        rcvfg_cwip_cleardataentry();
                        document.getElementById('rcvfg_cwip_txt_itemcode').focus();
                        return;
                        break;
                    }
                }
            }
            if(ttlselectedqty!=itemqty){
                rcvfg_cwip_cleardataentry();
                document.getElementById('rcvfg_cwip_txt_itemcode').focus();
                alertify.warning("Total Selected QTY must be same with the new QTY");
                return;
            }
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/traceid')?>",
                data: {inid: codext},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==0){
                        let itemcode = document.getElementById('rcvfg_cwip_txt_itemcode').value;
                        let lotno = document.getElementById('rcvfg_cwip_txt_lotno').value;
                        
                        itemcode = itemcode+$("input[name='rcvfg_cwip_typefg']:checked").val();
                        for(let i=0; i<ttlrows; i++){
                            if(tableku2.rows[i].cells[0].classList.contains('anastylesel_wip')){
                                newrow = mtblmapbody.insertRow(-1);
                                newcell = newrow.insertCell(0);
                                newText = document.createTextNode(codext);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(1);
                                newText = document.createTextNode(tableku2.rows[i].cells[0].innerText);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(2);
                                newText = document.createTextNode(itemcode);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(3);
                                newText = document.createTextNode(lotno);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(4);
                                newText = document.createTextNode(itemqty);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(5);
                                newcell.classList.add("d-none");
                                newText = document.createTextNode(numeral(tableku2.rows[i].cells[3].innerText).value());
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(6);
                                newcell.classList.add("d-none");
                                newText = document.createTextNode(rcvfg_cwip_newjob);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(7);
                                newcell.classList.add("d-none");
                                newText = document.createTextNode(tableku2.rows[i].cells[2].innerText);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(8);
                                newcell.classList.add("d-none");
                                newText = document.createTextNode(tableku2.rows[i].cells[1].innerText);
                                newcell.appendChild(newText);
                                tableku2.rows[i].cells[0].classList.remove('anastylesel_wip');
                            }
                        }                        
                    } else {
                        alertify.warning('The reff no. is already registered');
                    }
                    rcvfg_cwip_cleardataentry();
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }

    function rcvfg_cwip_cleardataentry(){
        document.getElementById('rcvfg_cwip_txt_itemcode').readOnly = false;
        document.getElementById('rcvfg_cwip_txt_lotno').readOnly = false;
        document.getElementById('rcvfg_cwip_txt_qty').readOnly = false;
        document.getElementById('rcvfg_cwip_txt_codeext').readOnly = false;
        document.getElementById('rcvfg_cwip_txt_itemcode').value = "";
        document.getElementById('rcvfg_cwip_txt_lotno').value = "";
        document.getElementById('rcvfg_cwip_txt_qty').value = "";
        document.getElementById('rcvfg_cwip_txt_codeext').value = "";
        rcvfg_cwip_newjob = "";
    }

    function rcvfg_cwip_btn_save_e_click(){
        let mtblmapbody = document.getElementById('rcvfg_cwip_tblext').getElementsByTagName("tbody")[0];
        let ttlrowsmap = mtblmapbody.getElementsByTagName('tr').length;
        let areffno = [];
        let aID = [];
        let aitemcd = [];
        let alotno = [];
        let areffqty = [];
        let aintqty = [];
        let anewjob = [];
        let aoldjob = [];
        let aolditemcd = [];
        
        for(let i=0; i<ttlrowsmap; i++){
            areffno.push(mtblmapbody.rows[i].cells[0].innerText);
            aID.push(mtblmapbody.rows[i].cells[1].innerText);
            aitemcd.push(mtblmapbody.rows[i].cells[2].innerText);
            alotno.push(mtblmapbody.rows[i].cells[3].innerText);
            areffqty.push(mtblmapbody.rows[i].cells[4].innerText);
            aintqty.push(mtblmapbody.rows[i].cells[5].innerText);
            anewjob.push(mtblmapbody.rows[i].cells[6].innerText);
            aoldjob.push(mtblmapbody.rows[i].cells[7].innerText);
            aolditemcd.push(mtblmapbody.rows[i].cells[8].innerText);
        }

        if(areffno.length>0){
            if(confirm("Are You sure ?")){
                $.ajax({
                    type: "post",
                    url: "<?=base_url('SER/setwiptowh')?>",
                    data: {areffno: areffno, aID: aID, aitemcd: aitemcd, alotno: alotno, areffqty :areffqty
                        , aintqty: aintqty,  anewjob: anewjob, aoldjob: aoldjob, aolditemcd: aolditemcd},
                    dataType: "json",
                    success: function (response) {
                        if(resposne.status[0].cd==1){
                            alertify.success(response.status[0].msg);
                        } else {
                            alertify.warning(response.status[0].msg);
                        }
                        rcvfg_cwip_btn_new_e_click();
                    }, error(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        } else {
            alertify.message("Nothing will be saved");
        }
    }
</script>