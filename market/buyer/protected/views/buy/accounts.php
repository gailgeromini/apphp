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
                </div>
            </div>

            <div class="actions">
                <div class="controls">
                    &nbsp&nbsp<input type="submit" name="action" value="Search" class="btn action">
                    <span>or</span>
                    <input type="submit" name="action" value="Show All Accounts" class="btn" onclick="javascript: if (!confirm('Are you sure you want to show all accounts without any condition?')) { return false; }" >
                </div>
            </div>
        </fieldset>
    </form>

    <form id="cart" action="<?php echo $targetPath;?>" method="POST">
        <input type="hidden" name="act" value="addcarts" >
        <table class="table ">
            <colgroup>
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
                <th>TOTAL</th>
                <th>GROUP</th>
                <th>COST($)</th>
                <th>FEE($)</th>
                <th>QUANLITY</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $accounts = $this->accounts;
            //var_dump($accounts);die;
            foreach ($accounts as $row)
            {?>
                <tr>
                    <td><?php echo CHtml::image("templates/default/files/images/".$row['image_map_uri'])?></td>
                    <td><?php echo $row['image_map_name'];?> (<?php echo ($row['numbers']);?>)</td>
                    <td><?php echo (!empty(A::app()->getSession()->get('acountry')) && A::app()->getSession()->get('acountry') != 'all')?A::app()->getSession()->get('acountry'):'N/A';?></td>
                    <td><?php
                        echo  ($row['account_price'] + A::app()->getSession()->get('fee')) - (($row['account_price'] * $row['discount']) / 100)."$";
                        if($row['discount'] != 0 ){
                            echo "<span style='font-size:8.5px;color:#E02323;'> -".$row['discount']."%</span>";
                        }
                        ?>
                    </td>
                    <td><?php
                        if(A::app()->getSession()->get('fee') != ''){
                            echo "<span style='font-size:8.5px;color:#E02323;'> ( +".A::app()->getSession()->get('fee')." $ )</span>";
                        }else echo "No Fee"?></td>
                    <th><input type="number" min="0" id="all"></th>
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
