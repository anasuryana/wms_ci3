<div style="padding: 10px">
	<div class="container-xxl">  
        <div class="row">
            <div class="col-md-12 mb-1">
                <?php     if(count($litem)>0){?>
                <div class="alert alert-info" role="alert">
                    <?=count($litem)?> Row(s) found
                </div>
                <?php
                }
                ?>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="table-responsive" id="itm_container">
                    <table id="itm_tbldiff" class="table table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" colspan="2">Item Code</th>                                
                            </tr>
                            <tr>
                                <th class="text-center">WMS</th>
                                <th class="text-center">Mega</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $toech ='';
                                foreach($litem as $r){
                                    $toech .= '<tr>
                                    <td>'.$r['MITM_ITMCD'].'</td>
                                    <td class="text-center">?</td>
                                    </tr>
                                    ';
                                }
                                echo $toech;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
</script>