<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <div>
                <div style="margin: 20px 0px;overflow: auto;">
                    <el-col style="width: 160px;margin-right:20px;">
                        <el-input v-model="mobile" placeholder="手机号" clearable size="mini"></el-input>
                    </el-col>
                    <el-col style="width: 90px;" >
                        <el-button type="primary" icon="el-icon-search" size="mini" :loading="loadingStatus" @click="getServiceData()">搜索</el-button>
                    </el-col>
                    <el-col style="width: 160px;" >
                        <el-button type="primary" icon="el-icon-download" size="mini" :loading="loadingStatus" @click="downloadexcel()">点击下载Excel示例</el-button>
                    </el-col>
                    <el-col style="width: 150px;" >
                        <el-upload
                            class="upload-demo"
                            action=""
                            :on-change="handleChange"
                            :on-remove="handleRemove"
                            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                            :auto-upload="false">
                            <el-button type="primary" size="mini" icon="el-icon-upload2" el-upload-list el-upload-list--text :loading="loadingStatus">点击上传Excel</el-button>
                        </el-upload>
                    </el-col>
                </div>
            </div>
            <el-table :data="orderData" border stripe style="margin-top:20px;" v-loading="loadingStatus">
                <el-table-column prop="name" label="昵称"></el-table-column>
                <el-table-column prop="mobile" label="手机号"></el-table-column>
                <el-table-column prop="created_at" label="添加时间"></el-table-column>
                <el-table-column label="操作" width="200">
                    <template slot-scope="scope">
                        <el-button type="danger" size="mini" @click="deldata(scope.row)">删除</el-button>
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
            mobile:'',
        }
    },
    created () {
        this.getServiceData()
    },
    methods: {
        downloadexcel(){
            window.location.href = Remote.baseUrl + '/blacklist.xlsx'
        },
        deldata(item){
            this.$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                this.$http.post(Remote.delbacklist, {id: item.id}).then((response) => {
                    if (response.data.code == '200') {
                        this.$message({
                            type: 'success',
                            message: '删除成功!'
                        });
                    }
                    this.getServiceData()
                },function(){
                    this.loadingStatus = false
                    this.$message({
                        message: '请求失败',
                        type: 'error'
                    });
                })
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '已取消删除'
                });          
            });
        },
        handleChange(file, fileList){
            this.fileTemp = file.raw
            if(this.fileTemp){
                if((this.fileTemp.type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') || (this.fileTemp.type == 'application/vnd.ms-excel')){
                    this.importfxx(this.fileTemp)
                } else {
                    this.$message({
                        type:'warning',
                        message:'附件格式错误，请删除后重新上传！'
                    })
                }
            } else {
                this.$message({
                    type:'warning',
                    message:'请上传附件！'
                })
            }
        },
        handleRemove(file,fileList){
            this.fileTemp = null
        },
        importfxx(obj) {
            let _this = this;
            // 通过DOM取文件数据
            this.file = obj
            var rABS = false; //是否将文件读取为二进制字符串
            var f = this.file;
            var reader = new FileReader();
            //if (!FileReader.prototype.readAsBinaryString) {
            FileReader.prototype.readAsBinaryString = function(f) {
                var binary = "";
                var rABS = false; //是否将文件读取为二进制字符串
                var pt = this;
                var wb; //读取完成的数据
                var outdata;
                var reader = new FileReader();
                reader.onload = function(e) {
                var bytes = new Uint8Array(reader.result);
                var length = bytes.byteLength;
                for(var i = 0; i < length; i++) {
                    binary += String.fromCharCode(bytes[i]);
                }
                var XLSX = require('xlsx');
                if(rABS) {
                    wb = XLSX.read(btoa(fixdata(binary)), { //手动转化
                        type: 'base64'
                    });
                } else {
                    wb = XLSX.read(binary, {
                        type: 'binary'
                    });
                }
                outdata = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]);//outdata就是你想要的东西
                    this.da = [...outdata]
                    let arr = []
                    this.da.map(v => {
                        let obj = {}
                        obj.name = v['姓名']
                        obj.mobile = v['手机号']
                        arr.push(obj)
                    })
                    _this.create(arr)
                    return arr
                }
                reader.readAsArrayBuffer(f);
            }
            
            if(rABS) {
                reader.readAsArrayBuffer(f);
            } else {
                reader.readAsBinaryString(f);
            }
        },
        // 请求新建用户
        create(excel_list) {
            console.log(excel_list)
            var parms = {
                data: excel_list
            }
            this.loadingStatus = true
            this.$http.post(Remote.addbacklist, parms).then((response) => {
                this.loadingStatus = false
                if(response.body.code != 200){
                    this.$message({
                        message: response.body.msg,
                        type: 'error'
                    });
                    return
                }
                this.$message({
                    message: "上传成功",
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
        
        getServiceData: function () {
            this.loadingStatus = true
            var query = '?page=' + this.page + '&page_size=' + this.page_count + '&mobile=' + this.mobile
            this.$http.get(Remote.backlist + query).then((response) => {
                var data=response.data.data
                if (response.data.code == '200') {
                    this.total_count = data.count
                    this.orderData = data.data
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
        }
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
</style>
