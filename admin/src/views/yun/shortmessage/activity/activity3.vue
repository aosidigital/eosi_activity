<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <!-- 操作按钮 -->
            <div style="padding: 10px;">
                <el-button type="primary" @click="getServiceData" >刷新数据</el-button>
                <el-button type="primary" @click="goWebView">跳转展示屏</el-button>
                <el-button type="primary" @click="saveData" :loading="SaveStatus" style="float: right;">保存数据</el-button>
            </div>
            <!-- 数据列表 -->
            <div class="data_list">
                <div class="data_list_item" v-for="(item, index) in dataList" :key="index">
                    <div class="title">{{ item.name }}</div>
                    <div class="data1_item">
                        <div>零售：<el-input v-model="item.reality" placeholder="请输入内容" style="width: 120px;" @blur="blur(item.name)"></el-input></div>
                        <div>新品：<el-input v-model="item.order_com" placeholder="请输入内容" style="width: 120px;" @blur="blur(item.name)"></el-input></div>
                    </div>
                </div>
            </div>
        </div>
    </d2-container>
</template>

<script>
/*eslint-disable */
import { Remote } from '../../../../remote'
export default {
    data () {
        return {
            dataList: [],
            SaveStatus: false,
        }
    },
    created () {
        this.getServiceData()
    },
    methods: {
        blur(item){
            var item_data = []
            for (let index = 0; index < this.dataList.length; index++) {
                if(this.dataList[index].name == item){
                    item_data.push(this.dataList[index])
                }
            }
            if(item_data.length){
                this.saveDataPublic(item_data)
            }
        },
        goWebView(){
            const newUrl = window.location.protocol + '//' + document.domain + "/activity4/index.html"
            console.log(newUrl)
            window.open(newUrl, "target")
        },
        saveData: function(){
            this.saveDataPublic(this.dataList)
        },
        saveDataPublic: function(dataList){
            console.log(dataList)
            var parms = {
                dataList: dataList
            }
            this.SaveStatus = true
            this.$http.post(Remote.city_save, parms).then((response) => {
                this.SaveStatus = false
                if(response.body.code != 200){
                    this.$message({
                        message: '保存失败',
                        type: 'error'
                    });
                    return
                }
                this.$message({
                    message: "保存成功",
                    type: 'success'
                });
                this.getServiceData()
            },function(){
                this.SaveStatus = false
                this.$message({
                    message: '保存失败',
                    type: 'error'
                });
            })
        },
        getServiceData: function () {
            this.loadingStatus = true
            var query = '?page=1&page_size=50'
            this.$http.get(Remote.city_list + query).then((response) => {
                var data = response.data.data
                this.dataList = data
            },function(){
                this.loadingStatus = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
    }
}
</script>
<style scoped>
.data_list{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
.data_list_item{
    width: 31%;
    border: 1px solid #ccc;
    border-radius: 10px;
    margin: 10px 0;
    padding: 10px;
    font-size: 14px;
}
.title{
    text-align: center;
    font-size: 24px;
    font-weight: 800;
}
.data1_item{
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 20px 0;
}
.data2_item{
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 5px;
}
.data3_item{
    text-align: center;
    font-weight: 800;
    font-size: 12px;
    color: #12a182;
    margin-top: 5px;
}
.font_weight{
    font-weight: 800;
}
</style>
