<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Ana Suryana">
	<title>WMS > Change Date</title>
    <link rel="icon" href="<?=base_url("assets/fiximgs/favicon.png")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/css/home.css")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/bootstrap/css/bootstrap.min.css")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/bootstrap_dp/css/bootstrap-datepicker.min.css")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/fontaw/css/all.css")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/alertify/css/alertify.min.css")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/alertify/css/themes/semantic.min.css")?>">
	<script type="text/javascript" src="<?=base_url("assets/jquery/jquery.min.js")?>"></script>
	<script type="text/javascript" src="<?=base_url("assets/bootstrap_dp/js/bootstrap-datepicker.min.js")?>"></script>
	<script type="text/javascript" src="<?=base_url("assets/numeral/numeral.min.js")?>"></script>
	<script type="text/javascript" src="<?=base_url("assets/js/moment.min.js")?>"></script>
	<script type="text/javascript" src="<?=base_url("assets/alertify/alertify.min.js")?>"></script>
</head>
<body>
<style>
    .anastylesel_sim{
        background: red;
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
    thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 26px;
    }
</style>
<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row" id="chgDateIth_stack0">
            <div class="col">
                <ul class="nav nav-tabs" id="chgDateIth_myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="chgDateIth_btn_tab_form_change_cancel_kitting_date" data-bs-toggle="tab" data-bs-target="#chgDateIth_tabCancel" type="button" role="tab" aria-controls="home" aria-selected="true">Change Cancel Kitting Date</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="chgDateIth_btn_tab_form_change_return_date" data-bs-toggle="tab" data-bs-target="#chgDateIth_tabReturn" type="button" role="tab">Change Return Date</button>
                    </li>
                </ul>
                <div class="tab-content" id="chgDateIth_myTabContent">
                    <div class="tab-pane fade show active" id="chgDateIth_tabCancel" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">
                                <div class="col-md-4 mb-1 pr-1">
                                    <div class="input-group input-group-sm mb-1">
                                        <span class="input-group-text" >PSN</span>
                                        <input type="text" class="form-control" id="chgDateIth_doc" onkeypress="chgDateIth_doc_eCK(event)" required maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1 pr-1">
                                    <div class="input-group input-group-sm mb-1">
                                        <span class="input-group-text" >Required Date</span>
                                        <input type="text" class="form-control" id="chgDateIth_reqdate"  readonly required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-1">
                                    <div class="table-responsive" id="chgDateIth_tbldetissu_div">
                                        <table id="chgDateIth_tbldetissu" class="table table-hover table-sm table-bordered caption-top">
                                            <caption>Preview <span class="fas fa-arrow-turn-down"></span></caption>
                                            <thead class="table-light">
                                                <tr class="first">
                                                    <th rowspan="2" class="align-middle">Item Code</th>
                                                    <th rowspan="2" class="text-end align-middle">Qty</th>
                                                    <th colspan="2" class="text-center">Date time</th>
                                                    <th rowspan="2" class="text-center"><input class="form-check-input" type="checkbox" id="chgDateIth_cid_ckall"></th>
                                                </tr>
                                                <tr class="second">
                                                    <th class="text-center">From</th>
                                                    <th class="text-center">To</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="chgDateIth_stack1">
                                <div class="col-md-12 mb-1 text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button title="New Entry" type="button" class="btn btn-outline-primary" id="chgDateIth_btn_new" onclick="chgDateIth_btn_new_eClick()"><i class="fas fa-file"></i></button>
                                        <button title="Save" type="button" class="btn btn-outline-primary" id="chgDateIth_btn_save" onclick="chgDateIth_btn_save_eClick()"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="chgDateIth_tabReturn" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">
                                <div class="col-md-4 mb-1 pr-1">
                                    <div class="input-group input-group-sm mb-1">
                                        <span class="input-group-text" >PSN</span>
                                        <input type="text" class="form-control" id="chgDateIth_rtn_doc" onkeypress="chgDateIth_rtn_doc_eCK(event)" required maxlength="45">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1 pr-1">
                                    <div class="input-group input-group-sm mb-1">
                                        <span class="input-group-text" >Required Date</span>
                                        <input type="text" class="form-control" id="chgDateIth_rtn_reqdate"  readonly required>
                                    </div>
                                </div>
                                <div class="col-md-5 mb-1 pr-1 text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button title="New Entry" type="button" class="btn btn-outline-primary" id="chgDateIth_btn_rtn_new" onclick="chgDateIth_btn_rtn_new_eClick()"><i class="fas fa-file"></i></button>
                                        <button title="Save" type="button" class="btn btn-outline-primary" id="chgDateIth_btn_rtn_save" onclick="chgDateIth_btn_rtn_save_eClick()"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-1">
                                    <div class="table-responsive" id="chgDateIth_tbl_rtn_div">
                                        <table id="chgDateIth_tbl_rtn" class="table table-hover table-sm table-bordered caption-top">
                                            <caption>Preview <span class="fas fa-arrow-turn-down"></span></caption>
                                            <thead class="table-light">
                                                <tr class="first">
                                                    <th rowspan="2" class="align-middle">Item Code</th>
                                                    <th rowspan="2" class="text-end align-middle">Qty</th>
                                                    <th colspan="2" class="text-center">Date time</th>
                                                    <th rowspan="2" class="text-center"></th>
                                                </tr>
                                                <tr class="second">
                                                    <th class="text-center">From</th>
                                                    <th class="text-center">To</th>
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
        </div>
    </div>
