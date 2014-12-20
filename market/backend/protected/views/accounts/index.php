<script type="text/javascript">
    function addtocart()
    {
        document.getElementById("cart").submit();
    }
</script>
<article class="panel">
    <header>
        <h3>Search</h3><br/>
    </header>
    <?php if(!empty($Messages)){?>
        <div class="msg <?php echo $Mtype?>">
            <p><?php echo $Messages; ?></p>
            <a class="close">&times;</a>
        </div>
    <?php }?>
    <form action="<?php echo $targetPath;?>" method="POST">
        <fieldset class="form">
            <?php
            // draw search form
            echo CWidget::form(array(

                'fields'=>array(

                    'act'     =>array('type'=>'hidden', 'value'=>'search'),
                ),
                'return'=>true,
            ));
            ?>

            <div class="field">
                <label for="cardsType"><small>Options</small></label>
                <div class="controls cards">
                    <select id="select" name="type">
                        <?php echo $tDroplist;?>
                    </select>
                    <select id="select" name="country">
                        <?php echo $cDroplist;?>
                    </select>
                    <select id="select" name="category">
                        <?php echo $ctDroplist;?>
                    </select>
                    <select id="select" name="paypal_used">
                        <?php echo $uDroplist;?>
                    </select>
                </div>
            </div>
            <div class="field">
                <label for="extension"><small>Extension</small></label>
                <div class="controls">&nbsp
                    <input AUTOCOMPLETE="OFF" type="text" value="<?php echo A::app()->getSession()->get('paextension')?>" id="extension" name="extension" class="input" placeholder=" example : jaydevil" >

                </div>

            </div>

            <div class="actions">
                <div class="controls">
                    &nbsp&nbsp<input name="action" type="submit" value="Search" class="btn action">
                    <span>or</span>
                    <input name="action" type="submit" value="Show All Accounts" class="btn" onclick="javascript: if (!confirm('Are you sure you want to show all account without any condition?')) { return false; }">
                </div>
            </div>
        </fieldset>
    </form>
    <form id="cart" action="<?php echo $targetPath;?>" method="POST">
        <input type="hidden" name="act" value="addcarts" >
        <table class="table ">
            <colgroup>
                <col class="grid1">
                <col class="grid1">
                <col class="grid1">
                <col class="grid1">
                <col class="grid1">
                <col class="grid1">
                <col class="grid1">
                <col class="grid1_1">
                <col class="grid1">
                <col class="grid1">
                <col class="grid1">
                <col class="grid1">
            </colgroup>
            <thead>
            <tr>

                <th>TYPE</th>
                <th>EMAIL</th>
                <th>ACCOUNT</th>
                <th>BALANCE</th>
                <th>EMAIL</th>
                <th>CARD</th>
                <th>BANK</th>
                <th>COUNTRY</th>
                <th>COST($)</th>
                <th>USED BY</th>
                <th>DATE USED</th>
                <th><input type="checkbox" id="all"></th>

            </tr>
            </thead>
            <tbody>
            <?php
            $paypals = $this->paypals;
            //var_dump($paypals);die;
            foreach ($paypals as $row)
            {?>
                <tr>

                    <td><?php echo CHtml::image("templates/default/files/images/".$row['image_map_uri'])?></td>
                    <td><?php echo CRefactorUltilities::replSOject($row['paypal_email'],5,'*****');?></td>
                    <td><?php echo $row['paypal_type']?></td>
                    <td><?php echo $row['paypal_balance']?></td>
                    <td><?php echo CRefactorUltilities::replIObject($row['paypal_is_email'])?></td>
                    <td><?php echo CRefactorUltilities::replIObject($row['paypal_is_card'])?></td>
                    <td><?php echo CRefactorUltilities::replIObject($row['paypal_is_bank'])?></td>
                    <td><?php echo CRefactorUltilities::flagsObject(strtolower($row['paypal_country']))." ".$row['paypal_country']?></td>
                    <td><?php
                        echo  ($row['paypal_price'] + A::app()->getSession()->get('fee')) - (($row['paypal_price'] * $row['discount']) / 100)."$";
                        if($row['discount'] != 0 ){
                            echo "<span style='font-size:8.5px;color:#E02323;'> -".$row['discount']."%</span>";
                        }
                        ?>
                    </td>
                    <td><?php
                        if(!empty($row['user_name'])){
                            echo  $row['user_name'];
                        }else echo "None"
                        ?></td>
                    <td><?php if(!empty($row['paypal_used_date'])){
                            echo $row['paypal_used_date'];
                        }else echo "0000-00-00 00:00:00"?></td>
                    <td><input type="checkbox" name="paypals[]" id="checkbox" value="<?php echo $row['paypal_id']?>"></td>

                </tr>

            <?php }?>

            </tbody>
        </table>
    </form>

    <div class="results">

        <p></p>
        <?php
        echo $this->pagination;
        ?>
    </div>

</article>
