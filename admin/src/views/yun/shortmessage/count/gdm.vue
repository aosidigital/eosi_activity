<template>
    <d2-container>
        <div class="messagePromotionAdd ">
            <!-- 操作按钮 -->
            <div class="mytable">
                <div>
                    <el-upload
                        class="upload-demo"
                        action=""
                        :on-change="UploadStatisticsData"
                        :on-remove="handleRemove"
                        accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                        :auto-upload="false">
                        <el-button id="button1" type="primary" icon="el-icon-upload2" el-upload-list el-upload-list--text :loading="loading1">上传当月数据</el-button>
                    </el-upload>
                    <div>
                        <input id="value1" type="text" v-model="value1" style="width:100px;">
                    </div>
                </div>
                <div>
                    <el-upload
                        class="upload-demo"
                        action=""
                        :on-change="UploadHistoryData"
                        :on-remove="handleRemove"
                        accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                        :auto-upload="false">
                        <el-button id="button2" type="primary" icon="el-icon-upload2" el-upload-list el-upload-list--text :loading="loading2">上传医生上月数据</el-button>
                    </el-upload>
                    <div>
                        <input id="value2" type="text" v-model="value2" style="width:100px;">
                    </div>
                </div>
                <div>
                    <el-upload
                        class="upload-demo"
                        action=""
                        :on-change="UploadHistoryDataBus"
                        :on-remove="handleRemove"
                        accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                        :auto-upload="false">
                        <el-button id="button3" type="primary" icon="el-icon-upload2" el-upload-list el-upload-list--text :loading="loading2">上传business上月数据</el-button>
                    </el-upload>
                    <div>
                        <input id="value3" type="text" v-model="value3" style="width:100px;">
                    </div>
                </div>
                <div>
                    <el-button id="button4" class="upload-demo" type="primary" @click="GetZansData" :loading="loadingZan">更新点赞数据</el-button>
                    <div>
                        <input id="value4" type="text" v-model="value4" style="width:100px;">
                    </div>
                </div>
                <div>
                    <el-button id="button5" class="upload-demo" type="primary" @click="GetUserData" :loading="loadingUser">更新用户数据</el-button>
                    <div>
                        <input id="value5" type="text" v-model="value5" style="width:100px;">
                    </div>
                </div>
                <div>
                    <el-button id="button6" class="upload-demo" type="primary" @click="ClearAll" :loading="loadingClear">清除所有数据</el-button>
                    <div>
                        <input id="value6" type="text" v-model="value6" style="width:100px;">
                    </div>
                </div>
            </div>
            <div class="mytable">
                <div>
                    <el-button id="button7" class="upload-demo" type="primary" @click="DownloadData1">下载医生数据</el-button>
                    <div>
                        <input id="value7" type="text" v-model="value7" style="width:100px;">
                    </div>
                </div>
                <div>
                    <el-button id="button8" class="upload-demo" type="primary" @click="DownloadData2">下载Business数据</el-button>
                    <div>
                        <input id="value8" type="text" v-model="value8" style="width:100px;">
                    </div>
                </div>
            </div>
        </div>
    </d2-container>
</template>

