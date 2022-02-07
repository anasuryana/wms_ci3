<div style="padding: 5px">
	<div class="container-xxl">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">DO Number</label>                    
                    <input type="text" class="form-control" id="reviselot_dono" readonly>                                         
                    <button class="btn btn-primary" id="reviselot_btn_tg_mod"><i class="fas fa-search"></i> </button>                
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12 mb-1">
                <span id="reviselot_lblinfo" class="badge badge-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive" id="reviselot_divku">
                    <table id="reviselot_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:80%">
                        <thead class="table-light">
                            <tr>                                
                                <th  class="d-none">ID</th>
                                <th  class="align-middle ">Item Code</th>                                
                                <th  class="align-middle">Item Name</th>
                                <th  class="align-middle">Lot Number</th>
                                <th  class="text-center" >QTY</th>
                                <th  class="text-center" >New Lot Number</th>
                            </tr>                            
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
      
        <div class="row">
            <div class="col text-center ">
                <div class="btn-group btn-group-sm">                    
                    <button class="btn btn-primary" type="button" id="reviselot_btn_save"><i class="fas fa-save"></i> </button>                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="reviselot_DTLMOD">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">DO List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">              
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text">Search</label>                        
                        <input type="text" class="form-control" id="reviselot_txt_search">
                    </div>
                </div>
            </div>                        
            <div class="row">
                <div class="col text-center">
                    <div class="form-check-inline">
                        <label class="form-check-label">
                           Search by
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <input type="radio" id="reviselot_rad_do" onclick="reviselot_e_focussearch()" class="form-check-input" name="reviselot_rad" value="do" checked>
                        <label class="form-check-label" for="reviselot_rad_do">
                        DO No
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <input type="radio" id="reviselot_rad_lot" onclick="reviselot_e_focussearch()" class="form-check-input" name="reviselot_rad" value="lot">
                        <label class="form-check-label" for="reviselot_rad_lot">
                        Lot Number
                        </label>
                    </div>
                </div>
            </div>         
            <div class="row">
                <div class="col text-right mb-1">
                    <span class="badge bg-info" id="lblinfo_reviselot_tbldono"></span>
                </div>
            </div>
            <div class="row">
                <div class="col" id="reviselot_divku_search">
                    <table id="reviselot_tbldono" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th>DO No</th>
                                <th>Supplier</th>
                                <th>Item Code</th>
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
<script>
    $("#reviselot_btn_save").click(function (e) { 
        let mtbl = document.getElementById('reviselot_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let aid =[];
        let anewlot =[];
        let tempstr = '';
        for(let i=0;i<ttlrows;i++){
            tempstr = tableku2.rows[i].cells[5].innerText.trim();
            if(tempstr!=''){
                aid.push(tableku2.rows[i].cells[0].innerText);
                anewlot.push(tempstr);
            }
        }
        if(aid.length>0){
            let konfr = confirm('Are you sure ?');
            if(konfr){
                $.ajax({
                    type: "post",
                    url: "<?=base_url('RCV/revise_lot')?>",
                    data: {inid: aid, innewlot: anewlot},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd!='0'){
                            alertify.success(response.status[0].msg);
                        } else {
                            alertify.message(response.status[0].msg);
                        }
                    }, error: function(xhr,xopt,xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        }
    });
    $("#reviselot_DTLMOD").on('shown.bs.modal', function(){
        document.getElementById('reviselot_txt_search').focus();
        document.getElementById('reviselot_txt_search').select();
    });
    function reviselot_e_focussearch(){
        document.getElementById('reviselot_txt_search').focus();
        document.getElementById('reviselot_txt_search').select();
    }
    $("#reviselot_btn_tg_mod").click(function (e) { 
        $("#reviselot_DTLMOD").modal('show');        
    });
    $("#reviselot_txt_search").keypress(function (e) { 
        let msearch = document.getElementById('reviselot_txt_search').value;
        let msearchby = $("input[name=reviselot_rad]:checked").val();
        if(e.which==13){
            document.getElementById('lblinfo_reviselot_tbldono').innerText='Please wait . . .';
            $("#reviselot_tbldono tbody").empty();
            $.ajax({
                type: "get",
                url: "<?=base_url('RCV/searchby_do_lot')?>",
                data: {insearch: msearch, insearchby: msearchby},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){
                        let ttlrows = response.data.length;
                        document.getElementById('lblinfo_reviselot_tbldono').innerText=ttlrows+' row(s) found';
                        let mydes = document.getElementById("reviselot_divku_search");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("reviselot_tbldono");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("reviselot_tbldono");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];                        
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        for (let i = 0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1);
                            newrow.onclick = function(){reviselot_e_getdetail(response.data[i].RCV_DONO,response.data[i].RCV_ITMCD.trim() )};
                            newcell = newrow.insertCell(0);            
                            newText = document.createTextNode(response.data[i].RCV_DONO);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.data[i].MSUP_SUPNM);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);                            
                            newText = document.createTextNode(response.data[i].RCV_ITMCD.trim());
                            newcell.appendChild(newText);
                        }
                        mydes.innerHTML='';                            
                        mydes.appendChild(myfrag);
                    } else {
                        document.getElementById('lblinfo_reviselot_tbldono').innerText='Not found';
                    }
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });

    function reviselot_e_getdetail(pdo, pitem){
        document.getElementById('reviselot_dono').value=pdo;
        document.getElementById('reviselot_lblinfo').innerText='Please wait...';
        $("#reviselot_DTLMOD").modal('hide');
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/search_scn_orderbyitemlot')?>",
            data: {indo: pdo, initemcd: pitem},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    let ttlrows = response.data.length;
                    document.getElementById('reviselot_lblinfo').innerText=ttlrows+' row(s) found';
                    let mydes = document.getElementById("reviselot_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("reviselot_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("reviselot_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];                        
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);                        
                        newcell = newrow.insertCell(0);  
                        newcell.style.cssText = 'display:none';
                        newText = document.createTextNode(response.data[i].RCVSCN_ID);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].RCVSCN_ITMCD);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].MITM_SPTNO);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.data[i].RCVSCN_LOTNO);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);                        
                        newText = document.createTextNode(numeral(response.data[i].RCVSCN_QTY).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(5);
                        newcell.onkeypress = function(e){                            
                            
                            if(e.which==13){
                                let thecell = tableku2.rows[i+1].cells[5].focus();                                
                                e.preventDefault();                                
                            }                            
                        };
                        newcell.contentEditable="true";
                        newText = document.createTextNode('');
                        newcell.appendChild(newText);
                    }
                    mydes.innerHTML='';             
                    mydes.appendChild(myfrag);
                } else {
                    document.getElementById('reviselot_lblinfo').innerText='Not found';
                }
            },error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }
</script>