<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Ana Suryana">
	<title>Reconsiliator</title>
    <link rel="icon" href="<?=base_url("assets/fiximgs/favicon.png")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/css/home.css")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/bootstrap/css/bootstrap.min.css")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/bootstrap_dp/css/bootstrap-datepicker.min.css")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/fontaw/css/all.css")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/alertify/css/alertify.min.css")?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/alertify/css/themes/semantic.min.css")?>">
	<script type="text/javascript" src="<?=base_url("assets/jquery/jquery.min.js")?>"></script>
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
</style>
<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row" id="recon_stack0">
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Item Code</span>
                    <input type="text" class="form-control" id="recon_item" onkeypress="recon_item_eCK(event)" required maxlength="50" onclick="this.select()">
                </div>
            </div>
            <div class="col-md-4 mb-1 pr-1">

            </div>
        </div>
        <div class="row">
            <div class="col mb-1">
                <div class="table-responsive" id="recon_tbldetissu_div">
                    <table id="recon_tbldetissu" class="table table-hover table-sm table-bordered caption-top">
                        <caption>Table1 <span class="fas fa-arrow-turn-down"></span></caption>
                        <thead class="table-light">
                            <tr>
                                <th>Document</th>
                                <th>Item Code</th>
                                <th class="text-end">Response</th>
                                <th class="text-end">Request</th>
                                <th class="text-end">Request + Response</th>
                                <th class="text-end">FG</th>
                                <th class="text-end">USE</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col mb-1">
                <div class="table-responsive" id="recon_tbldetissu2_div">
                    <table id="recon_tbldetissu2" class="table table-hover table-sm table-bordered caption-top">
                        <caption>Table2 <span class="fas fa-arrow-turn-down"></span></caption>
                        <thead class="table-light">
                            <tr>
                                <th>Document</th>
                                <th>Item Code</th>
                                <th class="text-end">Response</th>
                                <th>Nomor Pengajuan</th>
                                <th>id</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col mb-1">
                <div class="table-responsive" id="recon_tbldetissu3_div">
                    <table id="recon_tbldetissu3" class="table table-hover table-sm table-bordered caption-top">
                        <caption>Serial Table<span class="fas fa-arrow-turn-down"></span></caption>
                        <thead class="table-light">
                            <tr>
                                <th>WO</th>
                                <th>Reff</th>
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
    function recon_item_eCK(e){
        if(e.key === 'Enter')
        {
            recon_tbldetissu2.getElementsByTagName('tbody')[0].innerHTML = ''
            recon_filter()
        }
    }

    function recon_filter(){
        recon_item.readOnly = true
        recon_tbldetissu.getElementsByTagName('tbody')[0].innerHTML = `<tr class="bg-info"><td colspan="7" class="text-center"><strong>Please wait</strong></td></tr>`
        $.ajax({
            url: "<?=base_url('DELVHistory/reconsiliator')?>",
            data: {itemcd : recon_item.value,},
            dataType: "json",
            success: function (response) {
                recon_item.readOnly = false
                if(response.data.length===0){
                    recon_tbldetissu.getElementsByTagName('tbody')[0].innerHTML = `<tr class="bg-info"><td colspan="7" class="text-center"><strong>Data is not found</strong></td></tr>`
                } else {
                    let mydes = document.getElementById("recon_tbldetissu_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = recon_tbldetissu.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("recon_tbldetissu");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML = '';
                    response.data.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.style.cssText = 'cursor:pointer'
                        newcell.innerHTML = arrayItem['RPSTOCK_REMARK']
                        newcell.onclick = () => {
                            recon_filter2({
                                doc : arrayItem['RPSTOCK_REMARK'],
                                item : arrayItem['RPSTOCK_ITMNUM']
                            })
                        }

                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = arrayItem['RPSTOCK_ITMNUM']

                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = arrayItem['RESPONSEQT']

                        newcell = newrow.insertCell(3)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = arrayItem['BOMQT']

                        newcell = newrow.insertCell(4)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = arrayItem['BAL']

                        newcell = newrow.insertCell(5)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = arrayItem['FGQT']

                        newcell = newrow.insertCell(6)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = arrayItem['PER']
                    })
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                }
            }, error: function(xhr, xopt, xthrow)
            {
                recon_item.readOnly = false
                alertify.error(xthrow);
                recon_tbldetissu.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="7" class="text-center"><strong>Please try again</strong></td></tr>`
            }
        });
    }

    function recon_filter2(pdata){
        recon_tbldetissu2.getElementsByTagName('tbody')[0].innerHTML = `<tr class="bg-info"><td colspan="5" class="text-center"><strong>Please wait</strong></td></tr>`
        $.ajax({
            url: "<?=base_url('DELVHistory/reconsiliator2')?>",
            data: {itemcd: pdata.item, doc: pdata.doc},
            dataType: "json",
            success: function (response) {
                if(response.data.length===0){
                    recon_tbldetissu2.getElementsByTagName('tbody')[0].innerHTML = `<tr class="bg-info"><td colspan="5" class="text-center"><strong>Data is not found</strong></td></tr>`
                } else {
                    let mydes = document.getElementById("recon_tbldetissu2_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = recon_tbldetissu2.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("recon_tbldetissu2");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML = '';
                    response.data.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = arrayItem['RPSTOCK_REMARK']

                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = arrayItem['RPSTOCK_ITMNUM']

                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = arrayItem['RPSTOCK_QTY']

                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = arrayItem['RPSTOCK_NOAJU']

                        newcell = newrow.insertCell(4)
                        newcell.style.cssText = 'cursor:pointer'
                        newcell.innerHTML = arrayItem['id']
                        newcell.onclick = () => {
                            recon_fix({
                                id : arrayItem['id']
                            })
                        }
                    })
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)


                    mydes = document.getElementById("recon_tbldetissu3_div");
                    myfrag = document.createDocumentFragment();
                    cln = recon_tbldetissu3.cloneNode(true);
                    myfrag.appendChild(cln);
                    tabell = myfrag.getElementById("recon_tbldetissu3");
                    tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML =
                     '';
                    response.dataser.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = arrayItem['SER_DOC']

                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = arrayItem['SER_ID']
                    })
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                }
            }, error: function(xhr, xopt, xthrow)
            {
                alertify.error(xthrow);
                recon_tbldetissu2.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center"><strong>Please try again</strong></td></tr>`
            }
        });
    }

    function recon_fix(data){
        if(confirm('are you sure ?')){
            $.ajax({
                type: "post",
                url: "<?=base_url('DELV/cancelBookExbcById')?>",
                data: {id : data.id},
                dataType: "json",
                success: function (response) {
                    recon_tbldetissu3.getElementsByTagName('tbody')[0].innerHTML = ``
                    alertify.message(response.status.msg)
                }, error: function(xhr, xopt, xthrow)
                {
                    alertify.error(xthrow);
                }
            });
        }
    }
</script>
<script type="text/javascript" src="<?=base_url("assets/js/popper.min.js")?>"></script>
<script type="text/javascript" src="<?=base_url("assets/bootstrap/js/bootstrap.min.js")?>"></script>
</body>
</html>