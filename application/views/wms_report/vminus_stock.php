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
<div style="padding: 10px">
    <div class="container-fluid">                     
        <div class="row" id="r_minus_stock_stack1">            
            <div class="col-md-12 mb-1 text-center">  
                <div class="btn-group btn-group-sm">                                     
                    <button class="btn btn-sm btn-primary" type="button" id="r_minus_stock_btn_gen" onclick="r_minus_stock_btn_gen_eCK(this)"><i class="fas fa-sync"></i></button>
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="r_minus_stock_divku">
                    <table id="r_minus_stock_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle">Location</th>
                                <th  class="align-middle">Item Code</th>
                                <th  class="align-middle">Item Description</th>
                                <th  class="align-middle">Item Name</th>
                                <th  class="text-end">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-md-6 mb-1 text-end">
            </div>            
            <div class="col-md-6 mb-1 text-end">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >TOTAL</span>                    
                    <input type="text" class="form-control" id="r_minus_stock_txt_total" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#r_minus_stock_divku").css('height', $(window).height()   
        -document.getElementById('r_minus_stock_stack1').offsetHeight         
        -150);
    function r_minus_stock_btn_gen_eCK(p) {
        p.disabled = true
        p.innerHTML = `<i class="fas fa-sync fa-spin"></i>`
        $.ajax({
            type: "GET",
            url: "<?=base_url('ITH/minus_stock')?>",            
            dataType: "json",
            success: function (response) {
                p.disabled = false
                p.innerHTML = `<i class="fas fa-sync"></i>`
                const ttlrows = response.data.length;
                let mydes = document.getElementById("r_minus_stock_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("r_minus_stock_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("r_minus_stock_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;                
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].QTY).value()
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.data[i].ITH_WH

                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].ITH_ITMCD
                    
                    newcell = newrow.insertCell(2)                    
                    newcell.innerHTML = response.data[i].ITMD1

                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].SPTNO                    

                    newcell = newrow.insertCell(4)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].QTY).format(',')
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
                document.getElementById('r_minus_stock_txt_total').value = numeral(ttlqty).format(',')
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow)
                p.disabled = false
                p.innerHTML = `<i class="fas fa-sync"></i>`
            }
        })
    }
</script>