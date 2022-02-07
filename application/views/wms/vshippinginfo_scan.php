<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">				
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="New"  id="shpscanfg_btnnew" class="btn btn-outline-primary" ><i class="fas fa-file"></i></button>                    
                    <button title="Save" id="shpscanfg_btnsave" class="btn btn-outline-primary" ><i class="fas fa-save"></i></button>                    
                    <button title="Print" id="shpscanfg_btnprint" class="btn btn-outline-primary" ><i class="fas fa-print"></i></button>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >SI Doc</span>                    
                    <input type="text" class="form-control" id="shpscanfg_txt_doc" onfocus="this.select()" onkeypress="shpscanfg_e_press_doc(event)" required placeholder="Scan SI Document here...">                    
                </div>
            </div>                
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control"  id="shpscanfg_txt_itemcode" onfocus="this.select()" onkeypress="shpscanfg_e_press_item(event)"  required placeholder="Scan Item here..">                                       
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Location</span>                    
                    <input type="text" class="form-control" id="shpscanfg_txt_loc" readonly required>                    
                </div>
            </div>                
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >FIFO Serial</span>                    
                    <input type="text" class="form-control" id="shpscanfg_txt_serfif" readonly required >
                </div>
            </div>               
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >FG Serial</span>                    
                    <input type="text" class="form-control" id="shpscanfg_txt_ser" onfocus="this.select()" maxlength="40" placeholder="scan FG Serial here..." style="text-align: center" required>                    
                </div>
            </div>
        </div>
                
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="shpscanfg_divku">
                    <table id="shpscanfg_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Reff Doc No</th>
                                <th>Item Code</th>
                                <th>Model</th>
                                <th>Req. Date</th>
                                <th>Req. QTY</th>
                                <th>Actual QTY</th>
                                <th>Total Box</th>
                                <th class="d-none">LINENO</th>
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
<div class="modal fade" id="shpscanfg_mod_scanned">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Detail</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">  
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">Reff Document</label>                        
                        <input type="text" class="form-control" id="shpscanfg_d_doc" readonly>
                    </div>
                </div>                
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Item Code</label>                        
                        <input type="text" class="form-control" id="shpscanfg_d_item" readonly>
                    </div>
                </div>                
            </div>                 
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="shpscanfg_d_divku">
                        <table id="shpscanfg_d_tblscn" class="table table-hover table-sm table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Reff No</th>
                                    <th>QTY</th>                                    
                                    <th></th>                                    
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
</div>
<script>
    document.getElementById('shpscanfg_txt_doc').focus();
    function shpscanfg_initsi(){
        let mval = document.getElementById('shpscanfg_txt_doc').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('SI/scanbalancing')?>",
            data: {insi: mval},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                if(ttlrows>0){
                    let mydes = document.getElementById("shpscanfg_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("shpscanfg_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("shpscanfg_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let tominqty = 0;
                    let tempqty = 0;
                    let todisqty = 0;  
                    for (let i = 0; i<ttlrows; i++){                            
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);            
                        newText = document.createTextNode((i+1));            
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].SI_DOCREFF);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].SI_ITMCD);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.data[i].SI_MDL);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(response.data[i].SI_REQDT);
                        newcell.appendChild(newText);  
                        newcell.style.cssText = 'text-align: center';

                        newcell = newrow.insertCell(5);
                        newText = document.createTextNode(numeral(response.data[i].SI_QTY).format(','));
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';                         

                        newcell = newrow.insertCell(6);
                        newText = document.createTextNode(numeral(response.data[i].TTLSCN).format(','));
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right;cursor:pointer;';

                        newcell = newrow.insertCell(7);
                        newText = document.createTextNode(numeral(response.data[i].TTLLBL).format(','));
                        newcell.appendChild(newText);                        
                        newcell.style.cssText = 'text-align: right';

                        newcell = newrow.insertCell(8);
                        newcell.style.cssText = 'display: none';               
                        newText = document.createTextNode(response.data[i].SI_LINENO);
                        newcell.appendChild(newText);
                        todisqty=0;
                    }
                    let mrows = tableku2.getElementsByTagName("tr");
                    for(let x=0;x<mrows.length;x++){
                        tableku2.rows[x].cells[6].onclick = function(){shpscanfg_e_showdetail(tableku2.rows[x])};                                                
                    }
                    mydes.innerHTML='';                            
                    mydes.appendChild(myfrag);
                    document.getElementById('shpscanfg_txt_itemcode').focus();
                } else {
                    alertify.warning('SI Doc not found');
                }
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function shpscanfg_e_showdetail(prow){
        
        let itemdoc = prow.getElementsByTagName("td")[1].innerText;
        let itemcd = prow.getElementsByTagName("td")[2].innerText;
        let siline = prow.getElementsByTagName("td")[8].innerText;
        document.getElementById('shpscanfg_d_doc').value=itemdoc;
        document.getElementById('shpscanfg_d_item').value=itemcd;
        $("#shpscanfg_d_tblscn tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('SI/getscanned')?>",
            data: {insiline: siline},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    let ttlrows = response.data.length;
                    let mydes = document.getElementById("shpscanfg_d_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("shpscanfg_d_tblscn");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("shpscanfg_d_tblscn");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);            
                        newText = document.createTextNode(response.data[i].SISCN_SER);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newcell.style.cssText = 'text-align: right';
                        newText = document.createTextNode(numeral(response.data[i].SISCN_SERQTY).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newcell.style.cssText = 'text-align: center;cursor:pointer;';
                        newText = document.createElement('i');
                        newText.classList.add("fas");
                        newText.classList.add("fa-trash");
                        newText.classList.add("text-danger");
                        newcell.appendChild(newText);
                    }
                    let mrows = tableku2.getElementsByTagName("tr");
                    for(let x=0;x<mrows.length;x++){
                        tableku2.rows[x].cells[2].onclick = function(){shpscanfg_e_cancel(tableku2.rows[x])};
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                } else {
                    alertify.message(response.status[0].msg);
                }
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
        $("#shpscanfg_mod_scanned").modal('show');
    }

    function shpscanfg_e_cancel(prow){
        let reffno = prow.getElementsByTagName('td')[0].innerText;
        let konfr = confirm('Are You sure ? ');
        if(konfr){
            $.ajax({
                type: "post",
                url: "<?=base_url('SI/removescan')?>",
                data: {inreffno : reffno},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){
                        alertify.success(response.status[0].msg);
                        $("#shpscanfg_mod_scanned").modal('hide');
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }

    function shpscanfg_e_press_doc(e){
        if(e.which==13){
            shpscanfg_initsi();
        }
    }
    function shpscanfg_e_press_item(e){
        if(e.which==13){
            let mdoc = document.getElementById('shpscanfg_txt_doc').value;
            let mitem = document.getElementById('shpscanfg_txt_itemcode').value;
            $.ajax({
                type: "get",
                url: "<?=base_url('SI/checkSIItem')?>",
                data: {insi: mdoc, initem: mitem},
                dataType: "json",
                success: function (response) {
                    if(response.data[0].cd==0){
                        alertify.warning(response.data[0].msg);
                    } else {
                        $("#shpscanfg_txt_ser").focus();
                    }
                    if(response.datasug.length>0){
                        document.getElementById('shpscanfg_txt_loc').value=response.datasug[0].ITH_LOC;
                        document.getElementById('shpscanfg_txt_serfif').value=response.datasug[0].ITH_SER;
                    } else {
                        document.getElementById('shpscanfg_txt_loc').value='';
                        document.getElementById('shpscanfg_txt_serfif').value='';
                    }
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }
    $("#shpscanfg_btnnew").click(function (e) { 
        document.getElementById('shpscanfg_txt_doc').value='';
        document.getElementById('shpscanfg_txt_itemcode').value='';
        document.getElementById('shpscanfg_txt_loc').value='';
        document.getElementById('shpscanfg_txt_serfif').value='';
        document.getElementById('shpscanfg_txt_ser').value='';
        document.getElementById('shpscanfg_txt_doc').focus(); 
        $("#shpscanfg_tbl tbody").empty();
    });
    $("#shpscanfg_txt_ser").keypress(function (e) { 
        if(e.which==13){
            let mval = $(this).val();
            let msug = document.getElementById('shpscanfg_txt_serfif').value;
            let mitem = document.getElementById('shpscanfg_txt_itemcode').value;
            let msi = document.getElementById('shpscanfg_txt_doc').value;
            if(mval.length>50){alertify.warning('Serial is invalid');return;}
            $.ajax({
                type: "GET",
                url: "<?=base_url('SI/scanlabel')?>",
                data: {insi : msi,inser: mval, insersug: msug, initem: mitem},
                dataType: "text",
                success: function (response) {
                    alertify.message(response);
                    document.getElementById('shpscanfg_txt_ser').value='';
                    shpscanfg_initsi();
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#shpscanfg_btnsave").click(function (e) { 
        let msi = document.getElementById('shpscanfg_txt_doc').value;
        if(msi.trim()==''){
            alertify.warning('Please entry SI Doc');document.getElementById('shpscanfg_txt_doc').focus();return;
        }
        let konf = confirm('Are you sure ?');
        if(konf==false){return;}
        $.ajax({
            type: "post",
            url: "<?=base_url('SI/savescan')?>",
            data: {insi: msi},
            dataType: "text",
            success: function (response) {
                alertify.message(response);
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    });

    $("#shpscanfg_btnprint").click(function (e) { 
        let msi = document.getElementById('shpscanfg_txt_doc').value;
        if(msi.trim()==''){
            document.getElementById('shpscanfg_txt_doc').focus();
            alertify.warning('Please select document first');
            return;
        }
        Cookies.set('PRINTLABEL_SI', msi, {expires:365});
        window.open("<?=base_url('printpickingresult_doc')?>",'_blank');
    });
</script>