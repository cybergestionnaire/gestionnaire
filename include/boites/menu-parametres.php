<!-- DIV acces direct aux autres parametres-->
<div class="row">
    <div class='col-md-12'>
        <div class="box">
            <div class="box-header ">
                <h3 class="box-title">Param&eacute;trages</h3>
                <div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> </div>
            </div>

            <div class="box-body">

                <?php
                //debug($_GET["a"]);
                echo configBut($_GET["a"]);
                ?>

            </div><!-- /.box-body -->
        </div><!-- /.box -->
        <?php
        $mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
        if ($mesno != "") {
            echo getError($mesno);
        }

        if (isset($mess) && $mess != "") {
            echo $mess;
        }
        ?>

    </div><!-- /.col-md-12 -->
</div><!-- /.row -->