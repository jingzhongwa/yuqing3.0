<?php
/**
 * 添加过滤规则  rules_list.php
 *
 * @version         $Id$
 * @createtime      2018/10/25
 * @updatetime      2018/11/12
 * @author          tengyingzhi
 * @copyright       Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */
require_once('admin_init.php');
require_once('admincheck.php');

$POWERID        = '7006';//权限
Admin::checkAuth($POWERID, $ADMINAUTH);
$FLAG_TOPNAV    = "media";
$FLAG_LEFTMENU  = 'site_list';

if(!empty($_GET['siteid'])){
    $siteId  = safeCheck($_GET['siteid']);

}else{

    exit("非法访问");
}


if(!empty($_GET['type'])){
    $type  = safeCheck($_GET['type']);

}else{

    $type=1;
}
$data = array(
    'siteid' => $siteId,
    'type' => $type,
    'post_url' => $pre_media,
    'method' => 'rules/get_rules_list'
);
$table_name = "Rules";
$rows = Interfaceuse::get_list($data);
$rs = $rows->rules;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开发 http://www.zhimawork.com" />
    <title>匹配规则 - 媒体信息管理</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
<!--    <script src="ckeditor/ckeditor.js"></script>-->
    <script type="text/javascript">
        $(function(){

            $('#addRule').click(function(){
                layer.open({
                    type: 2,
                    title: '添加规则',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '400px'],
                    content: 'rules_add.php?siteid=<?php echo $siteId?>&type=<?php echo $type?>'
                });
            });




            $('.editinfo').click(function(){

                var id=$(this).parent('td').find("#aid").val();
                layer.open({
                    type: 2,
                    title: '修改规则',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['600px', '400px'],
                    content: 'rules_edit.php?id='+id
                });
            });


            //删除管理员
            $(".delete").click(function(){
                var thisid = $(this).parent('td').find('#aid').val();
                layer.confirm('确认删除该规则吗？', {
                        btn: ['确认','取消']
                    }, function(){
                        var index = layer.load(0, {shade: false});
                        $.ajax({
                            type        : 'POST',
                            data        : {
                                id:thisid,
                                table_name:'<?php echo $table_name;?>',
                                method : 'site/del_Info_id',
                                post_url : '<?php echo $pre_media;?>'
                            },
                            dataType : 'json',
                            url : 'rules_interfaceUse_do.php?act=del',
                            success : function(data){
                                layer.close(index);

                                var code = data.code;
                                var msg  = data.msg;
                                switch(code){
                                    case 1:
                                        layer.alert(msg, {icon: 6}, function(index){
                                            location.href = 'rules_list.php?siteid=<?php echo $siteId?>&type=<?php echo $type?>';
                                        });
                                        break;
                                    default:
                                        layer.alert(msg, {icon: 5});
                                }
                            }
                        });
                    }, function(){}
                );
            });


            $(".delete").mouseover(function(){
                layer.tips('删除', $(this), {
                    tips: [4, '#3595CC'],
                    time: 500
                });
            });

        });

    </script>
</head>
<body>
<div id="header">
    <?php include('top.inc.php');?>
    <?php include('nav.inc.php');?>
</div>
<div id="container">
    <?php include('media_menu.inc.php');?>
    <div id="maincontent">
        <div id="position">当前位置：<a href="site_list.php">媒体管理</a> &gt; 添加匹配规则</div>

        <div id="tablist">
            <ul>
                <li <?php if($type==1) echo 'class="active second"';?>><a href="rules_list.php?siteid=<?php echo $siteId?>&type=1">匹配规则</a></li>
                <li <?php if($type==2) echo 'class="active second"';?>><a href="rules_list.php?siteid=<?php echo $siteId?>&type=2">保留规则</a></li>
                <li <?php if($type==3) echo 'class="active second"';?>><a href="rules_list.php?siteid=<?php echo $siteId?>&type=3">丢弃规则</a></li>

            </ul>
            <div style="float:right">
                <input type="button" class="btn-handle" onclick="history.back(-1)" value="返回"/>

                <input type="button" class="btn-handle" href="javascript:" id="addRule" value="添加规则"/>
            </div>
        </div>

        <div class="tablelist">
            <table>
                <tr>
                    <th>序号</th>
                    <th>规则名称</th>
                    <th>站点参数</th>
                    <th>操作</th>
                </tr>
                <?php
                $i=1;
                //如果列表不为空
                if(!empty($rs)){
                    foreach($rs as $r){
                    
                        echo '<tr>
											<td class="center">'.$i.'</td>
											<td class="center">'.$r->url.'</td>
											<td class="center">'.$r->is_param_name.'</td>
											

											<td class="center">
											<a class="editinfo" href="javascript:void(0);"><img src="images/action/dot_edit.png"/></a> 
									
                                            <a class="delete" href="javascript:void(0);"><img src="images/action/dot_del.png"/></a>
                                            <input type="hidden" id="aid" value="'.$r->id.'"/>
											</td>
										</tr>
									';
                        $i++;
                    }
                }else{
                    echo '<tr><td class="center" colspan="6">没有数据</td></tr>';
                }
                ?>

            </table>
        </div>
        <div class="clear"></div>
    </div>
    <?php include('footer.inc.php');?>
</body>
</html>