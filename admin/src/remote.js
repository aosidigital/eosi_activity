/*eslint-disable */
// api接口地址
if(document.domain == 'localhost'){
    var baseUrl = window.location.protocol + '//' + document.domain
}else{
    var baseUrl = window.location.protocol + '//' + document.domain + '/capi'
}
let Remote = {}
Remote.baseUrl = baseUrl
// 获取活动列表
Remote.getactivitylist = baseUrl + '/admin/activitylist'

// 导出会员数据
Remote.downloadactivity = baseUrl + '/admin/downloadactivity'

// 中奖详情接口
Remote.activityinfo = baseUrl + '/admin/activityinfo'

// 导出活动数据
Remote.downloadactivityinfo = baseUrl + '/admin/downloadactivityinfo'
Remote.downloadactivity = baseUrl + '/admin/downloadactivity'

// 添加活动
Remote.addactivity = baseUrl + '/admin/addactivity'
Remote.addactivity_dc = baseUrl + '/admin/addactivity_dc'
Remote.addactivity_rt = baseUrl + '/admin/addactivity_rt'
// 添加活动
Remote.addactivity_every = baseUrl + '/admin/addactivity_every'

// 黑名单列表
Remote.backlist = baseUrl + '/admin/backlist'

// 添加黑名单
Remote.addbacklist = baseUrl + '/admin/addbacklist'

// 删除黑名单
Remote.delbacklist = baseUrl + '/admin/delbacklist'

// 活动1开奖
Remote.startpc_dc = baseUrl + '/luckdraw/startpc_dc'
// 活动2开奖
Remote.startpc_rt = baseUrl + '/luckdraw/startpc_rt'

// 活动3数据获取
Remote.city_list = baseUrl + '/admin/city_list'
Remote.city_save = baseUrl + '/admin/city_save'

// 住宿人员列表
Remote.guest_list = baseUrl + '/admin/guest_list'
// 分配住宿人员
Remote.designated_room = baseUrl + '/admin/designated_room'


// 修改抽奖场次
Remote.editNext = baseUrl + '/admin/editNext'

// 淘宝、京东抽奖
Remote.getProduct = baseUrl + '/admin/getProduct'

Remote.saveProduct = baseUrl + '/admin/saveProduct'
// 新增产品保存接口
Remote.saveProductNew = baseUrl + '/admin/saveProductNew'
// 新增产品保存接口
Remote.addProduct = baseUrl + '/admin/addProduct'
// 新增产品保存接口
Remote.deleteProduct = baseUrl + '/admin/deleteProduct'
// 上传图片接口
Remote.updatepic = baseUrl + '/admin/updatepic'
// 重置抽奖
Remote.resettingDraw = baseUrl + '/admin/resettingDraw'
Remote.saveParze = baseUrl + '/admin/saveParze'
Remote.addCity = baseUrl + '/admin/addCity'
Remote.deletePrize = baseUrl + '/admin/deletePrize'
Remote.addData1 = baseUrl + '/admin/addData1'
Remote.addData2 = baseUrl + '/admin/addData2'
Remote.addData3 = baseUrl + '/admin/addData3'
Remote.addZans = baseUrl + '/admin/addZans'
Remote.getData3 = baseUrl + '/admin/getData3'
Remote.getData4 = baseUrl + '/admin/getData4'
Remote.getMenus = baseUrl + '/admin/getMenus'
Remote.clearData = baseUrl + '/admin/clearData'
Remote.getUser = baseUrl + '/admin/getUser'
export { Remote }
