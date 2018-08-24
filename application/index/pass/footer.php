<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        <b>Version: </b>&nbsp;1.0.0.0_<?php echo date("Y-m-d") ;?>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2018 <a href="#">Toshiba.swv</a>.</strong> All rights reserved.
</footer>

<div class="modal fade bs-example-modal-sm" id="caidanDialog"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">ATS彩蛋</h4>
            </div>
            <div class="modal-body text-center" >
                <img src="../resource/img/caidan.png">
            </div>
            <div class="modal-footer">
                <blockquote>
                    <i class="text-info">扫一扫，有惊喜！</i>
                    <button type="button" class="btn btn-default btn-primary btn-sm" data-dismiss="modal">关闭</button>
                </blockquote>
            </div>
        </div>
    </div>
</div>

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>

<script>
    $(function () {
        $('.main-footer a').click(function () {
            $('#caidanDialog').modal("toggle");
        });
    })
</script>

<?php
/**
 * Created by PhpStorm.
 * User: refar
 * Date: 18-7-25
 * Time: 上午11:54
 */

