<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <!-- 操作按钮 -->
            <div style="padding: 10px;">
                <el-button type="primary" @click="getServiceData" >刷新数据</el-button>
                <el-button type="primary" @click="goWebView">跳转展示屏</el-button>
                <el-button type="primary" @click="saveData" :loading="SaveStatus" style="float: right;">保存数据</el-button>
                <el-button type="primary" @click="dialogVisible = true" :loading="SaveStatus" style="float: right;">添加数据</el-button>
            </div>
            <!-- 数据列表 -->
            <div class="data_list">
                <div class="data_list_item" v-for="(item, index) in dataList" :key="index">
                    <div style="text-align: right;"><i class="el-icon-delete delete_icon" @click="deletePrize(item.id)"></i></div>
                    <div class="title">{{ item.name }}</div>
                    <div class="data1_item">
                        <div>目标数值：<el-input v-model="item.target" placeholder="请输入内容" style="width: 100px;"></el-input></div>
                        <div>实际完成：<el-input v-model="item.reality" placeholder="请输入内容" style="width: 100px;"></el-input></div>
                    </div>
                </div>
            </div>
            <!-- 城市数据添加 -->
            <el-dialog
                title="填写数据"
                :visible.sync="dialogVisible"
                width="30%"
                :before-close="handleClose">
                <div>
                    <div class="div_pub">城市名称：<el-input v-model="from.name" placeholder="请输入内容"></el-input></div>
                    <div class="div_pub">目标数值：<el-input v-model="from.target" placeholder="请输入内容"></el-input></div>
                    <div class="div_pub">实际完成：<el-input v-model="from.reality" placeholder="请输入内容"></el-input></div>
                </div>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="dialogVisible = false">取 消</el-button>
                    <el-button type="primary" @click="addNewProduct">确 定</el-button>
                </span>
            </el-dialog>
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
            dialogVisible: false,
            from: {},
        }
    },
    created () {
        this.getServiceData()
    },
    methods: {
        deletePrize(id){
            var parms = { id: id}
            this.$http.post(Remote.deletePrize, parms).then((response) => {
                if(response.body.code != 200){
                    this.$message({
                        message: '删除失败',
                        type: 'error'
                    });
                    return
                }
                this.$message({
                    message: "删除成功",
                    type: 'success'
                });
                this.getServiceData()
            },function(){
                this.$message({
                    message: '删除失败',
                    type: 'error'
                });
            })
        },
        addNewProduct(){
            var parms = this.from
            parms.type = 1
            if(parms.target && parms.reality){
                let completion_rate = parms.reality/parms.target*100
                parms.completion_rate = completion_rate.toFixed(2)
            }
            this.$http.post(Remote.addCity, parms).then((response) => {
                this.dialogVisible = false
                if(response.body.code != 200){
                    this.$message({
                        message: '添加失败',
                        type: 'error'
                    });
                    return
                }
                this.$message({
                    message: "添加成功",
                    type: 'success'
                });
                this.getServiceData()
            },function(){
                this.$message({
                    message: '添加失败',
                    type: 'error'
                });
            })
        },
        handleClose(done) {
            this.dialogVisible = false
        },
        goWebView(){
            const newUrl = window.location.protocol + '//' + document.domain + "/moon1/index.html"
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
            var query = '?page=1&page_size=50&type=1'
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
    /* justify-content: space-between; */
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
.delete_icon{
    width: 60px;
    cursor: pointer;
}
</style>
