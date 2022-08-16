<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Code</label>                    
                    <input type="text" class="form-control" id="sup_txtsupcd">                    
                    <button title="Find Supplier" class="btn btn-outline-secondary" type="button" id="sup_btnformodsup"><i class="fas fa-search"></i></button>                                            
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Currency</label>                    
                    <input type="text" class="form-control" id="sup_txtcur" maxlength="4">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Name</label>                    
                    <input type="text" class="form-control" id="sup_txtnm">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Abbr Name</label>                    
                    <input type="text" class="form-control" id="sup_txtabbrnm">
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="col-md-12 mb-1">
                <div class="input-group">                    
                    <span class="input-group-text">Address</span>                    
                    <textarea class="form-control" id="sup_taaddr" aria-label="Address"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Phone</label>
                    <input type="text" class="form-control" id="sup_txtphone">
                </div>                
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Fax</label>
                    <input type="text" class="form-control" id="sup_txtfax">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Tax</label>
                    <input type="text" class="form-control" id="sup_txttax">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="sup_btnnew" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="sup_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-3 text-end">
                <div class="btn-group btn-group-sm">
                    <!-- <button title="New" id="sup_btnsync" class="btn btn-primary" onclick="sup_sync(this)">Synchronize</button> -->
                </div>
            </div>
        </div>        
    </div>
    
</div>
<div class="modal fade" id="SUP_MODSUP">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Supplier List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search by</span>                        
                        <select id="sup_srchby" class="form-select">
                            <option value="cd">Code</option>
                            <option value="nm">Name</option>                            
                            <option value="ab">Abbr Name</option>
                            <option value="ad">Address</option>
                        </select>                      
                        <input type="text" class="form-control" id="sup_txtsearch" onkeypress="sup_txtsearch_KP(event)" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>           
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="sup_tbl_div">
                        <table id="sup_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Supplier Code</th>
                                    <th>Currency</th>
                                    <th>Name</th>
                                    <th>Abbr Name</th>
                                    <th>Address</th>
                                    <th class="d-none">Phone</th>
                                    <th class="d-none">Fax</th>
                                    <th>Tax</th>
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
    $("#sup_btnnew").click(function(){
        $("#sup_txtsupcd").val('');
        $("#sup_txtcur").val('');
        $("#sup_txtnm").val('');
        $("#sup_txtabbrnm").val('');
        $("#sup_taaddr").val('');        
        $("#sup_txtsupcd").prop('readonly', false);
        document.getElementById('sup_txtphone').value = ''
        document.getElementById('sup_txtfax').value = ''
        $("#sup_txtsupcd").focus()
    })   
    $("#sup_btnformodsup").click(function(){
        $("#SUP_MODSUP").modal('show');
    });
    $("#SUP_MODSUP").on('shown.bs.modal', function(){
        $("#sup_txtsearch").focus();
    });
    $("#sup_srchby").change(function(){
        $("#sup_txtsearch").focus();
    })
    function sup_txtsearch_KP(e){
        if (e.key=='Enter'){
            e.target.readOnly = true            
            const msearchby = $("#sup_srchby").val();            
            $.ajax({
                type: "get",
                url: "<?=base_url('MSTSUP/search')?>",
                data: {cid : e.target.value, csrchby: msearchby},
                dataType: "json",
                success: function (response) {
                    e.target.readOnly = false
                    let ttlrows = response.length;
                    let mydes = document.getElementById("sup_tbl_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("sup_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);                
                    let tabell = myfrag.getElementById("sup_tbl");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let tominqty = 0;
                    let tempqty = 0;
                    let todisqty = 0;           
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.onclick = () => {
                            $("#sup_txtsupcd").val(response[i].MSUP_SUPCD.trim())
                            $("#sup_txtcur").val(response[i].MSUP_SUPCR);
                            $("#sup_txtnm").val(response[i].MSUP_SUPNM);
                            $("#sup_txtabbrnm").val(response[i].MSUP_ABBRV);
                            $("#sup_taaddr").val(response[i].MSUP_ADDR1);
                            document.getElementById('sup_txtphone').value = response[i].MSUP_TELNO
                            document.getElementById('sup_txtfax').value = response[i].MSUP_FAXNO
                            document.getElementById('sup_txttax').value = response[i].MSUP_TAXREG
                            $("#SUP_MODSUP").modal('hide');
                            $("#sup_txtsupcd").prop('readonly', true);
                        }
                        newcell.innerHTML = response[i].MSUP_SUPCD.trim()
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response[i].MSUP_SUPCR
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response[i].MSUP_SUPNM
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = response[i].MSUP_ABBRV
                        newcell = newrow.insertCell(4)
                        newcell.innerHTML = response[i].MSUP_ADDR1
                        newcell = newrow.insertCell(5)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response[i].MSUP_TELNO
                        newcell = newrow.insertCell(6)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response[i].MSUP_FAXNO
                        newcell = newrow.insertCell(7)                        
                        newcell.innerHTML = response[i].MSUP_TAXREG
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag)                   
                }, error: function(xhr, xopt, xthrow){
                    e.target.readOnly = true
                }
            })
        }
    }    
    $("#sup_btnsave").click(function(){        
        if(confirm("Are you sure ?")){
            let mcd     = $("#sup_txtsupcd").val();
            let mcur    = $("#sup_txtcur").val();
            let mnm     = $("#sup_txtnm").val();
            let mabbr   = $("#sup_txtabbrnm").val();
            let maddr   = $("#sup_taaddr").val();
            let mphone  = $("#sup_txtphone").val();
            let mfax   = $("#sup_txtfax").val()
            let mtax   = $("#sup_txttax").val()
            if(mcd.trim()==''){$("#sup_txtsupcd").focus() ;return;}
            $.ajax({
                type: "post",
                url: "<?=base_url('MSTSUP/set')?>",
                data: {incd: mcd, incur: mcur, innm: mnm, inabbr: mabbr, inaddr: maddr,mphone: mphone, mfax: mfax, mtax: mtax },
                dataType: "text",
                success: function (response) {
                    alertify.message(response);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            })
        }
    })

    function sup_sync(p) {
        if( confirm('Are you sure ?') ) {
            p.innerHTML = 'Please wait'
            p.disabled = true
            $.ajax({
                type: "post",
                url: "<?=base_url('MSTSUP/synchronize_parent')?>",
                dataType: "json",
                success: function (response) {
                    p.innerHTML = 'Synchronize'
                    p.disabled = false
                    alertify.message(response.status.msg)
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow)
                    p.disabled = false
                    p.innerHTML = 'Synchronize'
                }
            })
        }
    }
</script>