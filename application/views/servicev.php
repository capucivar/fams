<html lang="zh-CN">
    <h3>获取代理商后台房卡数量</h3> 
    <ul>
        <li>请求方式：GET</li>
        <li>url：<?= $url ?>getbgrc?param={"uid":"411a3140510b11e7aaef45548c501291"}</li>
        <li>参数格式：json</li>
        <li>参数说明：uid - 用户ID</li>
        <li>输出：{"code":1,"message":"","result":{"num":"100"}}</li>
     </ul>
    <h3>扣除代理商后台房卡数量</h3> 
    <ul>
        <li>请求方式：GET</li>
        <li>url：<?= $url ?>debgcard?param={"uid":"7c2abbe0524611e7a41a5b3aeab91d00","num":"3","rno":"123432"}</li>
        <li>参数格式：json</li>
        <li>参数说明：uid - 用户ID，num - 扣除的房卡数量，rno - 房间ID</li>
        <li>输出：{"code":1,"message":"","result":""}</li>
     </ul>
</html>