<script>
/*eslint-disable */
import { Remote } from '../../../../remote'
import XLSX from 'xlsx'
import axios from 'axios'
const XLSXS = require('xlsx')
const pinyin = require('tiny-pinyin')
export default {
    data () {
        return {
            dataList: [],
            userList: [],
            userList2: [],
            SaveStatus: false,
            dialogVisible: false,
            from: {},
            loading1: false,
            loading2: false,
            loadingZan: false,
            loadingClear: false,
            loadingUser: false,
            value1: 0,
            value2: 0,
            value3: 0,
            value4: 0,
            value5: 0,
            value6: 0,
            value7: 0,
            value8: 0,
            roles_data: [
                "ANONYMOUS",
                "医生",
                "高德美",
            ],
            aart_arr: [
                "aart - aart-AART - Loaded",
                "aart - aart-FAS - Loaded",
                "aart - aart-HIT - Loaded",
                "aart - -AART-33 - Loaded",
            ],
            new_arr: [
                "News - 2024年4月小红书线上营销热点与趋势洞察 - Loaded",
                "News - 2024年5月小红书线上营销热点与趋势洞察 - Loaded",
                "News - 2024年6月小红书线上营销热点与趋势洞察 - Loaded"
            ],
            product_arr: [
                "Product Detail Page  -",
                "产品手册 -",
                "产品中心",
                "吉适之旅",
                "品牌素材",
                "诊断及评估工具"
            ],
            pfkyy_arr: [
                "皮肤科应用-肌肤焕活-专家共识 - ",
                "皮肤科应用-肌肤焕活-参考文献 - ",
                "皮肤科应用-肌肤焕活-产品应用 - ",
                "皮肤科应用-肌肤焕活-皮肤科名师解读 - ",
                "皮肤科应用-CTMP的临床应用-专家共识 -",
                "皮肤科应用-CTMP的临床应用-产品应用 -",
                "皮肤科应用-CTMP的临床应用-皮肤科名师解读 -",
                "皮肤科应用-肉毒毒素-参考文献 -",
                "皮肤科应用-肉毒毒素-皮肤科名师解读 -",
                "皮肤科应用-敏感性皮肤-产品应用 -",
                "皮肤科应用-敏感性皮肤-皮肤科名师解读 - ",
                "皮肤科应用-痤疮/玫瑰痤疮-临床应用 - 临床应用",
                "皮肤科应用 - 皮肤科应用-痤疮/玫瑰痤疮-皮肤科名师解读-",
                "皮肤科应用 - 皮肤科应用-肌肤焕活-皮肤科名师解读-",
                "皮肤科应用 - 皮肤科应用-敏感性皮肤-皮肤科名师解读-",
                "皮肤科应用 - 皮肤科应用-肉毒毒素-皮肤科名师解读-",
                "皮肤科应用-痤疮/玫瑰痤疮-临床应用 - ",
                "皮肤科应用 - 皮肤科应用-CTMP的临床应用-皮肤科名师解读-",
            ],
            token: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6NCwiaWF0IjoxNzQ4OTMwMzE2LCJleHAiOjE3NTE1MjIzMTZ9.82fZaoIkwbPdjltT63kDHYdNshpRUApAnA1zqWuyrys',
        }
    },
    created () {
        this.getServiceData()
    },
    methods: {
        
        async GetUserData () {
            this.$http.post(Remote.getUser, {}).then((response) => {
                this.loadingUser = false
                if(response.body.code != 200){
                    this.value5 = -1
                    this.$message({
                        message: response.body.msg,
                        type: 'error'
                    });
                    return
                }else{
                    this.value5 = 1
                    this.$message({
                        message: "获取成功",
                        type: 'success'
                    });
                }
            },function(){
                this.value5 = -1
                this.loadingUser = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        async ClearAll () {
            this.loadingClear = true
            this.$http.post(Remote.clearData, {}).then((response) => {
                this.loadingClear = false
                this.value1 = 0
                this.value2 = 0
                this.value3 = 0
                this.value4 = 0
                this.value5 = 0
                this.value7 = 0
                this.value8 = 0

                if(response.body.code != 200){
                    this.value6 = -1
                    this.$message({
                        message: response.body.msg,
                        type: 'error'
                    });
                    return
                }else{
                    this.value6 = 1
                    this.$message({
                        message: "清除成功",
                        type: 'success'
                    });
                }
            },function(){
                this.value6 = -1
                this.loadingClear = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        async GetZansData(){
            this.$http.post(Remote.addZans, {}).then((response) => {
                this.loadingZan = false
                if(response.body.code != 200){
                    this.value4 = -1
                    this.$message({
                        message: response.body.msg,
                        type: 'error'
                    });
                    return
                }else{
                    this.value4 = 1
                    this.$message({
                        message: "获取成功",
                        type: 'success'
                    });
                }
            },function(){
                this.value4 = -1
                this.loadingZan = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        handleRemove(file,fileList){
            this.fileTemp = null
        },
        UploadStatisticsData(file, fileList){
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
        importfxx(obj) {
            let _this = this;
            // 通过DOM取文件数据
            this.file = obj
            var rABS = false; //是否将文件读取为二进制字符串
            var f = this.file;
            var reader = new FileReader();
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
                        obj.time = v['时间']
                        obj.title = v['标题']
                        obj.name = v['姓名']
                        obj.phone = v['手机号']
                        obj.city = v['城市']
                        obj.jigou = v['机构']
                        obj.nums = parseInt(v['访问次数'])
                        obj.start_time = parseInt(v['停留时间'])
                        obj.stop_time = 1
                        obj.leixing = ""
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
        // 剔除特殊数据
        deleteData(item){
            let det_arr = [
                "路人角色",
                "【勿删除】吉适之旅-复溶视频",
                "BUSINESS",
                "GAIN",
                "rddsnfc",
                "投稿记录",
                "学习中心",
            ]
            if(det_arr.includes(item['name'])){
                return true
            }
            if(det_arr.includes(item['title'])){
                return true
            }
            // if(item['title'].includes("BUSINESS - ")){
            //     return true
            // }
            // 删除数据
            if(item['title'].includes("【测试】")){
                return true
            }
            return false
        },
        // 学习中心数据处理
        handleLearningCenter(item){
            // 匹配固定菜单数据，不删除
            if(this.dataList.includes(item['title'])){
                return false
            }
            let new_string = /-\d+/;
            // console.log(item['title'], new_string.test(item['title']))
            if(!new_string.test(item['title'])){
                return true
            }
            return false
        },
        // 产品中心
        handleProductCenter(title){
            let is_true = false
            for (let index = 0; index < this.product_arr.length; index++) {
                if(title.startsWith(this.product_arr[index])){
                    is_true = true
                }
            }
            return is_true
        },
        // 用户角色区分
        Distinguisholes(phone){
            let user_list = this.userList
            let is_true = false
            for (let index = 0; index < user_list.length; index++) {
                if(user_list[index]['phone'] == phone){
                    is_true = true
                    // if(!this.roles_data.includes(user_list[index]['job'])){
                    //     is_true = true
                    // }
                }
            }
            return is_true
        },
        getPifuvalue(item){
            let pifu_arr = this.pfkyy_arr
            let is_true = false
            for (let index = 0; index < pifu_arr.length; index++) {
                if(item['title'].startsWith(pifu_arr[index])){
                    let regexPattern = new RegExp(pifu_arr[index], 'g')
                    is_true = item['title'].replace(regexPattern, "")
                }
            }
            return is_true
        },

        create(excel_list){
            let newData = []
            let yuan_data = []
            let business_data = []
            let new_yuan = []
            let new_business = []
            let new_ys_arr = []
            let new_bas_arr = []
            // 预处理数据
            for (let index = 0; index < excel_list.length; index++) {
                // 处理标题
                excel_list[index]['title'] = excel_list[index]['title'].replace("学习中心", "学术中心").replace("Product-Intro-", "")
                if(this.deleteData(excel_list[index])){
                    continue
                }
                if(excel_list[index]['title'].includes("学术中心 - ")){
                    if(this.handleLearningCenter(excel_list[index])){
                        continue
                    }
                }
                // 对数据进行分类
                let juese = this.Distinguisholes(excel_list[index]['phone'])
                // 新闻分类
                if(this.new_arr.includes(excel_list[index]['title'])){
                    juese = true
                }
                // business特殊分类
                if(excel_list[index]['title'].includes("学术中心 - 经管") || excel_list[index]['title'].includes("学术中心 - 营销") || excel_list[index]['title'].includes("学术中心 - 咨询")){
                    juese = true 
                }
                if(excel_list[index]['title'].includes("BUSINESS - 经管") || excel_list[index]['title'].includes("BUSINESS - 营销") || excel_list[index]['title'].includes("BUSINESS - 咨询")){
                    juese = true 
                }
                
                // 数据处理--产品中心
                if(excel_list[index]['title'].includes("Product Detail Page  -")){
                    let title_p = excel_list[index]['title'].replace(/Product Detail Page  -/g, "").replace(/<sup>/g, "").replace(/<\/sup>/g, "").replace(/ - Loaded/g, "")
                    excel_list[index]['title'] = title_p
                }
                // 数据处理--新闻
                if(excel_list[index]['title'].includes("News - ")){
                    let title_n = excel_list[index]['title'].replace(/News - /g, "").replace(/ - Loaded/g, "")
                    excel_list[index]['title'] = title_n
                    excel_list[index]['leixing'] = "news"
                }
                // 数据处理--皮肤科应用
                let pifu_s = this.getPifuvalue(excel_list[index])
                if(pifu_s){
                    excel_list[index]['title'] = pifu_s
                    excel_list[index]['leixing'] = "pifu"
                }
                // 数据处理--基础必修转成--E-Learning
                if(excel_list[index]['title'].startsWith("基础必修-")){
                    let title_j = excel_list[index]['title'].split("-")
                    excel_list[index]['title'] = "E-Learning - " + title_j[title_j.length - 2] + "-" + title_j[title_j.length - 1]
                }
                // 数据处理--学术中心 - 真实案例-案例精讲视频 - 
                if(excel_list[index]['title'].startsWith("学术中心 - 真实案例-案例精讲视频 -")){
                    excel_list[index]['title'] = excel_list[index]['title'].replace(/学术中心 - 真实案例-案例精讲视频 -/g, "")
                }
                if(juese){
                    business_data.push(excel_list[index])
                }else{
                    yuan_data.push(excel_list[index])
                }
                newData.push(excel_list[index])

                // 处理原始数据
                if(juese){
                    new_bas_arr.push(excel_list[index])
                }else{
                    new_ys_arr.push(excel_list[index])
                }
            }

            console.log(new_bas_arr)
            console.log(new_ys_arr)
            this.ExportDataYS(new_ys_arr)
            this.ExportDataBUS(new_bas_arr)
            // return false
            // 处理原始数据
            for (let index = 0; index < yuan_data.length; index++) {
                let is_true = false
                for (let indexA = 0; indexA < new_yuan.length; indexA++) {
                    if(yuan_data[index]['title'] == new_yuan[indexA]['title'] && yuan_data[index]['leixing'] == new_yuan[indexA]['leixing']){
                        is_true = true
                        new_yuan[indexA]['nums'] += yuan_data[index]['nums']
                        new_yuan[indexA]['start_time'] += yuan_data[index]['start_time']
                        let index_phone = yuan_data[index]['phone']
                        if (!new_yuan[indexA]['mobile_arr'].includes(index_phone)) {
                            new_yuan[indexA]['mobile_arr'].push(index_phone)
                            new_yuan[indexA]['stop_time'] += 1
                        }
                    }
                }
                if(!is_true){
                    yuan_data[index]['mobile_arr'] = [yuan_data[index]['phone']]
                    new_yuan.push(yuan_data[index])
                }
            }
            let data_te_2 = null
            for (let index = new_yuan.length - 1; index >= 0 ; index--) {
                if(new_yuan[index]['title'] == "aart - -AART-33 - Loaded"){
                    data_te_2 = newData[index]
                    new_yuan.splice(index, 1)
                }
            }
            for (let index = 0; index < new_yuan.length; index++) {
                if(new_yuan[index]['title'] == "aart - aart-AART - Loaded" && data_te_2){
                    new_yuan[index]['nums'] += data_te_2['nums']
                    new_yuan[index]['stop_time'] += data_te_2['stop_time']
                }
            }
            new_yuan = new_yuan.map(user => {
                let { mobile_arr, ...rest } = user;
                return { ...rest, type: 1 };
            });
            new_yuan.sort((a, b) => {
                // 将 title 字段转换为小写
                let titleA = a.title.toLowerCase();
                let titleB = b.title.toLowerCase();
                
                // 按 ASCII 顺序比较
                if (titleA < titleB) {
                    return -1;
                }
                if (titleA > titleB) {
                    return 1;
                }
                return 0;
            });

            console.log(new_yuan)

            // busines数据
            for (let index = 0; index < business_data.length; index++) {
                let is_true = false
                for (let indexA = 0; indexA < new_business.length; indexA++) {
                    if(business_data[index]['title'] == new_business[indexA]['title']){
                        is_true = true
                        new_business[indexA]['nums'] += business_data[index]['nums']
                        let index_phone = business_data[index]['phone']
                        if (!new_business[indexA]['mobile_arr'].includes(index_phone)) {
                            new_business[indexA]['mobile_arr'].push(index_phone)
                            new_business[indexA]['stop_time'] += 1
                        }
                    }
                }
                if(!is_true){
                    business_data[index]['mobile_arr'] = [business_data[index]['phone']]
                    new_business.push(business_data[index])
                }
            }
            let data_te_1 = null
            for (let index = new_business.length - 1; index >= 0 ; index--) {
                if(new_business[index]['title'] == "aart - -AART-33 - Loaded"){
                    data_te_1 = newData[index]
                    new_business.splice(index, 1)
                }
            }
            for (let index = 0; index < new_business.length; index++) {
                if(new_business[index]['title'] == "aart - aart-AART - Loaded" && data_te_1){
                    new_business[index]['nums'] += data_te_1['nums']
                    new_business[index]['stop_time'] += data_te_1['stop_time']
                }
            }
            new_business = new_business.map(user => {
                let { mobile_arr, ...rest } = user;
                return { ...rest, type: 2 };
            });
            new_business.sort((a, b) => {
                // 将 title 字段转换为小写
                let titleA = a.title.toLowerCase();
                let titleB = b.title.toLowerCase();
                
                // 按 ASCII 顺序比较
                if (titleA < titleB) {
                    return -1;
                }
                if (titleA > titleB) {
                    return 1;
                }
                return 0;
            });

            console.log(new_business)
            // 合并数据
            let newArray = new_yuan.concat(new_business);
            console.log(newArray)
            
            // return false
            this.loading1 = true
            var parms = {
                data: newArray
            }
            this.loadingStatus = true
            this.$http.post(Remote.addData1, parms).then((response) => {
                this.loadingStatus = false
                if(response.body.code != 200){
                    this.value1 = -1
                    this.$message({
                        message: response.body.msg,
                        type: 'error'
                    });
                    return
                }else{
                    this.value1 = 1
                    this.$message({
                        message: "上传成功",
                        type: 'success'
                    });
                }
                this.loading1 = false
            },function(){
                this.value1 = -1
                this.loadingStatus = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },

        ExportDataYS(data){
            // // 处理数据合并
            // let user_arr = this.userList2
            // for (let index = 0; index < data.length; index++) {
            //     data[index].province = ''
            //     for (let indexA = 0; indexA < user_arr.length; indexA++) {
            //         if(data[index].phone == user_arr[indexA].phone){
            //             data[index].province = user_arr[indexA].job
            //         }
            //     }
            //     data[index].title = data[index].title.trim()
            // }
            // // 上传数据
            // var parms = {
            //     data: data
            // }
            // this.$http.post(Remote.addData3, parms).then((response) => {
            //     if(response.body.code != 200){
            //         this.$message({
            //             message: response.body.msg,
            //             type: 'error'
            //         });
            //         return
            //     }
            //     this.ExportNewData(response.body.data)
            //     this.$message({
            //         message: "上传成功",
            //         type: 'success'
            //     });
            // },function(){
            //     this.$message({
            //         message: '请求失败',
            //         type: 'error'
            //     });
            // })

            let header = ["时间", "标题", "姓名", "手机号", "城市", "机构", "访问次数", "停留时间"]
            let data_arr = []
            for (let index = 0; index < data.length; index++) {
                let new_arr = [
                    data[index].time,
                    data[index].title,
                    data[index].name,
                    data[index].phone,
                    data[index].city,
                    data[index].jigou,
                    data[index].nums,
                    data[index].start_time,
                ]
                data_arr.push(new_arr)
            }
            let data_all = [header, ...data_arr]
            // 将数据转换为工作表
            const worksheet = XLSXS.utils.aoa_to_sheet(data_all);
            // 创建工作簿
            const workbook = XLSXS.utils.book_new();
            XLSXS.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
            // 写入文件
            XLSXS.writeFile(workbook, '医生原始数据.xlsx');
        },
        async ExportNewData(data){
            const headers = {
                headers: {
                    'Authorization': `Bearer ${this.token}`
                },
            }
            let zsal_arr = [
                { name: "下颌案例", c_qwfx: 0, c_alfx: 0, c_count: 0},
                { name: "鼻部案例", c_qwfx: 0, c_alfx: 0, c_count: 0},
                { name: "中面部案例", c_qwfx: 0, c_alfx: 0, c_count: 0},
                { name: "个性轮廓之美", c_qwfx: 0, c_alfx: 0, c_count: 0},
                { name: "个性提升之美", c_qwfx: 0, c_alfx: 0, c_count: 0},
                { name: "肤质改善案例", c_qwfx: 0, c_alfx: 0, c_count: 0},
                { name: "菁英秀案例", c_qwfx: 0, c_alfx: 0, c_count: 0},
                { name: "案例精讲视频", c_qwfx: 0, c_alfx: 0, c_count: 0},
            ]
            let url_path = "https://admin.restylane.com.cn/api/platforms/strapi/content-manager/collection-types/application::tutorial-course.tutorial-course?page=1&pageSize=9999&_sort=name:ASC"
            const response = await axios.get(url_path, headers)
            let results = response.data.results
            for (let index = 0; index < results.length; index++) {
                let element = results[index];
                let learning_sery = element.learning_sery ? element.learning_sery.name : ''
                for (let indexA = 0; indexA < zsal_arr.length; indexA++) {
                    if(learning_sery == zsal_arr[indexA].name){
                        zsal_arr[indexA].c_count += 1
                        if(element.label == 'quan_wei_feng_cai'){
                            zsal_arr[indexA].c_qwfx += 1
                        }
                        if(element.label == 'an_li_fen_xiang'){
                            zsal_arr[indexA].c_alfx += 1
                        }
                    }
                }
            }
            let base_5 = data.base_5
            for (let index = 0; index < base_5.length; index++) {
                for (let indexA = 0; indexA < zsal_arr.length; indexA++) {
                    if(base_5[index].pub_title == zsal_arr[indexA].name){
                        base_5[index].c_qwfx = zsal_arr[indexA].c_qwfx
                        base_5[index].c_alfx= zsal_arr[indexA].c_alfx
                        base_5[index].c_count= zsal_arr[indexA].c_count
                    }
                }
            }
            data.base_5 = base_5
            // console.log("response", data)

            let header = ["标题", "点击率", "停留时间", "权威风采", "案例分享", "上线总数", "使用人数", "类型"]
            let data_arr = []
            let data_last = Object.keys(data);
            for (let index = 0; index < data_last.length; index++) {
                let key = data_last[index];
                let data_info = data[key]
                for (let indexA = 0; indexA < data_info.length; indexA++) {
                    let new_arr = [
                        data_info[indexA].pub_title,
                        data_info[indexA].nums,
                        data_info[indexA].start_time,
                        data_info[indexA].c_qwfx,
                        data_info[indexA].c_alfx,
                        data_info[indexA].c_count,
                        data_info[indexA].user_count,
                        index,
                    ]
                    data_arr.push(new_arr)
                }
            }
            console.log("data_arr", data_arr)
            let data_all = [header, ...data_arr]
            // 将数据转换为工作表
            const worksheet = XLSXS.utils.aoa_to_sheet(data_all);
            // 创建工作簿
            const workbook = XLSXS.utils.book_new();
            XLSXS.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
            // 写入文件
            XLSXS.writeFile(workbook, '统计数据.xlsx');
        },
        ExportDataBUS(data){
            let header = ["时间", "标题", "姓名", "手机号", "城市", "机构", "访问次数", "停留时间"]
            let data_arr = []
            for (let index = 0; index < data.length; index++) {
                let new_arr = [
                    data[index].time,
                    data[index].title,
                    data[index].name,
                    data[index].phone,
                    data[index].city,
                    data[index].jigou,
                    data[index].nums,
                    data[index].start_time,
                ]
                data_arr.push(new_arr)
            }
            let data_all = [header, ...data_arr]
            // 将数据转换为工作表
            const worksheet = XLSXS.utils.aoa_to_sheet(data_all);
            // 创建工作簿
            const workbook = XLSXS.utils.book_new();
            XLSXS.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
            // 写入文件
            XLSXS.writeFile(workbook, 'Business原始数据.xlsx');
        },
        UploadHistoryData(file, fileList){
            this.fileTemp = file.raw
            if(this.fileTemp){
                if((this.fileTemp.type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') || (this.fileTemp.type == 'application/vnd.ms-excel')){
                    this.importfxx1(this.fileTemp, 0)
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
        UploadHistoryDataBus(file, fileList){
            this.fileTemp = file.raw
            if(this.fileTemp){
                if((this.fileTemp.type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') || (this.fileTemp.type == 'application/vnd.ms-excel')){
                    this.importfxx1(this.fileTemp, 1)
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
        importfxx1(obj, type) {
            let _this = this;
            // 通过DOM取文件数据
            this.file = obj
            var rABS = false; //是否将文件读取为二进制字符串
            var f = this.file;
            var reader = new FileReader();
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
                        obj.class_1 = v['大分类']
                        obj.class_2 = v['二级分类']
                        obj.class_3 = v['三级分类']
                        obj.class_4 = v['四级分类']
                        obj.class_5 = v['五级分类']
                        obj.title = _this.itExists(v['标题'])
                        obj.source = _this.itExists(v['来源'])
                        obj.time = _this.itExists(v['时间'])
                        obj.pv = _this.itExists(v['PV'])
                        // obj.pv_gr = _this.itExists(v['PV GR'])
                        obj.uv = _this.itExists(v['UV'])
                        // obj.uv_gr = _this.itExists(v['UV GR'])
                        obj.zb_pv = _this.itExists(v['直播PV'])
                        obj.zb_pv_gr = _this.itExists(v['直播PV GR'])
                        obj.zb_uv = _this.itExists(v['直播UV'])
                        obj.zb_uv_gr = _this.itExists(v['直播UV GR'])
                        obj.like = _this.itExists(v['Like'])
                        // obj.like_gr = _this.itExists(v['Like GR'])
                        obj.zb_yy = _this.itExists(v['直播预约'])
                        obj.type = type
                        // obj.class_1_p = _this.getPinyin(v['大分类'])
                        // obj.class_2_p = _this.getPinyin(v['二级分类'])
                        // obj.class_3_p = _this.getPinyin(v['三级分类'])
                        // obj.class_4_p = _this.getPinyin(v['四级分类'])
                        // obj.class_5_p = _this.getPinyin(v['五级分类'])
                        arr.push(obj)
                    })
                    _this.create1(arr,type)
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
        itExists(string){
            if(typeof(string) == 'undefined'){
                return
            }
            return string
        },
        getPinyin(string){
            if (pinyin.isSupported()) {
                return pinyin.convertToPinyin(string) // WO
            }
            return null
        },
        create1(excel_list,type){
            this.loading2 = true
            console.log(excel_list)
            // return
            var parms = {
                data: excel_list
            }
            this.loadingStatus = true
            this.$http.post(Remote.addData2, parms).then((response) => {
                this.loadingStatus = false
                if(response.body.code != 200){
                    if(type == 0){
                        this.value2 = -1
                    }
                    if(type == 1){
                        this.value3 = -1
                    }
                    this.$message({
                        message: response.body.msg,
                        type: 'error'
                    });
                    return
                }else{
                    if(type == 0){
                        this.value2 = 1
                    }
                    if(type == 1){
                        this.value3 = 1
                    }
                    this.$message({
                        message: "上传成功",
                        type: 'success'
                    });
                }
                this.loading2 = false
            },function(){
                if(type == 0){
                    this.value2 = -1
                }
                if(type == 1){
                    this.value3 = -1
                }
                this.loadingStatus = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        DownloadData1(){
            var parms = {
                // type: 12
            }
            this.$http.post(Remote.getData3, parms).then((response) => {
                this.loadingStatus = false
                if(response.body.code != 200){
                    this.value7 = -1
                    this.$message({
                        message: response.body.msg,
                        type: 'error'
                    });
                    return
                }else{
                    let data = response.body.data
                    this.ExportData1(data)
                    this.value7 = 1
                }
                this.loading3 = false
            },function(){
                this.value7 = -1
                this.loadingStatus = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        ExportData1(data){
            // console.log(data)
            let header = ["大分类", "二级分类", "三级分类", "四级分类", "五级分类", "标题", "来源", "时间", "PV", "PV GR", "UV", "UV GR", "直播PV", "直播PV GR", "直播UV", "直播UV GR", "Like", "Like GR", "直播预约"]
            let data_arr = []
            for (let index = 0; index < data.length; index++) {
                let new_arr = [
                    data[index].class_1,
                    data[index].class_2,
                    data[index].class_3,
                    data[index].class_4,
                    data[index].class_5,
                    data[index].title,
                    data[index].source,
                    data[index].time,
                    data[index].pv,
                    data[index].pv_gr,
                    data[index].uv,
                    data[index].uv_gr,
                    data[index].zb_pv,
                    data[index].zb_pv_gr,
                    data[index].zb_uv,
                    data[index].zb_uv_gr,
                    data[index].likes,
                    data[index].like_gr,
                    data[index].zb_yy,
                ]
                data_arr.push(new_arr)
            }
            let data_all = [header, ...data_arr]
            // console.log(data_all)
            // return
            // 将数据转换为工作表
            const worksheet = XLSXS.utils.aoa_to_sheet(data_all);
            // 创建工作簿
            const workbook = XLSXS.utils.book_new();
            XLSXS.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
            // 写入文件
            XLSXS.writeFile(workbook, '医生数据.xlsx');
        },
        DownloadData2(){
            var parms = {
                // type: 11
            }
            this.$http.post(Remote.getData4, parms).then((response) => {
                this.loadingStatus = false
                if(response.body.code != 200){
                    this.value8 = -1
                    this.$message({
                        message: response.body.msg,
                        type: 'error'
                    });
                    return
                }else{
                    let data = response.body.data
                    this.ExportData2(data)
                    this.value8 = 1
                }
                this.loading3 = false
            },function(){
                this.value8 = -1
                this.loadingStatus = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        ExportData2(data){
            console.log(data)
            // return
            let header = ["大分类", "二级分类", "三级分类", "四级分类", "五级分类", "标题", "来源", "时间", "PV", "PV GR", "UV", "UV GR"]
            let data_arr = []
            for (let index = 0; index < data.length; index++) {
                let new_arr = [
                    data[index].class_1,
                    data[index].class_2,
                    data[index].class_3,
                    data[index].class_4,
                    data[index].class_5,
                    data[index].title,
                    data[index].source,
                    data[index].time,
                    data[index].pv,
                    data[index].pv_gr,
                    data[index].uv,
                    data[index].uv_gr,
                ]
                data_arr.push(new_arr)
            }
            let data_all = [header, ...data_arr]
            // console.log(data_all)
            // return
            // 将数据转换为工作表
            const worksheet = XLSXS.utils.aoa_to_sheet(data_all);
            // 创建工作簿
            const workbook = XLSXS.utils.book_new();
            XLSXS.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
            // 写入文件
            XLSXS.writeFile(workbook, 'Business.xlsx');
        },
        getServiceData: function () {
            this.$http.get(Remote.getMenus).then((response) => {
                var data = response.data.data
                this.dataList = data.menus
                this.userList = data.users
                this.userList2 = data.user2
            },function(){
                this.loadingStatus = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        formatDate(numb, type) {
            const time = new Date(numb * 24 * 3600000 - 3600000 * type + 1)
            time.setYear(time.getFullYear() - 70)
            const year = time.getFullYear() + ''
            const month = time.getMonth() + 1 + ''
            const date = time.getDate() - 1 + ''
            return year + "-" + month + "-" + date
        },
        chunkArray(array, size) {
            let result = [];
            for (let i = 0; i < array.length; i += size) {
                let chunk = array.slice(i, i + size);
                result.push(chunk);
            }
            return result;
        },
    }
}
</script>
<style scoped>
.mytable{
    display: flex;
    width: 100%;
    margin-top: 20px;
}
.upload-demo{
    margin-left: 10px;
}
</style>
