<div style="padding: 10px">
	<div class="container-xxl">        
        <div class="row">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">WH Code</label>                    
                    <input type="text" class="form-control" id="dspsfg_whcode"  value="AFWH9SC" readonly>
                </div>
            </div> 
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">WH Name</label>                    
                    <input type="text" class="form-control" id="dspsfg_whname" value="Scrap of Finished Goods" readonly>
                </div>
            </div> 
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Dispose Date</label>                    
                    <input type="text" class="form-control" id="dspsfg_date" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="dspsfg_btnplus" >Get Stock</button>
                    <button class="btn btn-outline-primary" id="dspsfg_btnsave"><i class="fas fa-save"></i></button>
                </div>
            </div>                 
            <div class="col-md-6 mb-1 text-right">
                <span class="badge bg-info" id="dspsfg_lblinfo"></span>
            </div>
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="dspsfg_divku">
                    <table id="dspsfg_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;font-size:85%">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" class="align-middle">No</th>
                                <th rowspan="2" class="align-middle">ID</th>
                                <th rowspan="2" class="align-middle">Item Code</th>
                                <th rowspan="2" class="align-middle">Item Name</th>
                                <th colspan="2" class="text-center">Qty</th>
                            </tr>
                            <tr>
                                <th class="text-right">Stock</th>
                                <th class="text-right">Dispose</th>
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
    $("#dspsfg_divku").css('height', $(window).height()*60/100);
    $("#dspsfg_btnsave").click(function (e) {
        let aid = [];
        let aitem = [];
        let aqty = [];
        let mdate = document.getElementById("dspsfg_date").value;
        let mwh = document.getElementById("dspsfg_whcode").value;
        let otbl = document.getElementById("dspsfg_tbl");
        let otblbody = otbl.getElementsByTagName("tbody")[0];
        let ttlrows = otblbody.getElementsByTagName("tr").length;
        for(let i=0; i< ttlrows; i++){
            let qtystock = numeral(otblbody.rows[i].cells[4].innerText).value();
            let qtydis = numeral(otblbody.rows[i].cells[5].innerText).value();
            if(qtydis > qtystock){
                alertify.warning("Dispose qty is greater than stock qty !"); otblbody.rows[i].cells[5].focus();return;
            }
            if(qtydis>0) {
                aid.push(otblbody.rows[i].cells[1].innerText);
                aitem.push(otblbody.rows[i].cells[2].innerText);
                aqty.push(qtydis);
            }
        }        
        if(aitem.length>0){            
            let konf = confirm("Are You sure ?");
            if(konf){
                $.ajax({
                    type: "post",
                    url: "<?=base_url('ITH/dispose_fg_save')?>",
                    data: {inid: aid,  initem: aitem, inqty: aqty, indate: mdate, inwh : mwh},
                    dataType: "json",
                    success: function (response) {
                        if(response.info[0].cd =='0'){
                            alertify.warning(response.info[0].msg);
                        } else {
                            $("#dspsfg_tbl tbody").empty();
                            alertify.success(response.info[0].msg);
                        }
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            } else {
                alertify.message("You are not sure");
            }
        }
    });
    $("#dspsfg_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#dspsfg_date").datepicker('update', new Date());
    $("#dspsfg_btnplus").click(function (e) { 
        let mwh = document.getElementById("dspsfg_whcode").value;
        document.getElementById("dspsfg_lblinfo").innerHTML="Please wait...";
        $("#dspsfg_tbl tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/getdisposefg')?>",
            data: {inwh: mwh },
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let tohtml = '';
                document.getElementById("dspsfg_lblinfo").innerHTML= ttlrows+" row(s) found";
                for(let i=0;i<ttlrows;i++){
                    tohtml += "<tr>"
                    +"<td>"+(i+1)+"</td>"
                    +"<td>"+response.data[i].ITH_SER.trim()+"</td>"
                    +"<td>"+response.data[i].ITH_ITMCD.trim()+"</td>"
                    +"<td>"+response.data[i].MITM_ITMD1.trim()+"</td>"
                    +"<td class='text-right'>"+numeral(response.data[i].ITH_QTY).format('0,0')+"</td>"
                    +"<td class='text-right'>"+numeral(response.data[i].ITH_QTY).value()+"</td>"
                    +"</tr>";
                }
                $("#dspsfg_tbl tbody").html(tohtml);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });        
    });
</script>