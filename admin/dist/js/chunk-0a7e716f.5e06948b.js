(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0a7e716f"],{a09d:function(t,a,e){"use strict";var i=e("e8fe"),s=e.n(i);s.a},cf47:function(t,a,e){"use strict";e.r(a);var i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("d2-container",[e("div",{staticClass:"messagePromotionAdd mytable"},[e("div",{staticStyle:{padding:"10px"}},[e("el-button",{attrs:{type:"primary"},on:{click:t.getServiceData}},[t._v("刷新数据")]),e("el-button",{attrs:{type:"primary"},on:{click:t.goWebView}},[t._v("跳转展示屏")])],1),e("div",{staticClass:"top_div"},[e("div",{staticClass:"pub_div"},[t._v("分子：")]),e("el-input",{staticClass:"pub_div",staticStyle:{width:"120px"},attrs:{placeholder:"请输入内容"},model:{value:t.nowData.num,callback:function(a){t.$set(t.nowData,"num",a)},expression:"nowData.num"}}),e("div",{staticClass:"pub_div"},[t._v("分母：")]),e("el-input",{staticClass:"pub_div",staticStyle:{width:"120px"},attrs:{placeholder:"请输入内容"},model:{value:t.nowData.percentage,callback:function(a){t.$set(t.nowData,"percentage",a)},expression:"nowData.percentage"}}),e("el-button",{staticClass:"pub_div",staticStyle:{float:"right"},attrs:{type:"primary",loading:t.SaveStatus},on:{click:t.saveTop}},[t._v("保存数据")])],1),e("div",{staticClass:"data_list"},t._l(t.dataList,function(a,i){return e("div",{key:i,staticClass:"data_list_item"},[e("div",{staticClass:"div_pub"},[t._v("奖品："),e("el-input",{attrs:{placeholder:"请输入内容"},on:{blur:function(e){return t.blur(a.id)}},model:{value:a.name,callback:function(e){t.$set(a,"name",e)},expression:"item.name"}})],1),e("div",{staticClass:"div_pub"},[t._v("数量："),e("el-input",{attrs:{placeholder:"请输入内容"},on:{blur:function(e){return t.blur(a.id)}},model:{value:a.num,callback:function(e){t.$set(a,"num",e)},expression:"item.num"}})],1),e("div",{staticClass:"div_pub"},[t._v("百分比："),e("el-input",{attrs:{placeholder:"请输入内容"},on:{blur:function(e){return t.blur(a.id)}},model:{value:a.percentage,callback:function(e){t.$set(a,"percentage",e)},expression:"item.percentage"}})],1)])}),0)])])},s=[],d=e("d84e"),n={data:function(){return{dataList:[],SaveStatus:!1,nowData:{}}},created:function(){this.getServiceData()},methods:{blur:function(t){for(var a=[],e=0;e<this.dataList.length;e++)this.dataList[e].id==t&&a.push(this.dataList[e]);a.length&&this.saveDataPublic(a)},goWebView:function(){var t=window.location.protocol+"//"+document.domain+"/jingdong2/index.html";window.open(t,"target")},saveData:function(){this.saveDataPublic(this.dataList)},saveDataPublic:function(t){var a=this,e={dataList:t};this.SaveStatus=!0,this.$http.post(d["a"].saveProduct,e).then(function(t){a.SaveStatus=!1,200==t.body.code?(a.$message({message:"保存成功",type:"success"}),a.getServiceData()):a.$message({message:"保存失败",type:"error"})},function(){this.SaveStatus=!1,this.$message({message:"保存失败",type:"error"})})},getServiceData:function(){var t=this;this.loadingStatus=!0;var a="?page=1&page_size=50&type=6&type_a=106";this.$http.get(d["a"].getProduct+a).then(function(a){var e=a.data.data;t.dataList=e,t.nowData=a.data.trends[0]},function(){this.loadingStatus=!1,this.$message({message:"请求失败",type:"error"})})},saveTop:function(){var t=this,a={dataList:[this.nowData]};this.SaveStatus=!0,this.$http.post(d["a"].saveProduct,a).then(function(a){t.SaveStatus=!1,200==a.body.code?(t.$message({message:"保存成功",type:"success"}),t.getServiceData()):t.$message({message:"保存失败",type:"error"})},function(){this.SaveStatus=!1,this.$message({message:"保存失败",type:"error"})})}}},c=n,o=(e("a09d"),e("2877")),r=function(t){t.options.__source="src/views/yun/shortmessage/activity/jingdong2.vue"},u=r,l=Object(o["a"])(c,i,s,!1,null,"81eb4c48",null);"function"===typeof u&&u(l);a["default"]=l.exports},d84e:function(t,a,e){"use strict";if(e.d(a,"a",function(){return s}),"localhost"==document.domain)var i=window.location.protocol+"//"+document.domain;else i=window.location.protocol+"//"+document.domain+"/capi";var s={};s.baseUrl=i,s.getactivitylist=i+"/admin/activitylist",s.downloadactivity=i+"/admin/downloadactivity",s.activityinfo=i+"/admin/activityinfo",s.downloadactivityinfo=i+"/admin/downloadactivityinfo",s.downloadactivity=i+"/admin/downloadactivity",s.addactivity=i+"/admin/addactivity",s.addactivity_dc=i+"/admin/addactivity_dc",s.addactivity_rt=i+"/admin/addactivity_rt",s.addactivity_every=i+"/admin/addactivity_every",s.backlist=i+"/admin/backlist",s.addbacklist=i+"/admin/addbacklist",s.delbacklist=i+"/admin/delbacklist",s.startpc_dc=i+"/luckdraw/startpc_dc",s.startpc_rt=i+"/luckdraw/startpc_rt",s.city_list=i+"/admin/city_list",s.city_save=i+"/admin/city_save",s.guest_list=i+"/admin/guest_list",s.designated_room=i+"/admin/designated_room",s.editNext=i+"/admin/editNext",s.getProduct=i+"/admin/getProduct",s.saveProduct=i+"/admin/saveProduct",s.saveProductNew=i+"/admin/saveProductNew",s.addProduct=i+"/admin/addProduct",s.deleteProduct=i+"/admin/deleteProduct",s.updatepic=i+"/admin/updatepic",s.resettingDraw=i+"/admin/resettingDraw",s.saveParze=i+"/admin/saveParze",s.addCity=i+"/admin/addCity",s.deletePrize=i+"/admin/deletePrize",s.addData1=i+"/admin/addData1",s.addData2=i+"/admin/addData2",s.addData3=i+"/admin/addData3",s.addZans=i+"/admin/addZans",s.getData3=i+"/admin/getData3",s.getData4=i+"/admin/getData4",s.getMenus=i+"/admin/getMenus",s.clearData=i+"/admin/clearData",s.getUser=i+"/admin/getUser"},e8fe:function(t,a,e){}}]);
//# sourceMappingURL=chunk-0a7e716f.5e06948b.js.map