</div>

<script>
    $("#chgDateIth_tbldetissu_div").css('height', $(window).height()
        -document.getElementById('chgDateIth_stack0').offsetHeight
        -document.getElementById('chgDateIth_stack1').offsetHeight
        -90);
    $("#chgDateIth_reqdate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#chgDateIth_rtn_reqdate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#chgDateIth_reqdate").datepicker('update', new Date());
    $("#chgDateIth_rtn_reqdate").datepicker('update', new Date());
    function chgDateIth_doc_eCK(e){
        if(e.key === 'Enter')
        {
            chgDateIth_filter()
        }
    }

    function chgDateIth_rtn_doc_eCK(e){
        if(e.key === 'Enter')
        {
            chgDateIth_rtn_filter()
        }
    }

    function chgDateIth_filter(){
        if(chgDateIth_doc.value.trim().length!=19)
        {
            alertify.message('Please enter valid PSN Number')
            return
        }
        chgDateIth_doc.readOnly = true
        chgDateIth_tbldetissu.getElementsByTagName('tbody')[0].innerHTML = `<tr class="bg-info"><td colspan="5" class="text-center"><strong>Please wait</strong></td></tr>`
        $.ajax({
            url: "<?=base_url('ITH/raw_change_date_cancel')?>",
            data: {doc : chgDateIth_doc.value, date: chgDateIth_reqdate.value },
            dataType: "json",
            success: function (response) {
                if(response.data.length===0){
                    chgDateIth_tbldetissu.getElementsByTagName('tbody')[0].innerHTML = `<tr class="bg-info"><td colspan="5" class="text-center"><strong>Data is not found</strong></td></tr>`
                } else {
                    let mydes = document.getElementById("chgDateIth_tbldetissu_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = chgDateIth_tbldetissu.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("chgDateIth_tbldetissu");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let myCB = myfrag.getElementById('chgDateIth_cid_ckall')
                    myCB.onclick = () => {
                        let ttlrows = tableku2.rows.length
                        for (let i = 0; i<ttlrows; i++)
                        {
                            tableku2.rows[i].cells[4].getElementsByTagName('input')[0].checked = myCB.checked
                        }
                    }
                    tableku2.innerHTML = '';
                    response.data.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = arrayItem['ITH_ITMCD']
                        newcell = newrow.insertCell(1)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = arrayItem['ITH_QTY']
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = arrayItem['ITH_LUPDT']
                        newcell = newrow.insertCell(3)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = arrayItem['TO_LUPDT']
                        newcell = newrow.insertCell(4)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = `<input class="form-check-input" type="checkbox">`
                    })
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                }
            }, error: function(xhr, xopt, xthrow)
            {
                chgDateIth_doc.readOnly = false
                alertify.error(xthrow);
                chgDateIth_tbldetissu.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center"><strong>Please try again</strong></td></tr>`
            }
        });
    }
    function chgDateIth_rtn_filter(){
        if(chgDateIth_rtn_doc.value.trim().length!=19)
        {
            alertify.message('Please enter valid PSN Number')
            return
        }
        chgDateIth_rtn_doc.readOnly = true
        chgDateIth_tbl_rtn.getElementsByTagName('tbody')[0].innerHTML = `<tr class="bg-info"><td colspan="5" class="text-center"><strong>Please wait</strong></td></tr>`
        $.ajax({
            url: "<?=base_url('ITH/raw_change_date_return')?>",
            data: {doc : chgDateIth_rtn_doc.value, date: chgDateIth_rtn_reqdate.value },
            dataType: "json",
            success: function (response) {
                if(response.data.length===0){
                    chgDateIth_tbl_rtn.getElementsByTagName('tbody')[0].innerHTML = `<tr class="bg-info"><td colspan="5" class="text-center"><strong>Data is not found</strong></td></tr>`
                } else {
                    let mydes = document.getElementById("chgDateIth_tbl_rtn_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = chgDateIth_tbl_rtn.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("chgDateIth_tbl_rtn");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];                    
                    tableku2.innerHTML = '';
                    response.data.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = arrayItem['ITH_ITMCD']
                        newcell = newrow.insertCell(1)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = arrayItem['ITH_QTY']
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = arrayItem['ITH_LUPDT']
                        newcell = newrow.insertCell(3)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = arrayItem['TO_LUPDT']
                        newcell = newrow.insertCell(4)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = `<input class="form-check-input" type="checkbox">`
                    })
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                }
            }, error: function(xhr, xopt, xthrow)
            {
                chgDateIth_rtn_doc.readOnly = false
                alertify.error(xthrow);
                chgDateIth_tbl_rtn.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center"><strong>Please try again</strong></td></tr>`
            }
        });
    }

    function chgDateIth_btn_save_eClick(){
        const ttlrows = chgDateIth_tbldetissu.getElementsByTagName('tbody')[0].rows.length
        let aItems = []
        let aDates = []
        for(let i=0;i<ttlrows;i++)
        {
            if(chgDateIth_tbldetissu.getElementsByTagName('tbody')[0].rows[i].cells[4].getElementsByTagName('input')[0].checked)
            {
                aItems.push(chgDateIth_tbldetissu.getElementsByTagName('tbody')[0].rows[i].cells[0].innerText)
                aDates.push(chgDateIth_tbldetissu.getElementsByTagName('tbody')[0].rows[i].cells[2].innerText)
            }
        }
        if(aItems.length === 0)
        {
            alertify.message('nothing to be processed')
            return
        }
        if(confirm("Are you sure ?")){
            chgDateIth_btn_save.disable = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('ITH/change_cancelling_date')?>",
                data: {doc: chgDateIth_doc.value, date: chgDateIth_reqdate.value, items: aItems, dates : aDates },
                dataType: "json",
                success: function (response) {
                    chgDateIth_btn_save.disable = false
                    alertify.message(response.status.msg)
                }, error: (xhr, xopt, xthrow) => {                    
                    chgDateIth_btn_save.disable = false
                }
            })
        }
    }
    function chgDateIth_btn_rtn_save_eClick(){
        const ttlrows = chgDateIth_tbl_rtn.getElementsByTagName('tbody')[0].rows.length
        let aItems = []
        let aDates = []
        for(let i=0;i<ttlrows;i++)
        {
            if(chgDateIth_tbl_rtn.getElementsByTagName('tbody')[0].rows[i].cells[4].getElementsByTagName('input')[0].checked)
            {
                aItems.push(chgDateIth_tbl_rtn.getElementsByTagName('tbody')[0].rows[i].cells[0].innerText)
                aDates.push(chgDateIth_tbl_rtn.getElementsByTagName('tbody')[0].rows[i].cells[2].innerText)
            }
        }
        if(aItems.length === 0)
        {
            alertify.message('nothing to be processed')
            return
        }
        if(confirm("Are you sure ?")){
            chgDateIth_btn_rtn_new.disable = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('ITH/change_returned_psn_date')?>",
                data: {doc: chgDateIth_rtn_doc.value, date: chgDateIth_rtn_reqdate.value, items: aItems, dates : aDates },
                dataType: "json",
                success: function (response) {
                    chgDateIth_btn_rtn_save.disable = false
                    alertify.message(response.status.msg)
                }, error: (xhr, xopt, xthrow) => {                    
                    chgDateIth_btn_rtn_save.disable = false
                }
            })
        }
    }

    $('#chgDateIth_reqdate').datepicker()
    .on('changeDate', function(e) {
        chgDateIth_filter()
    });
    $('#chgDateIth_rtn_reqdate').datepicker()
    .on('changeDate', function(e) {
        chgDateIth_rtn_filter()
    });

    function chgDateIth_btn_new_eClick(){
        chgDateIth_doc.value = ''
        chgDateIth_tbldetissu.getElementsByTagName('tbody')[0].innerHTML = ''
        chgDateIth_doc.readOnly = false
        chgDateIth_doc.focus()
    }
    function chgDateIth_btn_rtn_new_eClick(){
        chgDateIth_rtn_doc.value = ''
        chgDateIth_tbl_rtn.getElementsByTagName('tbody')[0].innerHTML = ''
        chgDateIth_rtn_doc.readOnly = false
        chgDateIth_rtn_doc.focus()
    }
</script>
<script type="text/javascript" src="<?=base_url("assets/js/popper.min.js")?>"></script>
<script type="text/javascript" src="<?=base_url("assets/bootstrap/js/bootstrap.min.js")?>"></script>
</body>
</html>