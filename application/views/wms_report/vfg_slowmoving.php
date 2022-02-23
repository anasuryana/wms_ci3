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
        <div class="row" id="fgslowmoving_stack1">            
            <div class="col-md-12 mb-1 text-center">                       
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="fgslowmoving_btn_gen" onclick="fgslowmoving_btn_gen_eCK()"><i class="fas fa-sync"></i></button>                    
                </div>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="fgslowmoving_divku">
                    <table id="fgslowmoving_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle">Assy Number</th>
                                <th  class="align-middle">Model</th>                                
                                <th  class="text-end">Qty</th>                                
                                <th  class="text-center">Last Transaction</th>                                
                                <th  class="text-end">Day</th>
                            </tr>                           
                        </thead>
                        <tbody>                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
        <div class="row" id="fgslowmoving_stack3">
            <div class="col-md-6 mb-1 text-end">
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >TOTAL</span>                    
                    <input type="text" class="form-control" id="fgslowmoving_txt_total" readonly>
                </div>
            </div>
        </div>   	
    </div>
</div>
<script>
    $("#fgslowmoving_divku").css('height', $(window).height()   
    -document.getElementById('fgslowmoving_stack1').offsetHeight 
    -document.getElementById('fgslowmoving_stack3').offsetHeight     
    -100);
    function fgslowmoving_btn_gen_eCK(){
        const btnsync = document.getElementById('fgslowmoving_btn_gen')
        btnsync.disabled = true
        btnsync.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`
        
        $.ajax({            
            url: "<?=base_url('ITH/fg_slow_moving')?>",            
            dataType: "JSON",
            success: function (response) {
                btnsync.disabled = false
                btnsync.innerHTML = `<i class="fas fa-sync"></i>`
                let ttlrows = response.data.length;
                let mydes = document.getElementById("fgslowmoving_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("fgslowmoving_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("fgslowmoving_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;                
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].STKQTY).value();
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newcell.innerHTML = response.data[i].ITH_ITMCD
                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].MITM_ITMD1                    
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].STKQTY).format(',')
                    newcell = newrow.insertCell(3);
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].LTSDELV ? response.data[i].LTSDELV : response.data[i].LTSDT
                    newcell = newrow.insertCell(4);
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].DAYLEFT
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('fgslowmoving_txt_total').value = numeral(ttlqty).format(',')
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                btnsync.disabled = false
                btnsync.innerHTML = `<i class="fas fa-sync"></i>`
            }
        })
    }
</script>