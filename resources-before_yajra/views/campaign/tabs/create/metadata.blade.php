<div class="card">
    <div class="card-body pt-0">
        <div class="form-body">
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover color-table lkp-table">
                        <thead>
                        <tr>
                            <th><label>ID</label></th>
                            <th><label>Objective</label></th>
                            <th><label>Brand</label></th>
                            <th><label>Channel</label></th>
                            <th><label>Campaign Description</label></th>
                            <th><label>Universe</label></th>
                            <th><label>Wave</label></th>
                            <th><label>Start Yr</label></th>
                            <th><label>Mth</label></th>
                            <th><label>Day</label></th>
                            <th><label>Interval</label></th>
                            <th><label>ProductCat1</label></th>
                            <th><label>ProductCat2</label></th>
                            <th><label>SKU</label></th>
                            <th><label>Coupon</label></th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td><label id="campid"></label></td>
                            <td>
                                <select class="form-control form-control-sm" name="cmbObj" id="cmbObj" style="width:50px"
                                        onKeyDown="fnKeyDownHandler_A(this, event);"
                                        onKeyUp="fnKeyUpHandler_A(this, event); return false;"
                                        onKeyPress="return fnKeyPressHandler_A(this, event);"
                                        onChange="fnChangeHandler_A(this, event);">
                                    <option value="CustObj" id="CustObj">----</option>
                                    <option value="Convert">Convert</option>
                                    <option value="Cross-sell">Cross-sell
                                    </option>
                                    <option value="Holiday">Holiday
                                    </option>
                                    <option value="Information">Information
                                    </option>
                                    <option value="Innovation">Innovation
                                    </option>
                                    <option value="Retain">Retain
                                    </option>
                                    <option value="Invitation">Invitation
                                    </option>
                                    <option value="Upsell">Upsell
                                    </option>
                                    <option value="SuperProtect">SuperProtect
                                    </option>
                                    <option value="Super Media">Social Media
                                    </option>
                                    <option value="New Service">New Service
                                    </option>
                                    <option selected value="Protect">Protect
                                    </option>
                                    <option value="Reactivate">Reactivate
                                        <?= \App\Helpers\Helper::get_ProCatOption('Obj'); ?>
                                    </option>
                                </select></td>
                            <td><select class="form-control form-control-sm" id="cmbBand" style="width:50px">
                                    <option value="RD">A1
                                    </option>
                                    <option value="MT">A2
                                    </option>
                                    <option value="MT">A3
                                    </option>
                                    <option value="MT">A4
                                    </option>
                                    <option value="MT">A5
                                    </option>
                                    <option value="MT">A6
                                    </option>
                                    <option value="MT">A7
                                </select></td>
                            <td><select class="form-control form-control-sm" id="cmbChannel" style="width:50px">
                                    <option value="AL">AL</option>
                                    <option value="DM">DM</option>
                                    <option value="ED">ED</option>
                                    <option selected value="EM">EM</option>
                                    <option value="TM">TM</option>
                                    <option value="TX">TX</option>
                                </select></td>
                            <td><input class="form-control form-control-sm" style="width:250px;"
                                       onkeypress="return AvoidSpaceMeta(event);" type="text" id="txtCat"/>
                            </td>
                            <td><input class="form-control form-control-sm" style="width:250px;"
                                       onkeypress="return AvoidSpaceMeta(event);" type="text" id="txtListDis"/>
                            </td>
                            <td><select class="form-control form-control-sm" id="cmbWave" style="width:50px">
                                    <option value="1">1</option><?php for ($i = 2; $i < 21; $i++) {
                                        echo "<option value=$i>$i</option>";
                                    } ?></select></td>
                            <td>

                                <select class="form-control form-control-sm" id="cmbStartYr" style="width:50px">
                                    <?php
                                    for ($i = date('Y') - 3; $i <= date('Y'); $i++) {
                                        if ($i == date('Y')) {
                                            echo "<option selected='selected' value=$i>$i</option>";
                                        } else {
                                            echo "<option value=$i>$i</option>";
                                        }
                                    }
                                    ?></select></td>
                            <td>

                                <select class="form-control form-control-sm" id="cmbMonth" style="width:50px">
                                    <?php

                                    for ($i = 1; $i <= 12; $i++) {
                                        $zero = 0;
                                        if ($i < 10)
                                            $i = $zero . $i;
                                        if ($i == date('m')) {
                                            echo "<option selected='selected' value=$i>$i</option>";
                                        } else {
                                            echo "<option value=$i>$i</option>";
                                        }
                                    } ?></select></td>
                            <td><select class="form-control form-control-sm" id="cmbDay" style="width:50px">
                                    <?php
                                    for ($i = 2; $i <= 31; $i++) {
                                        if ($i < 10)
                                            $i = $zero . $i;
                                        if ($i == date('d'))
                                            echo "<option selected value=$i>$i</option>";
                                        else
                                            echo "<option value=$i>$i</option>";
                                    } ?></select></td>
                            <td><select class="form-control form-control-sm" id="cmbInterval"
                                        style="width:50px"><?php for ($i = 1; $i < 181; $i++) {
                                        if ($i != 45) echo "<option value=$i>$i</option>"; else echo "<option value=$i selected >$i</option>";
                                    } ?></select></td>
                            <td><select class="form-control form-control-sm" name="cmbPcat1" id="cmbPcat1" style="width:100px"
                                        onKeyDown="fnKeyDownHandler_A(this, event);"
                                        onKeyUp="fnKeyUpHandler_A(this, event); return false;"
                                        onKeyPress="return fnKeyPressHandler_A(this, event);"
                                        onChange="fnChangeHandler_A(this, event);">
                                    <option value="Cust1" id="Cust1">----
                                    </option>
                                    <option value=" " selected>None
                                    </option>
                                    <option value="0 ">0 - Clothing
                                    </option>
                                    <option value="1 ">1 - Shoes
                                    </option>
                                    <option value="2 ">2 - Bags
                                    </option>
                                    <option value="3 ">3 - Accesories
                                    </option>
                                    <option value="4 ">4 - Jewelry
                                    </option>
                                    <option value="5 ">5 - Lingerie
                                    </option>
                                    <option value="6 ">6 - Swimwear
                                    </option>
                                    <option value="7 ">7 - Designers
                                    <?= \App\Helpers\Helper::get_ProCatOption('PC1'); ?>
                                </select></td>

                            <td><select class="form-control form-control-sm" name="cmbPcat2" id="cmbPcat2" style="width:100px"
                                        onKeyDown="fnKeyDownHandler_A(this, event);"
                                        onKeyUp="fnKeyUpHandler_A(this, event); return false;"
                                        onKeyPress="return fnKeyPressHandler_A(this, event);"
                                        onChange="fnChangeHandler_A(this, event);">
                                    <option value="Cust2" id="Cust2">----
                                    </option>
                                    <option value=" " selected>None
                                    </option>
                                    <option value="1 ">1 Clothing-Dresses
                                    </option>
                                    <option value="2 ">2 Clothing-Jumpsuits
                                    </option>
                                    <option value="3 ">3 Clothing-Jackets
                                    </option>
                                    <option value="4 ">4 Clothing-Tops
                                    </option>
                                    <option value="5 ">5 Clothing-Bottoms
                                    </option>
                                    <option value="6 ">6 Clothing-Skirts
                                    </option>
                                    <option value="7 ">7 Clothing-Coats
                                    </option>
                                    <option value="8 ">8 Clothing-Pants
                                    </option>
                                    <option value="9 ">9 Clothing-Shorts
                                    </option>
                                    <?= \App\Helpers\Helper::get_ProCatOption('PC2'); ?>
                                </select></td>


                            <td><input class="form-control form-control-sm" type="text" id="txtSKU" style="width:75px"/></td>
                            <td><input class="form-control form-control-sm" type="text" id="txtCoupon" style="width:75px"/></td>
                        </tr>
                        </tbody>

                    </table>
                </div>
            </div>


            {{--<div style='text-align:right'>
                <span class="button-group">
                    <span id="yui-gen9" class="yui-button yui-push-button">
                        <span class="first-child">
                            <button class="btn btn-info"
                                     type="button"
                                     id='btnGo'
                                     onClick='dispReportMeta()'>Go</button>
                        </span>
                    </span>
                </span>
            </div>--}}
            <div class="row">
                <div id="divRep"></div>

            </div>
            <div class="row pull-right">
                <button type="button" class="btn btn-info ft" id='savebottom' onClick='nextMeta();'>Next</button>
            </div>
        </div>
    </div>

</div>

<script type="application/javascript">
    var yy = '<?= date('Y'); ?>';
    var mm = '<?= date('m'); ?>';
    var dd = '<?= date('d'); ?>';
</script>
<script src="js/metadata.js?ver={{time()}}" type="application/javascript"></script>
