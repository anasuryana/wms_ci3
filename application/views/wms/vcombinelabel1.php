<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Reff Code</label>                    
                    <input type="text" class="form-control" id="combinelbl1_reffcd" placeholder="Scan or type reff number here" maxlength="200">                                         
                    <span class="input-group-text" ><i class="fas fa-qrcode"></i></span>                    
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12 mb-1">
                <span id="combinelbl1_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive" id="combinelbl1_divku">
                    <table id="combinelbl1_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                        <thead class="table-light">
                            <tr>
                                
                                <th  class="align-middle">Reff No</th>                                
                                <th  class="align-middle">Job Number</th>
                                <th  class="align-middle">Item Code</th>
                                <th  class="align-middle">Item Name</th>
                                <th  class="text-end">QTY</th>                                                           
                            </tr>                          
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success" role="alert" id="combinelbl1_div_reffjob">
                    ...
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">New Reff Code</label>                    
                    <input type="text" class="form-control" id="combinelbl1_reffcdnew" >
                    <input type="hidden" id="combinelbl1_reffcdnew_rawtext" >                      
                    <span class="input-group-text" ><i class="fas fa-qrcode"></i></span>                                        
                    <button class="btn btn-primary" id="combinelbl1_btnmod"><i class="fas fa-search"></i></button>                    
                </div>
            </div>
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Job</label>                    
                    <input type="text" class="form-control" id="combinelbl1_jobnew" readonly > 
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Qty</label>                    
                    <input type="text" class="form-control" id="combinelbl1_qtynew" readonly > 
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col text-center ">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="combinelbl1_btnnew"><i class="fas fa-file"></i></button>
                    <button class="btn btn-primary" type="button" id="combinelbl1_btn_save"><i class="fas fa-save"></i> </button>                    
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#combinelbl1_divku").css('height', $(window).height()*57/100);
    $("#combinelbl1_reffcdnew").keypress(function (e) { 
        if(e.which==13){
            let mtbl = document.getElementById('combinelbl1_tbl');
            let mtbltr = mtbl.getElementsByTagName('tr');
            let ttlrows = mtbltr.length;
            if(ttlrows<3){
                alertify.warning('Please entry at least 2 labels');
                return;
            }
            let mreff = $(this).val();            
            let mitemcd = '';
            let mjob = '';
            let mqty = 0;
            document.getElementById('combinelbl1_reffcdnew_rawtext').value=mreff;
            if(mreff.includes("|")){
                let ar = mreff.split("|");
                mreff = ar[5].substr(2,ar[5].length-2);
                mitemcd = ar[0].substr(2,ar[0].length-2).toUpperCase()
                mjob = ar[2].substr(2,ar[2].length-2);
                mjob = mjob.substr(3,5);
                mqty = ar[3].substr(2,ar[3].length-2);
                document.getElementById('combinelbl1_reffcdnew').value=mreff;
            }
            
            let ttlqty = 0;
            let tempqty = 0;
            let biggestjob = '';
            let joblist = [];
            for(let i=1;i<ttlrows;i++){
                joblist.push(mtbl.rows[i].cells[1].innerText);
                ttlqty += numeral(mtbl.rows[i].cells[4].innerText).value();
                if(numeral(mtbl.rows[i].cells[4].innerText).value()>tempqty){
                    tempqty = numeral(mtbl.rows[i].cells[4].innerText).value();
                    biggestjob = mtbl.rows[i].cells[1].innerText;
                }

                if(mtbl.rows[i].cells[0].innerText==mreff){
                    alertify.warning('the label is already in the list below');
                    document.getElementById('combinelbl1_reffcdnew').value='';
                    return;
                }
                let tempitemcd = mtbl.rows[i].cells[2].innerText.trim().toUpperCase();
                if(tempitemcd!=mitemcd){
                    alertify.warning('Currently, system only allow to combine same Assy Code ' + tempitemcd + ' != ' + mitemcd);
                    document.getElementById('combinelbl1_reffcdnew').value='';
                    return;
                }
            }
            joblist = [...new Set(joblist)];
            if(mqty!=ttlqty){
                alertify.warning('Qty on label must be same with the total qty');
                document.getElementById('combinelbl1_reffcdnew').value='';
                return;                
            }

            let newjob ='';
            let abiggestjob = biggestjob.split('-');
                  
            if(joblist.length>1){
                if(mjob.substr(0, 1) !='C'){
                    alertify.warning('The job on the label must be started with "C" character ');
                    document.getElementById('combinelbl1_reffcdnew').value='';
                    return;
                }
                if(mjob.substr(1, mjob.length-1).toUpperCase()!=abiggestjob[1].toUpperCase()){
                    alertify.warning('Job Number on the label is not match with required Job ('+mjob.substr(1, mjob.length-1)+') != ('+abiggestjob[1]+')');
                    document.getElementById('combinelbl1_reffcdnew').value='';
                    return;
                }
                newjob  = abiggestjob[0] + '-C' + abiggestjob[1] + '-' + abiggestjob[2];        
            } else {
                if(mjob.substr(0, 1) !='0'){
                    if(mjob!=abiggestjob[1]){
                        alertify.warning('The job on the label is not same, please recheck the label ');
                        document.getElementById('combinelbl1_reffcdnew').value='';
                        return;
                    }                    
                }
                if(mjob.substr(1, mjob.length-1).toUpperCase()!=abiggestjob[1].toUpperCase()){
                    if(mjob!=abiggestjob[1]){ 
                        alertify.warning('Job Number on the label is not match with required Job ('+mjob.substr(1, mjob.length-1)+') != ('+abiggestjob[1]+')');
                        document.getElementById('combinelbl1_reffcdnew').value='';
                        return;
                    }
                }
                newjob  = abiggestjob[0] + '-' + abiggestjob[1] + '-' + abiggestjob[2];        
            }
            
            document.getElementById('combinelbl1_qtynew').value=numeral(mqty).format(',');
            document.getElementById('combinelbl1_jobnew').value=newjob;
            document.getElementById('combinelbl1_reffcdnew').readOnly = true;            
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/combine2_validate')?>",
                data: {inid: mreff},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){
                        document.getElementById('combinelbl1_btn_save').focus();
                    } else {
                        document.getElementById('combinelbl1_qtynew').value='';
                        document.getElementById('combinelbl1_jobnew').value='';
                        document.getElementById('combinelbl1_reffcdnew').readOnly = false;
                        document.getElementById('combinelbl1_reffcdnew').value='';
                        alertify.warning(response.status[0].msg);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });

        }
    });
    $("#combinelbl1_btn_save").click(function (e) { 
        let mtbl = document.getElementById('combinelbl1_tbl');
        let mtbltr = mtbl.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let newrawtext = document.getElementById('combinelbl1_reffcdnew_rawtext').value;
        let newjob = document.getElementById('combinelbl1_jobnew').value;
        let reffnonew = document.getElementById('combinelbl1_reffcdnew').value;
        let qtynew = document.getElementById('combinelbl1_qtynew').value;
        if(reffnonew.length!=16){
            if(reffnonew.length!=18){
                alertify.message('New Reff Code is not valid, please try to press enter to confirm');
                document.getElementById('combinelbl1_reffcdnew').focus();
                return;
            }
        }
        if(qtynew.length==0){
            alertify.warning('please press enter first');
            document.getElementById('combinelbl1_reffcdnew').focus();
            return;
        }
        if(ttlrows>1){
            let konfirm = confirm('Are You sure ?');
            if(konfirm){
                let aid = [];
                let aitemcd = [];
                let ajob = [];
                let aqty = [];
                let qtyt = 0;
                let mnewreff =  document.getElementById('combinelbl1_reffcdnew').value;
                for(let i=1;i<ttlrows;i++){
                    aid.push(mtbl.rows[i].cells[0].innerText);
                    ajob.push(mtbl.rows[i].cells[1].innerText.trim());
                    aitemcd.push(mtbl.rows[i].cells[2].innerText.trim());
                    aqty.push(numeral(mtbl.rows[i].cells[4].innerText.trim()).value());
                    qtyt += numeral(mtbl.rows[i].cells[4].innerText.trim()).value();
                }
                if(aid.length>1){
                    
                    document.getElementById('combinelbl1_btn_save').disabled=true;
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('SER/combine1_save')?>",
                        data: {inid: aid, injob: ajob, innewrawtext : newrawtext, innewjob: newjob,
                        inqty : aqty, inqtyt: qtyt, innewid : mnewreff, initemcd: aitemcd},
                        dataType: "json",
                        success: function (response) {
                            if(response.status[0].cd!='0'){                                
                                alertify.success(response.status[0].msg);
                                document.getElementById('combinelbl1_div_reffjob').innerHTML = '';
                                combinelbl1_new();
                            } else {
                                alertify.message(response.status[0].msg);
                            }
                        }, error: function(xhr, xopt, xthrow){
                            alertify.error(xthrow);
                        }
                    });
                } else {
                    alertify.message('combine 1 label ?');
                }
            } else {
                alertify.message('You are not sure');
            }
        } else {
            alertify.message('nothing to be processed');
        }
    });
    $("#combinelbl1_reffcd").keypress(function (e) {
        if(e.which==13){
            let mreff = $(this).val();
            if(mreff.includes("|")){
                let ar = mreff.split("|");
                mreff = ar[5].substr(2,ar[5].length-2);
                $(this).val(mreff);
            }
            
            if(mreff.length==18 || mreff.length==16){
                let mtbl = document.getElementById('combinelbl1_tbl');
                let mtbltr = mtbl.getElementsByTagName('tr');
                let ttlrows = mtbltr.length;
                for(let i=0;i<ttlrows;i++){                    
                    if(mtbl.rows[i].cells[0].innerText==mreff){
                        alertify.warning('the label is already in the list below');
                        document.getElementById('combinelbl1_reffcd').value='';
                        return;
                    }
                }
                if(document.getElementById('combinelbl1_reffcd').readOnly){
                    alertify.message('Please wait');
                    return;
                }
                document.getElementById('combinelbl1_reffcd').readOnly=true;
                document.getElementById('combinelbl1_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
                $.ajax({
                    type: "post",
                    url: "<?=base_url('SER/combine1_prep')?>",
                    data: {inreffcd: mreff},
                    dataType: "json",
                    success: function (response) {
                        document.getElementById('combinelbl1_lblinfo').innerHTML = '';
                        document.getElementById('combinelbl1_reffcd').readOnly=false;
                        document.getElementById('combinelbl1_reffcd').value='';
                        if(response.status[0].cd!='0'){
                            document.getElementById('combinelbl1_reffcd').value=''
                            let mtbl = document.getElementById('combinelbl1_tbl');
                            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
                            let mtbltr = mtbl.getElementsByTagName('tr');
                            let ttlrows = response.data.length;
                            let ttlrowsTEMP = mtbltr.length;
                            let newrow, newcell, newText;
                            for(let i=1;i<ttlrowsTEMP;i++){                    
                                if(mtbl.rows[i].cells[2].innerText.trim().toUpperCase()!=response.data[0].SER_ITMID.trim().toUpperCase()){
                                    alertify.warning('Currently, system only allow to combine same Assy Code '); 
                                    return;
                                }
                            }
                            for(let i=0;i<ttlrows;i++){
                                newrow = tableku2.insertRow(-1);                                                                
                                newcell = newrow.insertCell(0);
                                newText = document.createTextNode(response.data[i].SER_ID);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(1);
                                newText = document.createTextNode(response.data[i].SER_DOC.toUpperCase());
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(2);
                                newText = document.createTextNode(response.data[i].SER_ITMID);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(3);
                                newcell.innerText = response.data[i].MITM_ITMD1
                                newcell = newrow.insertCell(4);
                                newcell.classList.add('text-end')
                                newcell.innerHTML = numeral(response.data[i].SER_QTY).value()
                            }
                            ttlrows = mtbltr.length;
                            let tempqty = 0;
                            let biggestjob = '';
                            let joblist = [];
                            for(let i=1;i<ttlrows;i++){
                                joblist.push(mtbl.rows[i].cells[1].innerText);
                                if(numeral(mtbl.rows[i].cells[4].innerText).value()>tempqty){
                                    tempqty = numeral(mtbl.rows[i].cells[4].innerText).value();
                                    biggestjob = mtbl.rows[i].cells[1].innerText;
                                }
                            }
                            joblist = [...new Set(joblist)];
                            let abiggestjob = biggestjob.split('-');                            
                            if(joblist.length>1){
                                document.getElementById('combinelbl1_div_reffjob').innerHTML = 'the new job name should be <b>C'+abiggestjob[1]+'</b>';
                            } else {
                                document.getElementById('combinelbl1_div_reffjob').innerHTML = 'the new job name should be <b>'+abiggestjob[1]+'</b>';
                            }
                        } else {
                            alertify.warning(response.status[0].msg);
                        }
                        
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            } else {
                alertify.warning('Invalid Reff No');
                document.getElementById('combinelbl1_reffcd').value='';
            }
        }
    });

    function combinelbl1_new(){
        document.getElementById('combinelbl1_reffcd').value='';
        document.getElementById('combinelbl1_reffcd').readOnly=false;
        document.getElementById('combinelbl1_reffcdnew').readOnly = false;
        document.getElementById('combinelbl1_btn_save').disabled=false;
        document.getElementById('combinelbl1_reffcd').focus();
        $("#combinelbl1_tbl tbody").empty();        
        document.getElementById('combinelbl1_reffcdnew').value='';
        document.getElementById('combinelbl1_jobnew').value='';
        document.getElementById('combinelbl1_qtynew').value='';
        document.getElementById('combinelbl1_reffcdnew_rawtext').value='';
        document.getElementById('combinelbl1_div_reffjob').innerHTML = '';
    }
    $("#combinelbl1_btnnew").click(function (e) { 
        combinelbl1_new();
    });
    
</script>