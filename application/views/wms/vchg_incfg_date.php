<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row">   
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Reff No</span>
                    <input type="text" class="form-control" id="chgincfgdt_oldreff" onkeypress="chgincfgdt_oldreff_e_kp(event)" required>                   
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                
                    <span class="input-group-text" >Job Number</span>                    
                    <input type="text" class="form-control" id="chgincfgdt_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="chgincfgdt_olditemcd" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Qty</span>
                    <input type="text" class="form-control" id="chgincfgdt_oldqty" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h4>Transaction History</h4>
                <input type="hidden" id="chgincfgdt_txt_activewh">
                <div class="table-responsive" id="chgincfgdt_divku">
                    <table id="chgincfgdt_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr> 
                                <th  class="align-middle">FORM</th>
                                <th  class="align-middle">Warehouse</th>
                                <th  class="align-middle">Location</th>
                                <th  class="align-middle">Document</th>
                                <th  class="text-right">Qty</th>                                                              
                                <th  class="align-middle">Time</th>
                                <th  class="align-middle">PIC</th>
                            </tr>                           
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1 text-center">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" title="select the data on the table above">Old Data</span>
                    <input type="text" class="form-control" id="chgincfgdt_olddata" required readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-center">                
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >New Data</span>
                    <input type="text" class="form-control" autocomplete="off" id="chgincfgdt_newdata" data-td-toggle="datetimepicker" data-td-target="#chgincfgdt_newdata" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="chgincfgdt_btn_new" onclick="chgincfgdt_btn_new_eClick()"><i class="fas fa-file"></i></button>                    
                    <button title="Save" type="button" class="btn btn-outline-primary" id="chgincfgdt_btn_save" onclick="chgincfgdt_btn_save_eClick()"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function chgincfgdt_btn_save_eClick(){
        const txtdateold = document.getElementById('chgincfgdt_olddata')
        const txtdatenew = document.getElementById('chgincfgdt_newdata')
        const txtreffno = document.getElementById('chgincfgdt_oldreff')
        if(txtdateold.value.length==0){
            txtdateold.focus()
            alertify.warning("Old Data cold not be empty")
            return;
        }
        if(txtreffno.value.length==0){
            alertify.warning("Reff No Data cold not be empty")
            txtreffno.focus()
            return;
        }
        if(txtdatenew.value.length==0){
            alertify.warning("New Data cold not be empty")
            txtdatenew.focus()
            return;
        }
        if(confirm("Are you sure ?")){
            $.ajax({
                type: "post",
                url: "<?=base_url('ITH/change_dt_byreff')?>",
                data: {inreffno: txtreffno.value, inform_trigger_inc: 'INC-WH-FG'
                    , inform_trigger_out: 'OUT-QA-FG', innewdate: txtdatenew.value },
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==1){
                        alertify.success(response.status[0].msg)
                        chgincfgdt_btn_new_eClick()
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);                
                }
            });
        }
    }

    var chgincfgdt_newdata_pub = new tempusDominus.TempusDominus(chgincfgdt_newdata,
        {
            localization: {
                locale : 'en',
                format : 'yyyy-MM-dd HH:mm:ss'
            },
            restrictions : {
                maxDate: new Date
            }
        }
    )
    
    function chgincfgdt_oldreff_e_kp(e){
        if(e.which==13){
            const reffno = document.getElementById('chgincfgdt_oldreff').value;
            document.getElementById('chgincfgdt_oldreff').readOnly = true;
            $("#chgincfgdt_tbl tbody").empty()
            $.ajax({
                type: "get",
                url: "<?=base_url("SER/getproperties_n_tx")?>",
                data: {inid: reffno},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==1){
                        document.getElementById('chgincfgdt_oldjob').value = response.data[0].SER_DOC;
                        document.getElementById('chgincfgdt_olditemcd').value = response.data[0].SER_ITMID;
                        document.getElementById('chgincfgdt_oldqty').value = numeral(response.data[0].SER_QTY).format(',');
                        const ttlrows = response.tx.length
                        let mydes = document.getElementById("chgincfgdt_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("chgincfgdt_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("chgincfgdt_tbl");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        for(let i=0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1);
                            newrow.style.cssText = "cursor:pointer"
                            newrow.onclick = () => {
                                chgincfgdt_selectrow(
                                    {lupdt: response.tx[i].ITH_LUPDT, form: response.tx[i].ITH_FORM, basedata: response}
                                )
                            }
                            newcell = newrow.insertCell(0);
                            newcell.innerHTML = response.tx[i].ITH_FORM
                            newcell = newrow.insertCell(1);
                            newcell.innerHTML = response.tx[i].ITH_WH
                            newcell = newrow.insertCell(2);
                            newcell.innerHTML = response.tx[i].ITH_LOC
                            newcell = newrow.insertCell(3);
                            newcell.innerHTML = response.tx[i].ITH_DOC
                            newcell = newrow.insertCell(4);
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(response.tx[i].ITH_QTY).format(',')
                            newcell = newrow.insertCell(5);
                            newcell.innerHTML = response.tx[i].ITH_LUPDT
                            newcell = newrow.insertCell(6);
                            newcell.innerHTML = response.tx[i].PIC
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    } else {
                        document.getElementById('chgincfgdt_oldreff').readOnly = false;
                        alertify.message(response.status[0].msg)
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    document.getElementById('chgincfgdt_oldreff').readOnly = false;
                }
            });
        }
    }
    function chgincfgdt_btn_new_eClick(){
        const txtoldreff = document.getElementById('chgincfgdt_oldreff')
        txtoldreff.readOnly = false
        txtoldreff.value = "";
        document.getElementById('chgincfgdt_oldjob').value = "";
        document.getElementById('chgincfgdt_olditemcd').value = "";
        document.getElementById('chgincfgdt_oldqty').value = "";
        txtoldreff.focus();
        $("#chgincfgdt_tbl tbody").empty()
        $('#chgincfgdt_newdata').datetimepicker('destroy')
        $('#chgincfgdt_newdata').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            icons: {
                time: 'fas fa-clock'
            },
            useStrict: true,
            maxDate: new Date,
            minDate: false
        });
        document.getElementById('chgincfgdt_olddata').value = ''
    }
    function chgincfgdt_selectrow(pdata){
        if(pdata.form==="INC-WH-FG" || pdata.form==="OUT-QA-FG"){            
            const ttldata = pdata.basedata.tx.length
            let theMinDate = '';
            let theMaxDate = '';
            for(let i=0;i<ttldata;i++){
                if(pdata.basedata.tx[i].ITH_FORM === "INC-QA-FG"){
                    theMinDate = pdata.basedata.tx[i].ITH_LUPDT.substr(0,19)
                    break;
                }                
            }
            for(let i=0;i<ttldata;i++){
                if(pdata.basedata.tx[i].ITH_FORM === "INC-WH-FG"){
                    theMaxDate = pdata.basedata.tx[i].ITH_LUPDT.substr(0,19)
                    break;
                }
            }            
            console.log(`the minimum date : ${theMinDate}`)
            console.log(`the maximum date : ${theMaxDate}`)
            document.getElementById('chgincfgdt_olddata').value = pdata.lupdt.substr(0,19)
            $('#chgincfgdt_newdata').datetimepicker('destroy')
            $('#chgincfgdt_newdata').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
                    time: 'fas fa-clock'
                },
                useStrict: true,
                maxDate: theMaxDate,
                minDate: theMinDate
            });
        } else {
            document.getElementById('chgincfgdt_olddata').value = ''
        }
    }
</script>