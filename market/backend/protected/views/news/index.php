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
            </colgroup>
            <thead>
            <tr>

                <th>NEWS ID</th>
                <th>NEWS TITLE</th>
                <th>NEWS CONTENT</th>
                <th>DATE CREATED</th>
                <th>MANAGER</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $news = $this->news;
            //var_dump($news);die;
            foreach ($news as $row)
            {?>
                <tr>
                    <td><?php echo $row['news_id']?></td>
                    <td><?php echo $row['news_title']?></td>
                    <td><?php echo $row['news_content']?></td>
                    <td><?php echo $row['news_create']?></td>
                    <td><a href="users/edit/id/<?php echo $row['user_id']?>" class="btn">Edit News</a> <a href="users/delete/id/<?php echo $row['user_id']?>" class="btn action" onclick="javascript: if (!confirm('Are you sure you want to delete this users <?php echo $row['user_name']?> ?')) { return false; }">Delete</a> </td>
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
