<div style="padding: 10px">
    <div class="container-fluid">                     
        <div class="row">            
            <div class="col-md-12 mb-1 text-center">                       
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="fginventory_btn_gen"><i class="fas fa-sync"></i></button>                    
                    <button class="btn btn-danger" type="button" id="fginventory_btn_trash"><i class="fas fa-trash"></i></button>
                </div>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <span id="fginventory_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="fginventory_divku">
                    <table id="fginventory_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr>
                                <th  class="align-middle">Assy Number</th>
                                <th  class="align-middle">Model</th>
                                <th  class="align-middle">Lot</th>
                                <th  class="align-middle">Job Number</th>
                                <th  class="text-end">Qty</th>
                                <th  class="text-center">Reff. Number</th>
                                <th  class="text-center">PIC</th>
                                <th  class="text-center">Time</th>                                
                                <th  class="text-center">Location</th>                           
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
                    <input type="text" class="form-control" id="fginventory_txt_total" readonly>
                </div>
            </div>
        </div>   	
    </div>
</div>
<script>
    $("#fginventory_divku").css('height', $(window).height()*72/100);
    $("#fginventory_btn_trash").click(function (e) { 
        if(confirm("Are You sure ?")){
            let pw = prompt("PIN");
            $.ajax({
                type: "get",
                url: "<?=base_url('Inventory/clearFG')?>",
                data: {inpin: pw},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='1'){
                        alertify.message(response.status[0].msg);
                        $("#fginventory_tbl tbody").empty();
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                }, error : function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }        
    });
    $("#fginventory_btn_gen").click(function (e) {         
        document.getElementById('fginventory_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('Inventory/getlist')?>",            
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("fginventory_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("fginventory_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("fginventory_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;                
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].CQTY).value();
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newText = document.createTextNode(response.data[i].CASSYNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].CMODEL);
                    newcell.appendChild(newText);
                    
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data[i].CLOTNO);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.data[i].SER_DOC);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(numeral(response.data[i].CQTY).format('0,0'));
                    newcell.style.cssText= "white-space: nowrap;text-align:right";
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.data[i].REFNO);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.data[i].FULLNAME);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(7);
                    newText = document.createTextNode(response.data[i].CDATE);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);                                                             

                    newcell = newrow.insertCell(8);
                    newText = document.createTextNode(response.data[i].CLOC);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);                                                             
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('fginventory_lblinfo').innerHTML = ttlrows>0 ? '': 'data not found';
                document.getElementById('fginventory_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
</script>