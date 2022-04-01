<style type="text/css">	
    thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;        
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 26px;
    }    
</style>
<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row" id="splbookl_stack0">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="splbookl_btn_new" onclick="splbookl_btn_new_eC(this)"><i class="fas fa-sync"></i></button>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1" >
                <div class="table-responsive" id="splbookl_divku_1">
                    <table id="splbookl_tbl_1" class="table table-sm table-hover table-bordered" style="width:100%;font-size:80%">
                        <thead class="table-light">
                            <tr class="first">                                
                                <th class="align-middle" rowspan="2">ID</th> <!-- 0 -->
                                <th class="align-middle" rowspan="2">PSN</th> <!-- 1 -->
                                <th class="align-middle" rowspan="2">Category</th> <!-- 2 -->
                                <th class="align-middle" rowspan="2">Part Code</th> <!-- 3 -->
                                <th class="align-middle" rowspan="2">Part Name</th> <!-- 4 -->
                                <th class="text-center" colspan="3">QTY</th> <!-- 5 -->
                            </tr>
                            <tr class="second">
                                <th class="text-end">Booked</th>
                                <th class="text-end">Issued</th>
                                <th class="text-end">Balance</th>
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
<div class="modal fade" id="splbookl_mod_closing">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Closing : <span id="splbookl_mod_span_bookid"></span></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Item Code</span>
                        <input type="text" class="form-control" id="splbookl_txt_itemcd" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Item Code</span>
                        <input type="text" class="form-control" id="splbookl_txt_itemnm" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Booked</span>
                        <input type="text" class="form-control" id="splbookl_txt_booked" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Issued</span>
                        <input type="text" class="form-control" id="splbookl_txt_issued" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Balance</span>
                        <input type="text" class="form-control" id="splbookl_txt_balance" readonly>
                    </div>
                </div>
            </div>           
        </div>
        <div class="modal-footer">            
            <button type="button" class="btn btn-primary btn-sm" id="splbookl_btn_setasclosed" onclick="splbookl_btn_setasclosed_eCK(this)">Set as closed</button>
        </div>
      </div>
    </div>
</div>
<script>
    $("#splbookl_divku_1").css('height', $(window).height()   
    -document.getElementById('splbookl_stack0').offsetHeight    
    -100);
    function splbookl_btn_new_eC(p){
        p.disabled = true
        $.ajax({            
            url: "<?=base_url('SPL/book_detail_ost')?>",
            dataType: "json",
            success: function (response) {
                p.disabled = false
                let ttlrows = response.data.length;
                let mydes = document.getElementById("splbookl_divku_1");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("splbookl_tbl_1");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("splbookl_tbl_1");
                let tableku2 = tabell.getElementsByTagName("tbody")[0]                                
                let newrow, newcell, newText;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){
                    const balanceqty = response.data[i].RQT*1-response.data[i].AQT*1
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data[i].SPLBOOK_DOC
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].SPLBOOK_SPLDOC
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].SPLBOOK_CAT
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].SPLBOOK_ITMCD
                    newcell = newrow.insertCell(4)
                    newcell.innerHTML = response.data[i].ITMD1
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].RQT*1).format(',')
                    newcell = newrow.insertCell(6)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].AQT*1).format(',')
                    newcell = newrow.insertCell(7)
                    newcell.classList.add('text-end')
                    if(response.data[i].AQT*1>0 && balanceqty ){
                        newcell.style.cssText = 'cursor:pointer'                        
                        newcell.classList.add('bg-info')
                        newcell.onclick = () => {
                            document.getElementById('splbookl_txt_itemcd').value = response.data[i].SPLBOOK_ITMCD
                            document.getElementById('splbookl_txt_itemnm').value = response.data[i].ITMD1
                            document.getElementById('splbookl_txt_booked').value = numeral(response.data[i].RQT*1).format(',')
                            document.getElementById('splbookl_txt_issued').value = numeral(response.data[i].AQT*1).format(',')
                            document.getElementById('splbookl_txt_balance').value = numeral(balanceqty).format(',')
                            document.getElementById('splbookl_mod_span_bookid').innerHTML = response.data[i].SPLBOOK_DOC
                            $("#splbookl_mod_closing").modal('show')
                        }
                    }
                    newcell.innerHTML = numeral(balanceqty).format(',')
                }                
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow)
                p.disabled = false
            }
        })
    }

    function splbookl_btn_setasclosed_eCK(e){        
        if(confirm("Are you sure ?")){
            const bookid = document.getElementById('splbookl_mod_span_bookid').innerText
            const itemcd = document.getElementById('splbookl_txt_itemcd').value
            const issuedQTY = numeral(document.getElementById('splbookl_txt_issued').value).value()
            e.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('SPL/book_closing')?>",
                data: {bookid: bookid, itemcd: itemcd, issuedQTY: issuedQTY},
                dataType: "json",
                success: function (response) {
                    e.disabled = false
                    if(response.status[0].cd==1){
                        alertify.success(response.status[0].msg)
                    } else {
                        alertify.message(response.status[0].msg)                        
                    }
                }, error(xhr, xopt, xthrow){
                    e.disabled = false
                    alertify.error(xthrow)
                }
            })
            $("#splbookl_mod_closing").modal('hide')
        }
    }
</script>