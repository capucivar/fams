<link rel="stylesheet" href="/static/plugins/bootstrap-treeview/bootstrap-treeview.css">
<div id="tree"></div>

<script>
    var tree = [
  {
    text: "Parent 1",
    nodes: [
      {
        text: "Child 1",
        nodes: [
          {
            text: "Grandchild 1"
          },
          {
            text: "Grandchild 2"
          }
        ]
      },
      {
        text: "Child 2"
      }
    ]
  },
  {
    text: "Parent 2"
  },
  {
    text: "Parent 3"
  },
  {
    text: "Parent 4"
  },
  {
    text: "Parent 5"
  }
];
$(function () {
        $.ajax({
            type: "Post",
            url: "/Home/GetTreeJson",
            dataType: "json",
            success: function (result) {
                $('#tree').treeview({
                    data: result,         // 数据源
                    showCheckbox: true,   //是否显示复选框
                    highlightSelected: true,    //是否高亮选中
                    //nodeIcon: 'glyphicon glyphicon-user',    //节点上的图标
                    nodeIcon: 'glyphicon glyphicon-globe',
                    emptyIcon: '',    //没有子节点的节点图标
                    multiSelect: false,    //多选
                    onNodeChecked: function (event,data) {
                        alert(data.nodeId);
                    },
                    onNodeSelected: function (event, data) {
                        alert(data.nodeId);
                    }
                });
            },
            error: function () {
                alert("树形结构加载失败！")
            }
        });
    })
</script>