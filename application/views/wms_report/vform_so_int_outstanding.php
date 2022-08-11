<style>

thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;        
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 26px;
    }   
</style>
<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row" id="rsointost_stack1">            
            <div class="col-md-6 mb-1 text-center">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Sales order</span>
                    <input type="text" class="form-control" id="rsointost_txt_so" readonly>
                    <button class="btn btn-primary" type="button" onclick="rsointost_e_showModal()"><i class="fas fa-search"></i></button>
                </div>                
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rsointost_divku">
                    <table id="rsointost_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">                                
                                <th rowspan="2" class="align-middle">Item Code</th>
                                <th rowspan="2" class="align-middle">Item Name</th>
                                <th rowspan="2" class="align-middle">Document</th>
                                <th rowspan="2" class="align-middle text-center">Date</th>
                                <th colspan="2" class="text-center">Qty</th>
                            </tr>
                            <tr class="second">
                                <th class="text-center">Delivery</th>
                                <th class="text-center">Balance</th>
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
<div class="modal fade" id="rsointost_MODITM">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Sales Order List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search by</span>
                        <select id="rsointost_srchby" class="form-select" onchange="document.getElementById('rsointost_txtsearch').focus()">
                            <option value="so">Sales order</option>
                            <option value="ic">Item Code</option>
                            <option value="in">Item Name</option>                            
                        </select>
                        <input type="text" class="form-control" id="rsointost_txtsearch" maxlength="45" onkeypress="rsointost_txtsearch_eKP(event)" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rsointost_searchtbl_div">
                        <table id="rsointost_searchtbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:90%">
                            <thead class="table-light">
                                <tr>
                                    <th>Sales Order</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-end">Total Item</th>
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
    $("#rsointost_divku").css('height', $(window).height()   
    -document.getElementById('rsointost_stack1').offsetHeight     
    -100);
    function rsointost_e_showModal(){
        $("#rsointost_MODITM").modal('show')
    }
    $("#rsointost_MODITM").on('shown.bs.modal', function(){
        $("#rsointost_txtsearch").focus()
    })
    function rsointost_txtsearch_eKP(e) {
        if(e.key==='Enter') {            
            const searchby = document.getElementById('rsointost_srchby').value
            e.target.readOnly = true
            let mtabel = document.getElementById("rsointost_searchtbl");
            mtabel.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="3" class="text-center">Please wait. . .</td></tr>'
            $.ajax({
                url: "<?=base_url('SOHistory/so_list')?>",
                data: {search: e.target.value, searchby: searchby  },
                dataType: "JSON",
                success: function (response) {
                    e.target.readOnly = false
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("rsointost_searchtbl_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("rsointost_searchtbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML=''
                    for(let i=0;i<ttlrows;i++){
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].SO_NO
                        newcell.onclick=() => {
                            $("#rsointost_MODITM").modal('hide')
                            const txtdestination = document.getElementById('rsointost_txt_so')
                            txtdestination.value = response.data[i].SO_NO
                            txtdestination.focus()
                            rsointost_details(response.data[i].SO_NO)
                        }
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].SO_ORDRDT
                        newcell.classList.add('text-center')

                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].TTLROWS
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow)
                    e.target.readOnly = false
                }
            })

        }
    }

    function rsointost_details(pdata) {
        $.ajax({
            type: "POST",
            url: "<?=base_url('SOHistory/soplot_vs_dlv')?>",
            data: {doc: pdata},
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length
                let mydes = document.getElementById("rsointost_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rsointost_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rsointost_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML=''
                let itemcode = ''
                for(let i=0;i<ttlrows;i++){
                    newrow = tableku2.insertRow(-1)
                    if(response.data[i].DOC==='') {
                        newrow.classList.add("table-primary")
                        itemcode = response.data[i].ITMCD
                    }
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data[i].ITMCD
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].ITMD1                    
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].DOC
                    newcell.classList.add('text-center')
                    let thedata = itemcode
                    if(response.data[i].STATUS!=='OK'){
                        newcell.classList.add('bg-warning')
                        newcell.style.cssText = 'cursor: pointer'
                        newcell.title = 'fix this data'
                        newcell.onclick = () => {                            
                            if(confirm('Are you sure ?')) {
                                $.ajax({
                                    type: "POST",
                                    url: "<?=base_url('SO/cancel_soexim_plot')?>",
                                    data: {doc : response.data[i].DOC, itemcode: thedata },
                                    dataType: "json",
                                    success: function (response) {
                                        alertify.message(response.status.msg)
                                        rsointost_details(pdata)
                                    }, error(xhr, xopt, xthrow){
                                        alertify.error(xthrow)                                        
                                    }
                                })
                            }
                        }
                    }
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].DATE
                    newcell.classList.add('text-center')
                    newcell = newrow.insertCell(4)                    
                    newcell.innerHTML = response.data[i].DLVQT==='' ? '' : numeral(response.data[i].DLVQT).format(',')
                    newcell.classList.add('text-end')
                    newcell = newrow.insertCell(5)
                    newcell.innerHTML = numeral(response.data[i].BALANCE).format(',')
                    newcell.classList.add('text-end')
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow)
                e.target.readOnly = false
            }
        })
    }    
</script>