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
        <div class="row" id="discrep_stack1">            
            <div class="col-md-12 mb-1 text-center">                       
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="discrepso_btn_gen" onclick="discrepso_e_btnrefresh()"><i class="fas fa-sync"></i></button>
                </div>                
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="discrepso_divku">
                    <table id="discrepso_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th rowspan="2" class="align-middle">Model</th>
                                <th rowspan="2" class="align-middle">Model Name</th>
                                <th colspan="3" class="text-center">Qty</th>
                            </tr>
                            <tr class="second">
                                <th class="text-center">Current Stock</th>
                                <th class="text-center">Plot</th>
                                <th class="text-center">Discrepancy</th>
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
     $("#discrepso_divku").css('height', $(window).height()   
        -document.getElementById('discrep_stack1').offsetHeight        
        -100);
    function discrepso_e_btnrefresh(){    
        document.getElementById('discrepso_btn_gen').disabled = true
        document.getElementById('discrepso_btn_gen').innerHTML = `<i class="fas fa-spinner fa-spin"></i>`
        $("#discrepso_tbl tbody").empty();
        $.ajax({            
            url: "<?=base_url('SI/so_vs_stock')?>",            
            dataType: "json",
            success: function (response) {
                document.getElementById('discrepso_btn_gen').disabled = false   
                document.getElementById('discrepso_btn_gen').innerHTML = `<i class="fas fa-sync"></i>`
                    let ttlrows = response.data.length;
                    let mydes = document.getElementById("discrepso_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("discrepso_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);                    
                    let tabell = myfrag.getElementById("discrepso_tbl");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let tmpnomor = '';
                    let mnomor =0;
                    let ttlqty = 0;                
                    for (let i = 0; i<ttlrows; i++){
                        let discrepqty = numeral(response.data[i].PLOTQTY).value()-numeral(response.data[i].STOCKQTY).value();
                        newrow = tableku2.insertRow(-1);
                        if(response.data[i].COLOR=='YELLOW') {
                            newrow.classList.add('table-warning')
                        } else if (response.data[i].COLOR=='RED') {
                            newrow.classList.add('table-danger')
                        }
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response.data[i].ITH_ITMCD);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newcell.innerHTML = response.data[i].MITM_ITMD1
                        newcell = newrow.insertCell(2);
                        newcell.classList.add('text-end');
                        newText = document.createTextNode(numeral(response.data[i].STOCKQTY).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newcell.classList.add('text-end');
                        newText = document.createTextNode(numeral(response.data[i].PLOTQTY).format(','));
                        newcell.appendChild(newText);                        
                        newcell = newrow.insertCell(4);
                        newcell.classList.add('text-end');
                        newText = document.createTextNode(numeral(discrepqty).format(','));
                        newcell.appendChild(newText);                        
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);                
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('discrepso_btn_gen').disabled = false
                document.getElementById('discrepso_btn_gen').innerHTML = `<i class="fas fa-sync"></i>`
            }
        });
    }
</script>