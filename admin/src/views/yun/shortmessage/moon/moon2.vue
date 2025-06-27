<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <!-- 操作按钮 -->
            <div style="padding: 10px;">
                <el-button type="primary" @click="getServiceData" >刷新数据</el-button>
                <el-button type="primary" @click="goWebView">跳转展示屏</el-button>
            </div>
            <!-- 产品信息 -->
            <div class="title">产品信息</div>
            <div class="title_flex">
                <div class="pub_div">订单数：</div>
                <el-input  class="pub_div" v-model="nowData.num" placeholder="请输入内容" style="width:120px;"></el-input>
                <el-button type="primary" @click="dialogVisibleb = true">新增数据</el-button>
                <el-button  class="pub_div" type="primary" @click="saveTop" :loading="SaveStatus" style="float: right;">保存数据</el-button>
            </div>
            <div class="product_center">
                <div class="product_center_item" v-for="(item, index) in productList" :key="index">
                    <div style="text-align: right;"><i class="el-icon-delete delete_icon" @click="deleteProduct(item.id)"></i></div>
                    <div class="div_pub">产品名称：<el-input v-model="item.name" placeholder="请输入内容" @blur="blur(item.id)"></el-input></div>
                    <div class="div_pub">产品价格：<el-input v-model="item.percentage" placeholder="请输入内容" @blur="blur(item.id)"></el-input></div>
                    <div class="div_pub">产品图片：</div>
                    <div class="image_div">
                        <img class="image_show" :src="item.image"  >
                    </div>
                    <el-upload
                        :action="updatepic"
                        :on-success="picSuccess"
                        :data="{
                            id: item.id
                        }"
                        list-type="picture-card">
                            <i slot="default" class="el-icon-plus"></i>
                    </el-upload>
                </div>
            </div>
            <!-- 产品数据添加 -->
            <el-dialog
                title="填写数据"
                :visible.sync="dialogVisibleb"
                width="30%"
                :before-close="handleCloseb">
                <div>
                    <div class="div_pub">产品名称：<el-input v-model="fromb.name" placeholder="请输入内容"></el-input></div>
                    <div class="div_pub">产品价格：<el-input v-model="fromb.percentage" placeholder="请输入内容"></el-input></div>
                </div>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="dialogVisibleb = false">取 消</el-button>
                    <el-button type="primary" @click="addNewProduct">确 定</el-button>
                </span>
            </el-dialog>

            <!-- 进度数据列表 -->
            <div class="title">动销进度</div>
            <div style="padding: 10px;text-align: center;">
                <el-button type="primary" @click="dialogVisible = true">新增数据</el-button>
                <el-button type="primary" @click="saveData" :loading="SaveStatus">保存数据</el-button>
            </div>
            <div class="data_list">
                <div class="data_list_item" v-for="(item, index) in dataList" :key="index">
                    <div style="text-align: right;"><i class="el-icon-delete delete_icon" @click="deleteProduct(item.id)"></i></div>
                    <div class="div_pub">优惠金额：<el-input v-model="item.percentage" placeholder="请输入内容"></el-input></div>
                    <div class="div_pub">订购数：<el-input v-model="item.num" placeholder="请输入内容"></el-input></div>
                </div>
            </div>
            <!-- 进度数据添加 -->
            <el-dialog
                title="填写数据"
                :visible.sync="dialogVisible"
                width="30%"
                :before-close="handleClose">
                <div>
                    <div class="div_pub">优惠金额：<el-input v-model="from.percentage" placeholder="请输入内容"></el-input></div>
                    <div class="div_pub">订购数：<el-input v-model="from.num" placeholder="请输入内容"></el-input></div>
                </div>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="dialogVisible = false">取 消</el-button>
                    <el-button type="primary" @click="addNewData">确 定</el-button>
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
            productList: [],
            dataList: [],
            SaveStatus: false,
            nowData: {},
            dialogVisible: false,
            dialogVisibleb: false,
            disabled: false,
            updatepic: Remote.updatepic,
            from: {},
            fromb: {},
        }
    },
    created () {
        this.getServiceData()
    },
    methods: {
        
        addNewProduct(){
            if(this.productList.length >= 4){
                this.$message({
                    message: '产品已超过4个，无法添加',
                    type: 'error'
                });
                return
            }
            var parms = this.fromb
            parms.type = 105
            this.$http.post(Remote.addProduct, parms).then((response) => {
                this.dialogVisibleb = false
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
        deleteProduct(id){
            var parms = { id: id}
            this.$http.post(Remote.deleteProduct, parms).then((response) => {
                this.dialogVisible = false
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
        addNewData(){
            if(this.dataList.length >= 10){
                this.$message({
                    message: '动销阶段已超过10个，无法添加',
                    type: 'error'
                });
                return
            }
            var parms = this.from
            parms.type = 3
            this.$http.post(Remote.addProduct, parms).then((response) => {
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
        handleCloseb(done) {
            this.dialogVisibleb = false
        },
        handleRemove(file, index) {
            console.log(file);
        },
        handlePictureCardPreview(file, index) {
            this.productList[index] = file.url;
        },
        handleDownload(file, index) {
            console.log(file);
        },
        picSuccess: function (response, file, fileList) {
            this.getServiceData()
        },
        blur(item){
            var item_data = []
            for (let index = 0; index < this.dataList.length; index++) {
                if(this.dataList[index].id == item){
                    item_data.push(this.dataList[index])
                }
            }
            if(item_data.length){
                this.saveDataPublic(item_data)
            }
        },
        goWebView(){
            const newUrl = window.location.protocol + '//' + document.domain + "/moon2/index.html"
            console.log(newUrl)
            window.open(newUrl, "target")
        },
        saveData: function(){
            this.saveDataPublic(this.dataList)
        },
        saveDataPublic: function(dataList){
            var parms = {
                dataList: dataList
            }
            this.SaveStatus = true
            this.$http.post(Remote.saveProduct, parms).then((response) => {
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
            var query = '?page=1&page_size=50&type=3&type_a=105'
            this.$http.get(Remote.getProduct + query).then((response) => {
                var data = response.data.data
                this.productList = response.data.trends
                this.dataList = data
                this.nowData = response.data.trends[0]
            },function(){
                this.loadingStatus = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        saveTop: function(){
            var parms = {
                dataList: this.productList,
                orders: this.nowData.num
            }
            this.SaveStatus = true
            this.$http.post(Remote.saveProductNew, parms).then((response) => {
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
    }
}
</script>
<style scoped>
.data_list{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
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
    margin: 20px;
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
.div_pub{
    margin-top: 10px;
    width: 60%;
}
.top_div{
    display: flex;
    align-items: center;
}
.pub_div{
    margin-right: 10px;
}
.product_center{
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.product_center_item{
    width: 30%;
    border: 1px solid #ccc;
    border-radius: 10px;
    margin: 10px;
    padding: 10px;
    font-size: 14px;
}
.title_flex{
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}
.image_div{
    width: 200px;
}
.image_show{
    width: 200px;
    height: 200px;
}
.delete_icon{
    width: 60px;
    cursor: pointer;
}
</style>
