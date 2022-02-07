<div style="padding: 10px">
	<div class="container-xxl">        
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Location</span>                    
                    <input type="text" class="form-control" id="rcvfgwh_txt_loc" onfocus="this.select()" onkeypress="rcvfgwh_evt_validateloc(event)" maxlength="99" placeholder="Scan" required style="text-align:center">                     
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Serial Code</span>                    
                    <input type="text" class="form-control" id="rcvfgwh_txt_code" onfocus="this.select()"  onkeypress="rcvfgwh_evt_code(event)" maxlength="100" placeholder="Scan" required style="text-align:center">                      
                    <span class="input-group-text" ><i class="fas fa-barcode"></i></span>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rcvfgwh_divku">
                    <table id="rcvfgwh_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Doc No</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th class="text-right">Qty</th>
                                <th>Unit Measure</th>
                                <th>User</th>
                                <th>FG Uniquecode</th>
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
<script>
    $("#rcvfgqc_divku").css('height', $(window).height()*70/100);
    document.getElementById("rcvfgwh_txt_loc").focus();
    function rcvfgwh_evt_code(e){
        if(e.which==13){
            let mloc = document.getElementById("rcvfgwh_txt_loc").value;
            let mval = document.getElementById("rcvfgwh_txt_code").value;
            $.ajax({
                type: "get",
                url: "<?=base_url('INCFG/setwh')?>",
                data: {incode: mval, inloc: mloc},
                dataType: "text",
                success: function (response) {
                    alertify.message(response);
                    document.getElementById("rcvfgwh_txt_code").value='';
                    rcvfgwh_evt_gettodayscan();
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }
    rcvfgwh_evt_gettodayscan();
    function rcvfgwh_evt_gettodayscan(){       
        $.ajax({
            type: "get",
            url: "<?=base_url('INCFG/gettodayscanwh')?>",            
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rcvfgwh_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rcvfgwh_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rcvfgwh_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let msts='';
                for (let i = 0; i<ttlrows; i++){                    
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newText = document.createTextNode((i+1));            
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].ITH_DOC);
                    newcell.appendChild(newText);        
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data[i].ITH_ITMCD.trim());
                    newcell.appendChild(newText);
                    newcell.style.cssText = 'text-align: center';
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.data[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(numeral(response.data[i].ITH_QTY).format(','));
                    newcell.appendChild(newText);
                    newcell.style.cssText = 'text-align: right';
                    
                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.data[i].MITM_STKUOM);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.data[i].MSTEMP_FNM);
                    newcell.appendChild(newText);                                        
                    newcell = newrow.insertCell(7);
                    newText = document.createTextNode(response.data[i].ITH_SER);
                    newcell.appendChild(newText);                                        
                }                
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
   
    function rcvfgwh_evt_validateloc(e){
        if(e.which==13){
            let mval = document.getElementById('rcvfgwh_txt_loc').value;
            $.ajax({
                type: "get",
                url: "<?=base_url('MSTLOC/checkisExist2')?>",
                data: {incd: mval },
                dataType: "text",
                success: function (response){
                    if(response=='1'){
                        document.getElementById('rcvfgwh_txt_code').focus();
                    } else {
                        alertify.warning('Location not found');
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }
</script>