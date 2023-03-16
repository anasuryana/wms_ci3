<div id="mmmfooter" data-options="region:'south',border:false" style="height:35px;background:#98CCFD;padding:5px;">
    <div class="container-fluid">
        <div class="row g-0">
            <div class="col">
                <span id="footerinfo_user" class="badge bg-success">Hi <?php echo $sapaDia; ?></span>
            </div>
            <div class="col">
                <i><span class="badge bg-info">WMS</span></i>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="FOOTER_MODWH">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Set Warehouse</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Warehouse</span>
                            <select id="FOOTER_MODWH_selwh" class="form-select">
                            <?=$lwh?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1">
                        <button type="button" class="btn btn-sm btn-primary" id="FOOTER_MODWH_btnsave"><i class="fas fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="FOOTER_MODWHFG">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Set Warehouse</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Warehouse</span>
                            <select id="FOOTER_MODWHFG_selwh" class="form-select">
                                <?=$lwhfg?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1">
                        <button type="button" class="btn btn-sm btn-primary" id="FOOTER_MODWHFG_btnsave"><i class="fas fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    alertify.set('notifier', 'position', 'top-center');

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    var wms_usergroupid = '<?=$wms_usergroup_id;?>';
    var wms_scan_pipe = setInterval(wms_check_unscan_wh, 15000);

    function wms_check_unscan_wh() {
        if (wms_usergroupid == 'INC') {
            $.ajax({
                url: "<?=base_url('ITH/get_unscanned_FG_v1')?>",
                dataType: "JSON",
                success: function(response) {
                    if (response.status[0].cd != '0') {
                        alertify.warning(response.status[0].msg);
                    } else {
                        if (response.status[0].msg != '') {
                            alertify.warning(response.status[0].msg);
                        } else {
                            alertify.dismissAll();
                        }
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    }
    var indexnya = 0;
    const devNode = $('#mmenu').tree({
        url: '<?=base_url("menu")?>',
        animate: true,
        lines: true,
        onClick: function(node) {
            if ($('#tt').tabs('exists', node.desc)) {
                $('#tt').tabs('select', node.desc);
            } else {
                if (node.hasOwnProperty('url')) {
                    if (node.url != '') {
                        if (node.desc == 'Receiving FG PRD' || node.desc == 'Receiving FG PRD NG' || node.desc == 'Transfer OQC' || node.desc == 'Transfer OQC Sub-Assy' ||
                            node.desc == 'Receiving FG WH' || node.desc == 'Receiving Returned FG') {
                            if ($('#tt').tabs('tabs').length > 0) {
                                alertify.warning('Please close all opened tabs');
                                return;
                            }
                        } else {
                            if ($('#tt').tabs('exists', 'Transfer OQC')) {
                                alertify.warning('Please close all opened tabs');
                                return;
                            } else if ($('#tt').tabs('exists', 'Receiving FG WH')) {
                                alertify.warning('Please close all opened tabs');
                                return;
                            } else if ($('#tt').tabs('exists', 'Receiving FG PRD')) {
                                alertify.warning('Please close all opened tabs');
                                return;
                            } else if ($('#tt').tabs('exists', 'Receiving FG PRD NG')) {
                                alertify.warning('Please close all opened tabs');
                                return;
                            } else if ($('#tt').tabs('exists', 'Transfer OQC Sub-Assy')) {
                                alertify.warning('Please close all opened tabs');
                                return;
                            } else if ($('#tt').tabs('exists', 'Receiving Returned FG')) {
                                alertify.warning('Please close all opened tabs');
                                return;
                            }
                        }
                        addT(node.desc, node.url);
                    }
                }
            }
        },
        onLoadSuccess: function(node, data) {
            const myfind = $(this).tree('findBy', {
                field: 'id',
                value: 'CL'
            })

            // $('#tt').tabs('add', {
            //     title: 'Dashboard',
            //     href: '<?=base_url('Home/form_dashboard')?>',
            //     closable: false
            // });
        }
    })

    function addT(Judul, alamat) {
        $.post("<?=base_url('Pages/l')?>", {
                inmenu: Judul,
                inurl: alamat
            },
            function(data, textStatus, jqXHR) {

            },
            "text"
        );
        indexnya++;
        $('#tt').tabs('add', {
            title: Judul,
            href: '<?=base_url()?>' + alamat,
            closable: true
        });
    }



    function showinfoofus() {
        let mymodal = new bootstrap.Modal(document.getElementById("FOOTER_MODWH"), {
            backdrop: 'static',
            keyboard: false
        });
        mymodal.show();
    }

    function showinfoofus_FG() {
        let mymodal = new bootstrap.Modal(document.getElementById("FOOTER_MODWHFG"), {
            backdrop: 'static',
            keyboard: false
        });
        mymodal.show();
    }
    $("#FOOTER_MODWHFG_btnsave").click(function(e) {
        e.preventDefault();
        let mwh = document.getElementById("FOOTER_MODWHFG_selwh").value;
        Cookies.set('CKPSI_WH', mwh, {
            expires: 365
        });
        $("#FOOTER_MODWHFG").modal('hide');
        alertify.message('Saved');
    });
    $("#FOOTER_MODWH_btnsave").click(function(e) {
        e.preventDefault();
        let mwh = document.getElementById("FOOTER_MODWH_selwh").value;
        Cookies.set('CKPSI_WH', mwh, {
            expires: 365
        });
        $("#FOOTER_MODWH").modal('hide');
        alertify.message('Saved');
    });

    $('#tt').tabs({
        onBeforeClose: function(title) {
            let konf = confirm('Are you sure want to close ' + title);
            if (konf) {
                if (typeof clipboard != 'undefined') {
                    clipboard.destroy();
                }
                if (typeof rt_rcvscan != 'undefined') {
                    clearTimeout(rt_rcvscan);
                }
                if (typeof txfg_cb_rfid != 'undefined') {
                    txfg_cb_rfid.destroy();
                }
                if (typeof cb_out != 'undefined') {
                    cb_out.destroy();
                }
                if (typeof cb_defmonthyear != 'undefined') {
                    cb_defmonthyear.destroy();
                }
                if (typeof cb_outmonthyear != 'undefined') {
                    cb_outmonthyear.destroy();
                }
                if (typeof objfun_qtycheck != 'undefined') {
                    clearTimeout(objfun_qtycheck);
                }
                if (typeof objfun_hourcheck != 'undefined') {
                    clearTimeout(objfun_hourcheck);
                }
                if (typeof cb_prodmonth != 'undefined') {
                    cb_prodmonth.destroy();
                }
                if (typeof cbrecapinv != 'undefined') {
                    cbrecapinv.destroy();
                }
                if (typeof cb_visreport != 'undefined') {
                    cb_visreport.destroy();
                }
                if (title.toLowerCase() == 'downtime machine per lot') {
                    alertify.message('we also close window');
                    $("#w_mtn_reclotshift").window('close');
                }
                if (title == 'Scan RM IN') {
                    if (typeof scannerDetectionData != 'undefined') {
                        onScan.detachFrom(document);
                    }

                }
                if (title == 'Receiving FG PRD' || title == 'Receiving FG PRD NG' || title == 'Transfer OQC' || title == 'Transfer OQC Sub-Assy' || title == 'Receiving Returned FG') {
                    console.log('mulai detach seharusnya....');
                    if (typeof scannerDetectionData != 'undefined') {
                        onScan.detachFrom(document);
                    } else {
                        try {
                            onScan.detachFrom(document);
                        } catch (err) {
                            console.log(err + ' ini log');
                        }
                    }
                }
            }

            return konf;
        },
        onSelect: function(title, index) {
            if (title == 'Pending RM Scan' ||
                title == 'Transfer Location ID') {
                showinfoofus();
            }
            if (title == 'Receiving FG WH' || title == 'Scan SI') {
                showinfoofus_FG();
            }
        }
    })

    function cmpr_selectElementContents(el) {
        let body = document.body,
            range, sel;
        if (document.createRange && window.getSelection) {
            range = document.createRange();
            sel = window.getSelection();
            sel.removeAllRanges();
            try {
                range.selectNodeContents(el);
                sel.addRange(range);
            } catch (e) {
                range.selectNode(el);
                sel.addRange(range);
            }
        } else if (body.createTextRange) {
            range = body.createTextRange()
            range.moveToElementText(el)
            range.select()
        }
    }
    function ith_colorize(pcontainer){
        let ithwh = pcontainer.getElementsByTagName('option')
        Array.from(ithwh).forEach(function(elem) {
            if(elem.value.includes('EQUIP')){
                elem.style.cssText = 'background-color:#0072B5;color:white'
            }
        })
    }
</script>
<script type="text/javascript" src="<?=base_url("assets/js/popper.min.js")?>"></script>
<script type="text/javascript" src="<?=base_url("assets/bootstrap/js/bootstrap.min.js")?>"></script>
</body>

</html>