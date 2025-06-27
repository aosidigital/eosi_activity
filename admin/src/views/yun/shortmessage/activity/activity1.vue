<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <div>
                <!-- 标题 -->
                <div class="jiangpin">奖品信息</div>
                <!-- 数据列表 -->
                <div class="data_list">
                    <div class="data_list_item" v-for="(item, index) in parzeList" :key="index">
                        <div class="level_text">{{ getLevel(item.id) }}</div>
                        <div class="div_pub">奖品名称：<el-input v-model="item.name" placeholder="请输入内容" @blur="blur(item.id)"></el-input></div>
                        <div class="div_pub">奖品数量：<el-input v-model="item.grade" placeholder="请输入内容" @blur="blur(item.id)"></el-input></div>
                    </div>
                </div>
            </div>
            <el-table :data="orderData" border stripe style="margin-top:20px;" v-loading="loadingStatus">
                <!-- <el-table-column prop="changci" label="场次"></el-table-column> -->
                <el-table-column prop="is_stop" label="开奖状态">
                    <template slot-scope="scope">
                        <span v-if="scope.row.is_stop == 1" class="stop">已开奖</span>
                        <span v-else class="start">未开奖</span>
                    </template>
                </el-table-column>
                <el-table-column prop="start" label="活动开奖时间">
                    <template slot-scope="scope">
                        <span>{{scope.row.end_time}}</span>
                    </template>
                </el-table-column>
                <el-table-column prop="url" label="开奖二维码" >
                    <template slot-scope="scope">
                        <el-image class="qr_img" @click="changeimg(scope.row)" :src="scope.row.qrcode_url" :preview-src-list="srcList"></el-image>
                    </template>
                </el-table-column>
                <el-table-column prop="url" label="开奖连接">
                    <template slot-scope="scope">
                        <a :href="scope.row.url" target="_blank">点击跳转</a>
                    </template>
                </el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button type="primary" size="mini" style="margin-left: 20px;" @click="NextSession(scope.row)" :loading="NextType">进入下一抽奖环节</el-button>
                        <el-button type="primary" size="mini" style="margin-left: 20px;margin-top: 10px;" @click="resetting(scope.row)" :loading="NextType">重置抽奖</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div style="text-align:center;">
                <el-pagination
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :current-page="page"
                :page-sizes="[10, 20, 30 ,50, 100]"
                :page-size="page_count"
                layout="total, sizes, prev, pager, next, jumper"
                :total="total_count">
                </el-pagination>
            </div>
            <el-dialog title="活动详情" :visible.sync="dialogTableVisible" class="daiinfo">
                <!-- <div style="text-align: right;">
                    <el-button type="primary" icon="el-icon-goods" size="mini" :loading="DrawAPrizeStatus" @click="DrawAPrize()" :disabled="itemInfo.is_stop == 1">点击开奖</el-button>
                </div> -->
                <el-table :data="InfoData" border stripe style="margin-top:20px;" v-loading="loadingStatus_d">
                    <el-table-column prop="nickname" label="昵称"></el-table-column>
                    <el-table-column label="是否中奖">
                        <template slot-scope="scope">
                            <span v-if="scope.row.is_win==1" class="start">是</span>
                            <span v-else class="stop">否</span>
                        </template>
                    </el-table-column>
                    <el-table-column prop="name" label="奖品">
                        <template slot-scope="scope">
                            <span v-if="scope.row.is_win==1">
                                <span v-if="itemInfo.ac_type==1">天猫精灵</span>
                                <span v-if="itemInfo.ac_type==2">小米手环</span>
                                <span v-if="itemInfo.ac_type==3">Airpods</span>
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column prop="created_at" label="参加活动时间"></el-table-column>
                </el-table>
                <div style="text-align:center;">
                    <el-pagination
                    @size-change="handleSizeChange_a"
                    @current-change="handleCurrentChange_a"
                    :current-page="page_a"
                    :page-sizes="[10, 20, 30 ,50, 100]"
                    :page-size="page_count_a"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total="total_count_a">
                    </el-pagination>
                </div>
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
            loadingStatus:false,
            loadingdown:false,
            page: 1,
            page_count: 10,
            total_count: 0,
            orderData:[],
            name:'',
            srcList:[],
            valuetime:'',
            valuetime_end:'',
            loadingStatus_d:false,
            InfoData:[],
            dialogTableVisible:false,
            page_a: 1,
            page_count_a: 10,
            total_count_a: 0,
            id:'',
            type: 1,
            itemInfo: {},
            DrawAPrizeStatus: false,
            NextType: false,
            parzeList: []
        }
    },
    created () {
        this.getServiceData()
    },
    methods: {
        getLevel(id){
            if(id == 1){
                return '一等奖'
            }else if(id == 2){
                return '二等奖'
            }else if(id == 3){
                return '三等奖'
            }
            return ''
        },
        blur(item){
            var item_data = []
            for (let index = 0; index < this.parzeList.length; index++) {
                if(this.parzeList[index].id == item){
                    item_data.push(this.parzeList[index])
                }
            }
            if(item_data.length){
                this.saveDataPublic(item_data)
            }
        },
        saveDataPublic: function(dataList){
            var parms = {
                dataList: dataList
            }
            this.SaveStatus = true
            this.$http.post(Remote.saveParze, parms).then((response) => {
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
        resetting(item){
            this.$confirm('是否确认重置抽奖环节?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                var parms = {
                    activity: item.encryption
                }
                this.NextType = true
                this.$http.post(Remote.resettingDraw, parms).then((response) => {
                    this.NextType = false
                    if(response.body.code != 200){
                        this.$message({
                            message: response.body.msg,
                            type: 'error'
                        });
                        return
                    }
                    this.$message({
                        type: 'success',
                        message: '操作成功!'
                    });
                    this.getServiceData()
                },function(){
                    this.NextType = false
                    this.$message({
                        message: '操作失败',
                        type: 'error'
                    });
                })
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '已取消操作'
                });          
            });
        },
        NextSession(info){
            this.$confirm('是否确认进入下一抽奖环节?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                var parms = {
                    activity: info.encryption
                }
                this.NextType = true
                this.$http.post(Remote.editNext, parms).then((response) => {
                    this.NextType = false
                    if(response.body.code != 200){
                        this.$message({
                            message: response.body.msg,
                            type: 'error'
                        });
                        return
                    }
                    this.$message({
                        type: 'success',
                        message: '操作成功!'
                    });
                    this.getServiceData()
                },function(){
                    this.NextType = false
                    this.$message({
                        message: '操作失败',
                        type: 'error'
                    });
                })
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '已取消操作'
                });          
            });
        },
        DrawAPrize:function(){
            var parms = {
                activity: this.itemInfo.encryption
            }
            this.DrawAPrizeStatus = true
            this.$http.post(Remote.startpc_dc, parms).then((response) => {
                this.DrawAPrizeStatus = false
                if(response.body.code != 200){
                    this.$message({
                        message: '开奖失败',
                        type: 'error'
                    });
                    return
                }
                this.itemInfo.is_top = 1
                this.$message({
                    message: "开奖成功",
                    type: 'success'
                });
                this.selectdia()
            },function(){
                this.DrawAPrizeStatus = false
                this.$message({
                    message: '开奖失败',
                    type: 'error'
                });
            })
        },
        showdialog:function (info){
            this.id = info.id
            this.page_a = 1
            this.dialogTableVisible = true
            this.itemInfo = info
            console.log(this.itemInfo)
            this.selectdia()
        },
        selectdia:function(){
            this.loadingStatus_d = true
            var query = '?page=' + this.page_a + '&page_size=' + this.page_count_a + '&id=' + this.id
            this.$http.get(Remote.activityinfo + query).then((response) => {
                var data=response.data.data
                this.loadingStatus_d = false
                if (response.data.code == '200') {
                    this.total_count_a = data.count
                    this.InfoData = data.data
                }
            },function(){
                this.loadingStatus = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        changeimg:function (row) {
            this.srcList = [row.qrcode_url]
        },
       
        getServiceData: function () {
            this.loadingStatus = true
            var query = '?page=1&page_size=10&type=0'
            this.$http.get(Remote.getactivitylist + query).then((response) => {
                var data=response.data.data
                if (response.data.code == '200') {
                    this.total_count = data.count
                    var newdata = data.data
                    this.parzeList = data.prizes
                    this.orderData = newdata
                    this.loadingStatus = false
                }
            },function(){
                this.loadingStatus = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        handleSizeChange(val) {
            this.page_count=30
            if(val>0){
                this.page_count=val
            }
            this.getServiceData()
        },
        handleCurrentChange(val) {
            this.page=val==0?1:val
            this.getServiceData()
        },
        handleSizeChange_a(val) {
            this.page_count_a=30
            if(val>0){
                this.page_count_a=val
            }
            this.selectdia()
        },
        handleCurrentChange_a(val) {
            this.page_a=val==0?1:val
            this.selectdia()
        },
    }
}
</script>
<style>
.el-icon-circle-close:before{
    color:black;
}
.daiinfo .el-dialog{
    width: 60% !important;
}
.daiinfo .el-dialog .el-dialog__body{
    padding-top:0;
}
.el-upload-list{
    display: none !important;
}
.el-range-editor--mini.el-input__inner{
    width: 240px;
}
.jiangpin{
    width: 100%;
    text-align: center;
    font-weight: 800;
    font-size: 20px;
}
.data_list{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
}
.data_list_item{
    width: 30%;
    border: 1px solid #ccc;
    border-radius: 10px;
    margin: 10px 0;
    padding: 20px;
    font-size: 14px;
}
.div_pub{
    margin-top: 10px;
}
.level_text{
    text-align: center;
    font-weight: 800;
}
</style>
<style scoped>
.blue{
    color:blue;
    cursor:pointer;
}
.stop{
    color: red;
}
.start{
    color: green; 
}
.qr_img{
    width: 60px; 
    height: 60px;
}
.sel_span{
    font-size: 14px;
}
</style>
