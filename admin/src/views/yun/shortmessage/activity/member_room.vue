<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <div style="margin-bottom: 20px;overflow: auto;">
                <el-col style="width: 250px;margin-right:20px;">
                    <el-input v-model="name" placeholder="请输入姓名、手机号、房间号查询" clearable size="mini"></el-input>
                </el-col>
                <el-col style="width: 90px;" >
                    <el-button type="primary" icon="el-icon-search" size="mini" :loading="loadingStatus" @click="getServiceData()">搜索</el-button>
                </el-col>

                <el-col style="width: 150px;float: right;" >
                    <el-button type="primary" icon="el-icon-refresh" size="mini" :loading="loadingStatus" @click="RoomAssignments()">一键分配房间</el-button>
                </el-col>
            </div>
            <el-table :data="orderData" border stripe style="margin-top:20px;" v-loading="loadingStatus">
                <el-table-column prop="name" label="姓名"></el-table-column>
                <el-table-column prop="phone" label="手机号"></el-table-column>
                <el-table-column prop="sex" label="性别"></el-table-column>
                <el-table-column prop="age" label="年龄"></el-table-column>
                <!-- <el-table-column prop="number" label="虚拟房间号"></el-table-column> -->
                <el-table-column prop="room" label="房间号"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <span class="blue" @click="lookmessage(scope.row)">查看详情</span>
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
            <el-dialog title="房间详情" :visible.sync="dialogTableVisible" class="daiinfo">
                <el-table :data="messageData" border stripe style="margin-top:20px;">
                    <el-table-column prop="name" label="姓名"></el-table-column>
                    <el-table-column prop="phone" label="手机号"></el-table-column>
                    <el-table-column prop="sex" label="性别"></el-table-column>
                    <el-table-column prop="age" label="年龄"></el-table-column>
                    <!-- <el-table-column prop="number" label="虚拟房间号"></el-table-column> -->
                    <el-table-column prop="room" label="房间号"></el-table-column>
                </el-table>
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
            page: 1,
            page_count: 10,
            total_count: 0,
            orderData:[],
            name:'',
            id:'',
            type: 1,
            dialogTableVisible:false,
            messageData: [],
        }
    },
    created () {
        this.getServiceData()
    },
    methods: {
        RoomAssignments(){
            this.$http.get(Remote.designated_room).then((response) => {
                this.$message({
                    message: "分配成功",
                    type: 'success'
                });
                this.getServiceData()
            },function(){
                this.loadingStatus = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        lookmessage: function(item){
            this.messageData = []
            this.dialogTableVisible = true
            var query = '?page=1&page_size=10&room=' + item.room
            this.$http.get(Remote.guest_list + query).then((response) => {
                var data = response.data.data
                if (response.data.code == '200') {
                    this.messageData = data.data
                }
            },function(){
                this.loadingStatus = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        getServiceData: function () {
            this.loadingStatus = true
            var query = '?page=1&page_size=10&mobile=' + this.name
            this.$http.get(Remote.guest_list + query).then((response) => {
                var data=response.data.data
                if (response.data.code == '200') {
                    this.total_count = data.count
                    var newdata = data.data
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
<style scoped>
.blue{
    color:blue;
    cursor:pointer;
}
</style>
