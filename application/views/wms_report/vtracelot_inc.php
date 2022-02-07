<style type="text/css">
	.tagbox-remove{
		display: none;
	}
</style>
<div style="padding: 10px">
	<div class="container-xxl">       
        <div class="row">				
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >DO Number</span>                    
                    <input type="text" class="form-control" id="trace_inc_dono" required>                   
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="trace_inc_txt_itmcd" required>                   
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Lot Number</span>                    
                    <input type="text" class="form-control" id="trace_inc_txt_itmlot" required >                   
                </div>
            </div>           
        </div>         
        <div class="row">            
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="trace_inc_btnsearch" class="btn btn-outline-primary" ><i class="fas fa-search"></i> Search</button>                   
                </div>                
            </div>   
            <div class="col-md-6 mb-1 text-end">
                <span id="trace_inc_lblinfo" class="badge bg-info"></span>
            </div>   
        </div>           
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="trace_inc_divku">
                    <table id="trace_inc_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>                                
                                <th>DO Number</th>                                 
                                <th>Item Code</th>           
                                <th>Lot No</th>
                                <th>QTY</th>                                                           
                                <th>Time</th>                          
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
    $("#trace_inc_divku").css('height', $(window).height()*70/100);
    function trace_inc_e_search(){
        let mdo = document.getElementById('trace_inc_dono').value;
        let mitmcd = document.getElementById('trace_inc_txt_itmcd').value;
        let mitmlot = document.getElementById('trace_inc_txt_itmlot').value;
        document.getElementById('trace_inc_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $("#trace_inc_tbl tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/tracelot')?>",
            data: {indo: mdo, initmcd: mitmcd, initmlot: mitmlot},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    let ttldata = response.data.length;
                    document.getElementById('trace_inc_lblinfo').innerHTML = ttldata + ' row(s) found';
                    let mydes = document.getElementById("trace_inc_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("trace_inc_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("trace_inc_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];                                
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for(let i =0; i<ttldata; i ++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);            
                        newText = document.createTextNode(response.data[i].RCVSCN_DONO);            
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].RCVSCN_ITMCD);
                        newcell.appendChild(newText);                       
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].RCVSCN_LOTNO);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(numeral(response.data[i].RCVSCN_QTY).format(','));
                        newcell.appendChild(newText);
                        newcell.style.cssText= "white-space: nowrap;text-align:right";
                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(response.data[i].RCVSCN_LUPDT);
                        newcell.appendChild(newText);
                    }                    
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);

                } else {
                    document.getElementById('trace_inc_lblinfo').innerHTML = response.status[0].msg;                    
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#trace_inc_txt_txno").keypress(function (e) { 
        if(e.which==13){
            trace_inc_e_search();
        }
    });
    $("#trace_inc_txt_itmcd").keypress(function (e) { 
        if(e.which==13){
            trace_inc_e_search();
        }
    });
    $("#trace_inc_txt_itmlot").keypress(function (e) { 
        if(e.which==13){
            trace_inc_e_search();
        }
    });

    $("#trace_inc_btnsearch").click(function (e) {
        trace_inc_e_search();
    });
   
</script>