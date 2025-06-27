<?php 
namespace App\Http\Controllers;
use DB;
use Input;
use Response;
use Illuminate\Http\Request;
use App\Models\LdActivity;
use App\Models\LdBlackList;
use App\Models\LdActivityMember;
use App\Models\LdCity;
use App\Models\Guest;
use App\Models\Number;
use App\Models\Product;
use App\Models\LdPrize;
use App\Models\Data1;
use App\Models\Data2;
use App\Models\Menus;
use App\Models\Users;
use App\Models\Zans;

class AdminController extends Controller {
    
    /**
     * 初始化
     *
     * @Author   sunhongwei
     * @DateTime 2019-10-14
     *
     * @param    {string}
     */
    public function __construct()
    {
        $this->ModelLdActivity = new LdActivity();
        $this->LdActivityMember = new LdActivityMember();
        $this->LdBlackList = new LdBlackList();
        $this->LdCityModel = new LdCity();
        $this->GuestModel = new Guest();
        $this->NumberModel = new Number();
        $this->ProductModel = new Product();
        $this->LdPrizeModel = new LdPrize();
        $this->Data1Model = new Data1();
        $this->Data2Model = new Data2();
        $this->MenusModel = new Menus();
        $this->UsersModel = new Users();
        $this->ZansModel = new Zans();
        $this->StrapiToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6NCwiaWF0IjoxNzQ4OTMwMzE2LCJleHAiOjE3NTE1MjIzMTZ9.82fZaoIkwbPdjltT63kDHYdNshpRUApAnA1zqWuyrys";
        $this->OmiToken = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICI0XzRTd2Rvc2EwNXU2ZHRzUndIalpWS0hTSDhtSnJhc2p5MlFGUlVBZEFnIn0.eyJleHAiOjE3NDk3MDExMjAsImlhdCI6MTc0OTcwMDgyMCwianRpIjoiYTAyNjJjYjctMjM1Mi00NzY2LThhMDUtMTFkZTJkMWIzMTc2IiwiaXNzIjoiaHR0cHM6Ly9hZG1pbi5yZXN0eWxhbmUuY29tLmNuL2F1dGgvcmVhbG1zL29tbmkiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNWFlMzhmOTEtMTAwMi00NjBmLWE5MmQtNWIwN2JkMDIwN2NjIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoib21uaS1hZG1pbi1jbGllbnQiLCJzZXNzaW9uX3N0YXRlIjoiOTRjOTk1NGEtNGFiZC00MWQ3LWFiNzctZDliYTI5MzFhYjc5IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1vbW5pIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19LCJvbW5pLWFkbWluLWNsaWVudCI6eyJyb2xlcyI6WyJjb250YWN0cyJdfX0sInNjb3BlIjoiZW1haWwgcHJvZmlsZSIsInNpZCI6Ijk0Yzk5NTRhLTRhYmQtNDFkNy1hYjc3LWQ5YmEyOTMxYWI3OSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJyb2xlcyI6Iltjb250YWN0c10iLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiJwcm9kLmdhbGRlcm1hIn0.U376uvMnt0c0jGDysLA2th8RH921F2qB4Mlzqken4uZnm7yVdfxp69SxiYWN66QxTv7No2Ok3wA8GSP7ukpvaPZ3T0I8feC1kleK3yyVmPMBFWnCTpd2AOU_9bOF668zYgT22VSm5dQeXYbrqpDqFYqAEHgMjfW4KXc4dVjZZwbmGbfimqkxRZ3fVl3FWu1-5hF5LvtggXGmuPGjBgMZ6g5rPtuv7Bd6Rr_OVYCGiua3fNGqy9VGtMAACspE46orZvowfVIFlmFhUrBxLf3oiH62Wc0MgnYDDyvJmgApzsy4mIfDXjzPWYyuaKhFLRfxhbWCU48bOCMQEF_BvgkTww";
        // $this->TokenHost= "http://10.1.1.141:5001/get_token";
        $this->TokenHost= "http://git.eosi.com.cn:5001/get_token";
    }

    /**高德美月报相关接口 */

    /**
     * 上传当月数据
     */
    public function addData1(Request $request){
        try {
            $input = $request->all();
            $data = isset($input['data'])?$input['data']:"";
            # 批量插入数据
            foreach ($data as $key => $value) {
                $res = $this->Data1Model->insert($value);
            }
            // $this->Data1Model->insert($data);
            return response()->json(['code' => 200, 'msg' => '', "data" => "成功"]);
        } catch (\Exception $e) {
            return response()->json(['code' => 302, 'msg' => $e->getMessage(), "data" => '']);
        }
    }

    /**
     * 上传上月历史数据
     */
    public function addData2(Request $request){
        try {
            $input = $request->all();
            $data = isset($input['data'])?$input['data']:"";
            # 批量插入数据
            $newdata = [];
            foreach ($data as $key => $value) {
                $times = null;
                if(isset($value['time'])){
                    if(strlen($value['time']) != 10){
                        $times = $this->ExcelTimeCHange($value['time']);
                    }else{
                        $times = $value['time'];
                    }
                }
                $newobj = [
                    "class_1" => isset($value['class_1'])?$value['class_1']:"",
                    "class_2" => isset($value['class_2'])?$value['class_2']:"",
                    "class_3" => isset($value['class_3'])?$value['class_3']:"",
                    "class_4" => isset($value['class_4'])?$value['class_4']:"",
                    "class_5" => isset($value['class_5'])?$value['class_5']:"",
                    "title" => isset($value['title'])?$value['title']:"",
                    "source" => isset($value['source'])?$value['source']:"",
                    "time" => $times,
                    "pv" => isset($value['pv'])?$value['pv']:"",
                    "pv_gr" => isset($value['pv_gr'])?$value['pv_gr']:"",
                    "uv" => isset($value['uv'])?$value['uv']:"",
                    "uv_gr" => isset($value['uv_gr'])?$value['uv_gr']:"",
                    "zb_pv" => isset($value['zb_pv'])?$value['zb_pv']:"",
                    "zb_pv_gr" => isset($value['zb_pv_gr'])?$value['zb_pv_gr']:"",
                    "zb_uv" => isset($value['zb_uv'])?$value['zb_uv']:"",
                    "zb_uv_gr" => isset($value['zb_uv_gr'])?$value['zb_uv_gr']:"",
                    "likes" => isset($value['like'])?$value['like']:"",
                    "like_gr" => isset($value['like_gr'])?$value['like_gr']:"",
                    "zb_yy" => isset($value['zb_yy'])?$value['zb_yy']:"",
                    "class_1_p" => isset($value['class_1_p'])?$value['class_1_p']:"",
                    "class_2_p" => isset($value['class_2_p'])?$value['class_2_p']:"",
                    "class_3_p" => isset($value['class_3_p'])?$value['class_3_p']:"",
                    "class_4_p" => isset($value['class_4_p'])?$value['class_4_p']:"",
                    "class_5_p" => isset($value['class_5_p'])?$value['class_5_p']:"",
                    "type" => $value['type']
                ];
                $newdata[] = $newobj;
            }
            $res = $this->Data2Model->insert($newdata);
            return response()->json(['code' => 200, 'msg' => '', "data" => "成功"]);
        } catch (\Exception $e) {
            return response()->json(['code' => 302, 'msg' => $e->getMessage(), "data" => '']);
        }
    }






    /**
     * 上传分析数据
     */
    public function addData3(Request $request){
        try {
            $input = $request->all();
            $data = isset($input['data'])?$input['data']:"";
            // 处理原始数据
            foreach ($data as $key => $value) {
                $title = explode("-", $value['title']);
                $data[$key]['class1'] = isset($title[0])?trim($title[0]):"";
                $data[$key]['class2'] = isset($title[1])?trim($title[1]):"";
                $data[$key]['class3'] = isset($title[2])?trim($title[2]):"";
                $data[$key]['class4'] = isset($title[3])?trim($title[3]):"";
                $data[$key]['class5'] = isset($title[4])?trim($title[4]):"";
                $data[$key]['people_arr'] = [];
                $data[$key]['user_count'] = 0;
            }
            # 数据分析
            // 1、几个大板块: 前沿资讯,限时返场,真实案例,皮肤科应用,产品中心,基础必修,进阶培训,个性美学,AART
            // 2、基础必修中的几个板块：中面部，面部解剖，透明质酸，肉毒毒素，摄影技巧
            // 3、进阶培训中的几个板块：透明质酸系列，肌肤焕活系列，肉毒毒素系列，法思丽系列
            // 4、个性美学中的几个板块：AART, FAS, HIT, FACE
            // 5、真实案例中的几个板块：下颌案例，鼻部案例，中面部案例，个性轮廓之美案例，个性提升之美案例，肤质改善案例，菁英秀案例，案例精讲视频
            // 6、AART中几个板块：AART, FAS, FACE BY GALDERMA, HIT
            $base_1 = [];
            $base_2 = [];
            $base_3 = [];
            $base_4 = [];
            $base_5 = [];
            $base_6 = [];
            $org_data = [];
            $pro_data = [];
            $training_data = [];
            foreach ($data as $key => $value) {
                $base_1 = $this->get_base_1($value, $base_1);
                $base_2 = $this->get_base_2($value, $base_2);
                $base_3 = $this->get_base_3($value, $base_3);
                $base_4 = $this->get_base_4($value, $base_4);
                $base_5 = $this->get_base_5($value, $base_5);
                $base_6 = $this->get_base_6($value, $base_6);
                // 合并机构数据
                $org_data = $this->get_org_data($value, $org_data);
                // 合并省份数据
                $pro_data = $this->get_pro_data($value, $pro_data);
                // 合并进阶培训数据
                $training_data = $this->get_training_data($value, $training_data);
            }
            // 1、降序排序
            usort($base_1, function($a, $b) {
                return $b['nums'] <=> $a['nums'];
            });
            // 2、降序排序
            usort($base_2, function($a, $b) {
                return $b['nums'] <=> $a['nums'];
            });
            // 3、降序排序
            usort($base_3, function($a, $b) {
                return $b['nums'] <=> $a['nums'];
            });
            // 4、降序排序
            usort($base_4, function($a, $b) {
                return $b['nums'] <=> $a['nums'];
            });
            // 5、降序排序
            usort($base_5, function($a, $b) {
                return $b['nums'] <=> $a['nums'];
            });
            // 6、降序排序
            usort($base_6, function($a, $b) {
                return $b['nums'] <=> $a['nums'];
            });
            // 7、停留时长最高的机构TOP10
            $long_time_org = $org_data;
            usort($long_time_org, function($a, $b) {
                return $b['start_time'] <=> $a['start_time'];
            });
            // 8、使用人数最多的机构TOP10
            $long_user_count_org = $org_data;
            usort($long_user_count_org, function($a, $b) {
                return $b['user_count'] <=> $a['user_count'];
            });
            // 9、停留时长最高的机构TOP10
            $long_city_org = $pro_data;
            usort($long_city_org, function($a, $b) {
                return $b['user_count'] <=> $a['user_count'];
            });
            // 10、停留时长最高的机构TOP10
            usort($training_data, function($a, $b) {
                return $b['nums'] <=> $a['nums'];
            });
            $resurt = [
                "base_1" => $base_1,
                "base_2" => $base_2,
                "base_3" => $base_3,
                "base_4" => $base_4,
                "base_5" => $base_5,
                "base_6" => $base_6,
                "long_time_org" => array_slice($long_time_org, 0, 10),
                "long_user_count_org" => array_slice($long_user_count_org, 0, 10),
                "long_city_org" => array_slice($long_city_org, 0, 10),
                "training_data" => array_slice($training_data, 0, 5),
            ];
            return response()->json(['code' => 200, 'msg' => '', "data" => $resurt]);
        } catch (\Exception $e) {
            return response()->json(['code' => 302, 'msg' => $e->getMessage(), "data" => '']);
        }
    }

    function get_training_data($value, $training_data){
        $title = $value['title'];
        $class2 = $value['class2'];
        $class4 = $value['class4'];
        $class5 = $value['class5'];
        if($class2 == "进阶培训" && strpos($title, "Loaded") !== false){
            if(intval($class5) > 0){
                $value['pub_title'] = $class4;
            }else{
                $value['pub_title'] = $class5;
            }
            $checked = false;
            foreach ($training_data as $key => $value_a) {
                if($value['pub_title'] == $value_a['pub_title']){
                    $checked = true;
                    $training_data[$key]["nums"] += $value['nums'];
                }
            }
            if(!$checked){
                $training_data[] = $value;
            }
        }
        return $training_data;
    }

    function get_pro_data($value, $org_data){
        $province = $value['province'];
        $exc_arr = ["", "　"];
        if(!in_array($province, $exc_arr)){
            $value['pub_title'] = $province;
            $checked = false;
            foreach ($org_data as $key => $value_a) {
                if($value['pub_title'] == $value_a['pub_title']){
                    $checked = true;
                    $org_data[$key]["start_time"] += $value['start_time'];
                    if(!in_array($value['phone'], $value_a['people_arr'])){
                        $org_data[$key]["people_arr"][] = $value['phone'];
                        $org_data[$key]["user_count"] += 1;
                    }
                }
            }
            if(!$checked){
                $org_data[] = $value;
            }
        }
        return $org_data;
    }

    function get_org_data($value, $org_data){
        $exc_arr = ["", "　", "其他", "高德美"];
        $jigou = $value['jigou'];
        if(!in_array($jigou, $exc_arr)){
            $value['pub_title'] = $jigou;
            $checked = false;
            foreach ($org_data as $key => $value_a) {
                if($value['pub_title'] == $value_a['pub_title']){
                    $checked = true;
                    $org_data[$key]["start_time"] += $value['start_time'];
                    if(!in_array($value['phone'], $value_a['people_arr'])){
                        $org_data[$key]["people_arr"][] = $value['phone'];
                        $org_data[$key]["user_count"] += 1;
                    }
                }
            }
            if(!$checked){
                $org_data[] = $value;
            }
        }
        return $org_data;
    }

    function get_base_6($value, $base_6){
        $title3_arr = ["AART", "FAS", "HIT", "FACE BY GALDERMA"];
        $title1 = $value['class1'];
        $title3 = $value['class3'];
        
        if($title1 == "aart" && in_array($title3, $title3_arr)){
            $value['pub_title'] = $title3;
            $checked = false;
            foreach ($base_6 as $key => $value_a) {
                if($value['pub_title'] == $value_a['pub_title']){
                    $checked = true;
                    $base_6[$key]["nums"] += $value['nums'];
                }
            }
            if(!$checked){
                $base_6[] = $value;
            }
        }
        return $base_6;
    }

    function get_base_5($value, $base_5){
        $title3_arr = ["下颌案例", "鼻部案例", "中面部案例", "个性轮廓之美", "个性提升之美", "肤质改善案例", "菁英秀案例", "案例精讲视频"];
        $title2 = $value['class2'];
        $title3 = $value['class3'];
        $title4 = $value['class4'];
        
        $is_true = 0;
        if(in_array($title3, $title3_arr)){
            $is_true = 1;
        }
        if(in_array($title4, $title3_arr)){
            $is_true = 2;
        }
        if($title2 == "真实案例" && $is_true){
            if($is_true == 1){
                $value['pub_title'] = $title3;
            }
            if($is_true == 2){
                $value['pub_title'] = $title4;
            }
            $checked = false;
            foreach ($base_5 as $key => $value_a) {
                if($value['pub_title'] == $value_a['pub_title']){
                    $checked = true;
                    $base_5[$key]["nums"] += $value['nums'];
                }
            }
            if(!$checked){
                $base_5[] = $value;
            }
        }
        return $base_5;
    }

    function get_base_4($value, $base_4){
        $title3_arr = ["AART", "FAS", "HIT", "FACE"];
        $title2 = $value['class2'];
        $title3 = $value['class3'];
        
        if($title2 == "个性美学" && in_array($title3, $title3_arr)){
            $value['pub_title'] = $title3;
            $checked = false;
            foreach ($base_4 as $key => $value_a) {
                if($value['pub_title'] == $value_a['pub_title']){
                    $checked = true;
                    $base_4[$key]["nums"] += $value['nums'];
                }
            }
            if(!$checked){
                $base_4[] = $value;
            }
        }
        return $base_4;
    }

    function get_base_3($value, $base_3){
        $title3_arr = ["透明质酸系列", "肌肤焕活系列", "肉毒毒素系列", "法思丽系列"];
        $title2 = $value['class2'];
        $title3 = $value['class3'];
        
        if($title2 == "进阶培训" && in_array($title3, $title3_arr)){
            $value['pub_title'] = $title3;
            $checked = false;
            foreach ($base_3 as $key => $value_a) {
                if($value['pub_title'] == $value_a['pub_title']){
                    $checked = true;
                    $base_3[$key]["nums"] += $value['nums'];
                }
            }
            if(!$checked){
                $base_3[] = $value;
            }
        }
        return $base_3;
    }

    function get_base_2($value, $base_2){
        $title3_arr = ["中面部", "面部解剖", "透明质酸", "肉毒毒素", "摄影技巧"];
        $title2 = $value['class2'];
        $title3 = $value['class3'];
        $title4 = $value['class4'];
        
        $is_true = 0;
        if(in_array($title3, $title3_arr)){
            $is_true = 1;
        }
        if(in_array($title4, $title3_arr)){
            $is_true = 2;
        }
        if($title2 == "基础必修" && $is_true){
            if($is_true == 1){
                $value['pub_title'] = $title3;
            }
            if($is_true == 2){
                $value['pub_title'] = $title4;
            }
            $checked = false;
            foreach ($base_2 as $key => $value_a) {
                if($value['pub_title'] == $value_a['pub_title']){
                    $checked = true;
                    $base_2[$key]["nums"] += $value['nums'];
                }
            }
            if(!$checked){
                $base_2[] = $value;
            }
        }
        return $base_2;
    }

    function get_base_1($value, $base_1){
        $title1_arr = ["前沿资讯", "限时返场", "皮肤科应用", "产品中心", "AART"];
        $title2_arr = ["真实案例", "基础必修", "进阶培训", "个性美学"];
        $title1 = $value['class1'];
        $title2 = $value['class2'];
        
        $is_true = 0;
        if(in_array($title1, $title1_arr)){
            $is_true = 1;
        }
        if(in_array($title2, $title2_arr)){
            $is_true = 2;
        }
        if($is_true){
            if($is_true == 1){
                $value['pub_title'] = $title1;
            }
            if($is_true == 2){
                $value['pub_title'] = $title2;
            }
            $checked = false;
            foreach ($base_1 as $key => $value_a) {
                if($value['pub_title'] == $value_a['pub_title']){
                    $checked = true;
                    $base_1[$key]["nums"] += $value['nums'];
                }
            }
            if(!$checked){
                $base_1[] = $value;
            }
        }
        return $base_1;
    }


    /**
     * 医生数据整合
     */
    public function getData3(Request $request){
        try {
            $input = $request->all();
            if(!isset($input['type'])){
                $data_1 = $this->Data1Model->where("type", 1)->get();
                $data_2 = $this->Data2Model->where("type", 0)->get();
                
                // 复制一份数据
                $newdata = [];
                foreach ($data_2 as $key => $value) {
                    $newobj = [
                        "class_1" => isset($value['class_1'])?$value['class_1']:"",
                        "class_2" => isset($value['class_2'])?$value['class_2']:"",
                        "class_3" => isset($value['class_3'])?$value['class_3']:"",
                        "class_4" => isset($value['class_4'])?$value['class_4']:"",
                        "class_5" => isset($value['class_5'])?$value['class_5']:"",
                        "title" => isset($value['title'])?$value['title']:"",
                        "source" => isset($value['source'])?$value['source']:"",
                        "time" => isset($value['time'])? $value['time']: null,
                        "pv" => isset($value['pv'])?$value['pv']:"",
                        "uv" => isset($value['uv'])?$value['uv']:"",
                        "pv_gr" => 0,
                        "uv_gr" => 0,
                        "zb_pv" => isset($value['zb_pv'])?$value['zb_pv']:"",
                        "zb_uv" => isset($value['zb_uv'])?$value['zb_uv']:"",
                        "likes" => isset($value['likes'])?$value['likes']:"",
                        "zb_yy" => isset($value['zb_yy'])?$value['zb_yy']:"",
                        "type" => 12
                    ];
                    $newdata[] = $newobj;
                }
                $res = $this->Data2Model->insert($newdata);
                // 重新获取数据
                $data_2 = $this->Data2Model->where("type", 12)->get();
                
                foreach ($data_1 as $key => $value) {
                    $name_1 = preg_replace("/\s+/", "", $value['title']);
                    $newObj = null;
                    $istrue_1 = false;
                    if((strpos($name_1, "学术中心-真实案例-") !== false || strpos($name_1, "学术中心-基础必修-") !== false || strpos($name_1, "学术中心-个性美学-") !== false || strpos($name_1, "学术中心-进阶培训-") !== false) && strpos($name_1, "-Loaded") !== false){
                        $istrue_1 = true;
                    }
                    foreach ($data_2 as $key_a => $value_a) {
                        $name_2 = preg_replace("/\s+/", "", $value_a['title']);
                        if($value['leixing'] == "news"){
                            if($value_a['class_2'] == "前沿资讯" && $name_1 == $name_2){
                                $newObj = $value_a;
                            }
                        }else if($value['leixing'] == "pifu"){
                            if($value_a['class_1'] == "皮肤科应用" && $name_1 == $name_2){
                                $newObj = $value_a;
                            }
                        }else{
                            if($name_1 == $name_2){
                                $newObj = $value_a;
                            }
                        }

                        if($istrue_1){
                            preg_match_all('/\d+/', $name_1, $matches1);
                            $string_1 = array_reverse($matches1[0]);
                            preg_match_all('/\d+/', $name_2, $matches2);
                            $string_2 = array_reverse($matches2[0]);
                            if(count($string_1) && count($string_2) && strpos($name_2, "Loaded") !== false){
                                if($string_1[0] == $string_2[0]){
                                    $newObj = $value_a;
                                }
                            }
                        }
                    }
                    // 查询是否匹配到历史数据
                    if($newObj){
                        $newSave = [
                            "pv" => intval($newObj['pv']) + intval($value['nums']),
                            "pv_gr" => $value['nums'],
                            "uv" => intval($newObj['uv']) + intval($value['stop_time']),
                            "uv_gr" => $value['stop_time'],
                        ];
                        // 修改原始数据title
                        $this->Data1Model->where("id",$value['id'])->update(["title" => $newObj['title']]);
                        $this->Data2Model->where("id",$newObj['id'])->update($newSave);
                    }
                    
                }

                $data_1 = $this->Data1Model->where("type", 1)->get();

                // 补齐本月未出现的数据
                foreach ($data_1 as $key_a => $value_a) {
                    $name_2 = preg_replace("/\s+/", "", $value_a['title']);
                    $is_true = false;
                    foreach ($data_2 as $key => $value) {
                        $name_1 = preg_replace("/\s+/", "", $value['title']);
                        if($value_a['leixing'] == "news"){
                            if($value['class_2'] == "前沿资讯" && $name_1 == $name_2){
                                $is_true = true;
                            }
                        }else if($value_a['leixing'] == "pifu"){
                            if($value['class_1'] == "皮肤科应用" && $name_1 == $name_2){
                                $is_true = true;
                            }
                        }else{
                            if($name_1 == $name_2){
                                $is_true = true;
                            }
                        }
                    }
                    if(!$is_true){
                        $newObj = [
                            "title" => $value_a['title'],
                            "pv" => $value_a['nums'],
                            "pv_gr" => $value_a['nums'],
                            "uv" => $value_a['stop_time'],
                            "uv_gr" => $value_a['stop_time'],
                            "type" => 12
                        ];
                        $this->Data2Model->insertGetId($newObj);
                    }
                }
                // 补齐点赞数据
                $data_1 = $this->Data2Model->where("type", 12)->get();
                $data_2 = $this->ZansModel->get();
                foreach ($data_1 as $key => $value) {
                    if($value['class_1'] == "学习中心" && $value['class_2'] == "真实案例"){
                        preg_match_all('/\d+/', $value['title'], $matches1);
                        $string_id = $matches1[0];
                        if(count($string_id)){
                            foreach ($data_2 as $keyA => $valueA) {
                                if($string_id[0] == $valueA['zan_id']){
                                    $new_save = [
                                        "likes" => intval($valueA['zans_count']),
                                        "like_gr" => intval($valueA['zans_count']) - intval($value['likes'])
                                    ];
                                    $this->Data2Model->where("id",$value['id'])->update($new_save);
                                }
                            }
                        }
                    }
                }
            }
            
            $yisheng = $yisheng_3 = $this->Data2Model->where("type", 12)->get();
            return response()->json(['code' => 200, 'msg' => '', "data" => $yisheng]);
        } catch (\Exception $e) {
            return response()->json(['code' => 302, 'msg' => $e->getMessage(), "data" => '']);
        }
    }

    /**
     * business数据整合
     */
    public function getData4(Request $request){
        try {
            $input = $request->all();
            if(!isset($input['type'])){
                // 处理business数据
                $data_1 = $this->Data1Model->where("type", 2)->get();
                $data_2 = $this->Data2Model->where("type", 1)->get();
                $newData1 = [];
                foreach ($data_2 as $key_a => $value_a) {
                    $newObj = null;
                    $newObj1 = null;
                    foreach ($data_1 as $key => $value) {
                        $name_1 = $value['title'];
                        if(strpos($name_1, "学术中心 - ") !== false){
                            $name_1 = str_replace("学术中心 - ", "business - ", $name_1);
                        }
                        if($name_1 == $value_a['title']){
                            $newObj = $value_a;
                            $newObj1 = $value;
                        }
                    }
                    // 查询是否匹配到历史数据
                    if($newObj){
                        $newObj = [
                            "class_1" => $newObj['class_1'],
                            "class_2" => $newObj['class_2'],
                            "class_3" => $newObj['class_3'],
                            "class_4" => $newObj['class_4'],
                            "class_5" => $newObj['class_5'],
                            "title" => $value_a['title'],
                            "time" => $value_a['time'],
                            "pv" => $newObj['pv'] + $newObj1['nums'],
                            "pv_gr" => $newObj1['nums'],
                            "uv" => $newObj['uv'] + $newObj1['stop_time'],
                            "uv_gr" => $newObj1['stop_time'],
                            "class_1_p" => $newObj['class_1_p'],
                            "class_2_p" => $newObj['class_2_p'],
                            "class_3_p" => $newObj['class_3_p'],
                            "class_4_p" => $newObj['class_4_p'],
                            "class_5_p" => $newObj['class_5_p'],
                            "type" => 11
                        ];
                    }else{
                        $newObj = [
                            "class_1" => $value_a['class_1'],
                            "class_2" => $value_a['class_2'],
                            "class_3" => $value_a['class_3'],
                            "class_4" => $value_a['class_4'],
                            "class_5" => $value_a['class_5'],
                            "title" => $value_a['title'],
                            "time" => $value_a['time'],
                            "pv" => $value_a['pv'],
                            "uv" => $value_a['uv'],
                            "type" => 11
                        ];
                    }
                    $this->Data2Model->insertGetId($newObj);
                }
                
                // 补齐本月未出现的数据
                foreach ($data_1 as $key_a => $value_a) {
                    $name_1 = $value_a['title'];
                    $is_true = false;
                    foreach ($data_2 as $key => $value) {
                        if(strpos($name_1, "学术中心 - ") !== false){
                            $name_1 = str_replace("学术中心 - ", "business - ", $name_1);
                        }
                        if($name_1 == $value['title']){
                            $is_true = true;
                        }
                    }
                    if(!$is_true){
                        $newObj = [
                            "title" => $value_a['title'],
                            "pv" => $value_a['nums'],
                            "pv_gr" => $value_a['nums'],
                            "uv" => $value_a['stop_time'],
                            "uv_gr" => $value_a['stop_time'],
                            "type" => 11
                        ];
                        $this->Data2Model->insertGetId($newObj);
                    }
                }
            }
            $Business = $this->Data2Model->where("type", 11)->get();
            return response()->json(['code' => 200, 'msg' => '', "data" => $Business]);
        } catch (\Exception $e) {
            return response()->json(['code' => 302, 'msg' => $e->getMessage(), "data" => '']);
        }
    }

    /**
     * 获取菜单数据
     */
    public function getMenus(Request $request){
        try {
            $resurt = $this->MenusModel->get();
            $new_arr = [];
            foreach ($resurt as $key => $value) {
                $new_arr[] = $value['name'];
            }
            $users = $this->UsersModel->where("type", 1)->get();
            $users2 = $this->UsersModel->where("type", 2)->get();
            return response()->json(['code' => 200, 'msg' => '', "data" => ["menus" => $new_arr, "users" => $users, "user2" => $users2] ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 302, 'msg' => $e->getMessage(), "data" => '']);
        }
    }

    public function ExcelTimeCHange($excelTime){
        // $excelTime = 43942.4578703704;
        $timestamp = ($excelTime - 25569) * 86400;
        $formatDate = date("Y/m/d", $timestamp);
        return $formatDate;
    }


    /**
     * 获取点赞数据
     */
    public function addZans(Request $request){
        try {
            // 先获取token
            $responseT = $this->curl_request($this->TokenHost ."?type=strapi", null);
            $this->StrapiToken = $responseT['token'];
            if(isset($responseT['token'])){
                $url = "https://admin.restylane.com.cn/api/platforms/strapi/content-manager/collection-types/application::tutorial-course.tutorial-course?page=1&pageSize=9999&_sort=name:ASC";
                $header = [
                    "Authorization: Bearer ".$responseT['token'],
                ];
                $response = $this->curl_request($url, null, $header);
                if(isset($response['results'])){
                    $data = $response['results'];
                    # 批量插入数据
                    foreach ($data as $key => $value) {
                        $new_data = [
                            "name" => $value['name'],
                            "introduction" => $value['introduction'],
                            "zans_count" => $value['zans_count'],
                            "zan_id" => $value['id'],
                            "doctor" => $value['author']['givenName'],
                            "info" => json_encode($value, JSON_UNESCAPED_UNICODE),
                        ];
                        $res = $this->ZansModel->insert($new_data);
                    }
                }
            }else{
                return response()->json(['code' => 302, 'msg' => "token获取失败", "data" => '']);
            }
            return response()->json(['code' => 200, 'msg' => '', "data" => "获取成功"]);
        } catch (\Exception $e) {
            return response()->json(['code' => 302, 'msg' => $e->getMessage(), "data" => '']);
        }
    }

    /**
     * 获取点赞数据
     */
    public function getUser(Request $request){
        try {
            $jobArray = [
                'CEO',
                'COO',
                '营销',
                '运营',
                '咨询顾问',
            ];
            // 先获取token
            $responseT = $this->curl_request($this->TokenHost ."?type=omni", null);
            $this->OmiToken = $responseT['token'];
            $countAll = 10000;
            if(isset($responseT['token'])){
                $array = [];
                // 循环遍历获取用户数据
                for ($i = 0; $i < 100; $i++) {
                    $offset = $i * 200;
                    if($offset > $countAll){
                        break;
                    }
                    $limit = 200;
                    $url = 'https://admin.restylane.com.cn/api/internal/users?filter={"status":"APPROVED"}&page={"offset":'.$offset.',"limit":'.$limit.'}';
                    $header = [
                        "Authorization: Bearer ".$responseT['token'],
                    ];
                    $response = $this->curl_request($url, null, $header);
                    // return response()->json(['code' => 200, 'msg' => '', "data" => $response['meta']['total']]);
                    if(isset($response['data'])){
                        $countAll = $response['meta']['total'];
                        $array = array_merge($array, $response['data']);
                    }
                }
                // type值1是Business 2是医生
                $newdata = [];
                foreach ($array as $key => $value) {
                    $type = 0;
                    $jobType = '';
                    if(isset($value['phoneHome']) && $value['phoneHome']){
                        $jobType = $value['phoneHome'];
                    }else{
                        $jobType = $value['jobTitle'];
                    }
                    if(in_array($jobType, $jobArray)){
                        $type = 1;
                    }
                    if($jobType == "医生"){
                        $type = 2;
                    }
                    $newobj = [
                        "name" => $value['givenName'],
                        "phone" => $value['phone'],
                        "job" => $value['jobTitle'],
                        "type" => $type,
                    ];
                    if($type){
                        $newdata[] = $newobj;
                    }
                }
                $this->UsersModel->insert($newdata);
                return response()->json(['code' => 200, 'msg' => '', "data" => $array]);
            }else{
                return response()->json(['code' => 302, 'msg' => "token获取失败", "data" => '']);
            }
            
        } catch (\Exception $e) {
            return response()->json(['code' => 302, 'msg' => $e->getMessage(), "data" => '']);
        }
    }

    /**
     * 清除所有数据
     */
    public function clearData(Request $request){
        try {
            $this->Data1Model->truncate();
            $this->Data2Model->truncate();
            $this->ZansModel->truncate();
            $this->UsersModel->truncate();
            return response()->json(['code' => 200, 'msg' => '', "data" => []]);
        } catch (\Exception $e) {
            return response()->json(['code' => 302, 'msg' => $e->getMessage(), "data" => '']);
        }
    }


    /**
     * curl_post
     */
	public function curl_request($url,$data = null, $header = null){
        $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        if(!empty($header)){
            $headerArray = array_merge($headerArray,$header);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        $output = curl_exec($curl);
        curl_close($curl);

        $res = json_decode($output,true);

        return $res;
    }

















































    
    /**
     * 获取产品数据
     */
    public function getProduct(Request $request){
        $input = $request->all();
        $page = isset($input['page'])?$input['page']:1;
        $page_size = isset($input['page_size'])?$input['page_size']:50;
        $type = isset($input['type'])?$input['type']:1;
        $type_a = isset($input['type_a'])?$input['type_a']:1;
        $order_field = isset($input['order_field'])?$input['order_field']:0;

        $listarray = array();
        $listarray['first_row'] = ($page-1)*$page_size;
        $listarray['limit_row'] = $page_size;
        if($order_field){
            $listarray['order_field'] = $order_field;
            $listarray['order_type'] = "DESC";
        }

        $keys = array();
        $keys['type'] = "=$type";
        $res = $this->ProductModel->getDataByCondition($keys,'all',$listarray);
        $keys = array();
        $keys['type'] = "=$type_a";
        $trends = $this->ProductModel->getDataByCondition($keys,'all',$listarray);
        return response()->json(['code' => 200, 'msg' => '', "data" => $res, 'trends' => $trends]);
    }

    /**
     * 修改大屏数据
     */
    public function saveProduct(Request $request){
        $input = $request->all();
        $dataList = isset($input['dataList']) ? $input['dataList'] : [];
        foreach ($dataList as $key => $value) {
            $num = $value['num'];
            $name = $value['name'];
            $percentage = $value['percentage'];
            $dataSave = [
                "name" => $name,
                "num" => $num,
                "percentage" => $percentage
            ];
            $this->ProductModel->where("id", $value['id'])->update($dataSave);
        }
        return response()->json(['code' => 200, 'msg' => '', "data" => $input]);
    }

    /**
     * 获取活动列表
     */
    public function activitylist(Request $request){
        $input = $request->all();
        $page = isset($input['page'])?$input['page']:1;
        $page_size = isset($input['page_size'])?$input['page_size']:10;

        $listarray = array();
        $listarray['first_row'] = ($page-1)*$page_size;
        $listarray['limit_row'] = $page_size;

        $keys=array();
        $res = $this->ModelLdActivity->getDataByCondition($keys,'all',$listarray);
        foreach ($res as $key => $value) {
            $res[$key]['changci'] = explode("_",$value['content'])[1];
            # 开奖连接
            $string = "/activity1/operation.html?activity=".base64_encode($value['id']);
            $res[$key]['url'] = env("HOST_URL").$string;
        }
        $count = $this->ModelLdActivity->getDataByCondition($keys,'cnt',array());
        // 奖品信息
        $prizes = $this->LdPrizeModel->where("type", 1)->get();
        $resurt = ['data' => $res,'count' => $count, 'prizes' => $prizes];
        return response()->json(['code' => 200, 'msg' => '', "data" => $resurt]);
    }
    
    /**
     * 修改抽奖场次
     */
    public function editNext(Request $request){ 
        $input = $request->all();
        $activity = isset($input['activity'])?base64_decode($input['activity']):'';
        $keys = array();
        $listarray = array();
        if($activity==''){
            return response()->json(['code' => 301, 'msg' => '参数错误', "data" => '']);
        }
        $resurt = $this->ModelLdActivity->where("id", $activity)->first();
        if($resurt){
            if($resurt['is_stop'] == 0){
                return response()->json(['code' => 301, 'msg' => '活动还未结束，不能修改', "data" => '']);
            }
            if($resurt['ac_type'] == 1){
                return response()->json(['code' => 301, 'msg' => '没有更多抽奖环节', "data" => '']);
            }

            $data_n = array();
            $data_n['is_stop'] = 0;
            $data_n['ac_type'] = $resurt['ac_type'] - 1;
            $this->ModelLdActivity->where("id", $resurt['id'])->update($data_n);
            // 添加黑名单
            $addBlack = $this->LdActivityMember->where("is_win",1)->get()->toArray();
            foreach ($addBlack as $key => $value) {
                $newData = [
                    'mobile' => $value['member_id'],
                ];
                $this->LdBlackList->insertGetId($newData);
            }
            $this->LdActivityMember->where("is_win",1)->delete();
        }
        return response()->json(['code' => 200, 'msg' => '', "data" => $resurt]);
    }
    /**
     * 获取活动详情
     */
    public function activityinfo(Request $request){
        $input = $request->all();
        $page = isset($input['page'])?$input['page']:1;
        $page_size = isset($input['page_size'])?$input['page_size']:10;
        $id = isset($input['id'])?$input['id']:'';

        $listarray = array();
        $listarray['first_row'] = ($page-1)*$page_size;
        $listarray['limit_row'] = $page_size;
        $listarray['select_field'] = "ld_activity_member.*,ld_prize.name,member.mobile,member.nickname";
        $listarray['order_field'] = "is_win";

        $keys=array();
        if($id!=''){
            $keys['activity_id']="=$id";
        }else{
            return response()->json(['code' => 301, 'msg' => '参数错误', "data" => '']);
        }
        $res = $this->LdActivityMember->getDataByCondition($keys,'all',$listarray);
        $count = $this->LdActivityMember->getDataByCondition($keys,'cnt',array());
        $resurt = ['data' => $res,'count' => $count];
        return response()->json(['code' => 200, 'msg' => '', "data" => $resurt]);
    }

    /**
     * 黑名单列表
     */
    public function backlist(Request $request){
        $input = $request->all();
        $mobile = isset($input['mobile'])?$input['mobile']:'';
        $page = isset($input['page'])?$input['page']:1;
        $page_size = isset($input['page_size'])?$input['page_size']:10;

        $listarray = array();
        $listarray['first_row'] = ($page-1)*$page_size;
        $listarray['limit_row'] = $page_size;

        $keys=array();
        if($mobile!=''){
            $keys['mobile']=" like '%$mobile%'";
        }
        $res = $this->LdBlackList->getDataByCondition($keys,'all',$listarray);
        $count = $this->LdBlackList->getDataByCondition($keys,'cnt',array());
        $resurt = ['data' => $res,'count' => $count];
        return response()->json(['code' => 200, 'msg' => '', "data" => $resurt]);
    }

    /**
     * 删除黑名单数据
     */
    public function delbacklist(Request $request){
        try {
            $input = $request->all();
            $id = isset($input['id'])?$input['id']:"";
            $this->LdBlackList->where(array("id" => $id))->delete();
            return response()->json(['code' => 200, 'msg' => '', "data" => '']);
        } catch (\Exception $e) {
            return response()->json(['code' => 302, 'msg' => $e->getMessage(), "data" => '']);
        }
    }

    /**
     * 获取活动列表
     */
    public function city_list(Request $request){
        $input = $request->all();
        $page = isset($input['page'])?$input['page']:1;
        $page_size = isset($input['page_size'])?$input['page_size']:50;
        $type = isset($input['type'])?$input['type']:0;
        $order_field = isset($input['order_field'])?$input['order_field']:0;

        $listarray = array();
        $listarray['first_row'] = ($page-1)*$page_size;
        $listarray['limit_row'] = $page_size;
        if($order_field){
            $listarray['order_field'] = $order_field;
            $listarray['order_type'] = "DESC";
        }
        $keys['type'] = $type ? $type : 0;

        $keys = array();
        $res = $this->LdCityModel->getDataByCondition($keys,'all',$listarray);
        $newArr = [];
        foreach ($res as $key => $value) {
            $newValue = sprintf("%.2f", $value['completion_rate']);
            $res[$key]['completion_rate'] = $newValue;
            $res[$key]['reality_s'] = round(intval($value['reality'])/10000, 1);
            if($value['type'] == $type){
                $newArr[] = $value;
            }
        }
        return response()->json(['code' => 200, 'msg' => '', "data" => $newArr]);
    }

    /**
     * 修改大屏数据
     */
    public function city_save(Request $request){
        $input = $request->all();
        $dataList = isset($input['dataList']) ? $input['dataList'] : [];
        foreach ($dataList as $key => $value) {
            $reality = $value['reality'];
            $target = $value['target'];
            $dataSave = [
                "reality" => $reality,
                "target" => $target,
                "completion_rate" => $target ? round(($reality/$target)*100, 2) : 0
            ];
            $this->LdCityModel->where("id", $value['id'])->update($dataSave);
        }
        return response()->json(['code' => 200, 'msg' => '', "data" => $input]);
    }

    /**
     * 获取客户列表
     */
    public function guest_list(Request $request){
        $input = $request->all();
        $mobile = isset($input['mobile'])?$input['mobile']:'';
        $room = isset($input['room'])?$input['room']:'';
        $page = isset($input['page'])?$input['page']:1;
        $page_size = isset($input['page_size'])?$input['page_size']:10;

        $listarray = array();
        $listarray['first_row'] = ($page-1)*$page_size;
        $listarray['limit_row'] = $page_size;

        $keys = array();
        if($mobile!=''){
            $keys['keywords'] = $mobile;
        }
        if($room){
            $keys['room'] = "=$room";
        }
        $res = $this->GuestModel->getDataByCondition($keys,'all', $listarray);
        $count = $this->GuestModel->getDataByCondition($keys,'cnt', array());
        $resurt = ['data' => $res,'count' => $count];
        return response()->json(['code' => 200, 'msg' => '', "data" => $resurt]);
    }

    /**
     * 分配住宿人员
     */
    public function designated_room(){
        return response()->json(['code' => 200, 'msg' => '', "data" => '']);
    }

    /**
     * 修改大屏数据(新)
     */
    public function saveProductNew(Request $request){
        $input = $request->all();
        $dataList = isset($input['dataList']) ? $input['dataList'] : [];
        $order = isset($input['orders']) ? $input['orders'] : 0;
        foreach ($dataList as $key => $value) {
            $name = $value['name'];
            $percentage = $value['percentage'];
            $percentage1 = $value['percentage1'];
            $dataSave = [
                "name" => $name,
                "num" => $order,
                "percentage" => $percentage,
                "percentage1" => $percentage1,
            ];
            $this->ProductModel->where("id", $value['id'])->update($dataSave);
        }
        return response()->json(['code' => 200, 'msg' => '', "data" => $input]);
    }

    /**
     * 修改大屏数据(新)
     */
    public function addProduct(Request $request){
        $input = $request->all();
        $dataSave = isset($input) ? $input : null;
        if($dataSave){
            $this->ProductModel->insertGetId($dataSave);
            return response()->json(['code' => 200, 'msg' => '', "data" => $input]);
        }
        return response()->json(['code' => 401, 'msg' => '', "data" => '']);
    }
    /**
     * 修改大屏数据(新)
     */
    public function deleteProduct(Request $request){
        $input = $request->all();
        $id = isset($input['id']) ? $input['id'] : 0;
        if($id){
            $this->ProductModel->where("id",$id)->delete();
        }
        return response()->json(['code' => 200, 'msg' => '', "data" => $input]);
    }

    /**
     * 上传文件
     */
    public function updatepic(Request $request){
        $input = $request->all();
        $ids = isset($input['id']) ? $input['id'] : 0;
        try{
    		if(!$_FILES){
    			return array('res' => '' , 'msg' => '未找到上传文件');
    		}
	    	$file = $_FILES['file'];
            $filepath = $file['tmp_name'];
            //上传文件
            if($_SERVER['HOST_URL']=='https://rinnai.eoiyun.com'){
                $path = '/mnt/KangWeiDe/upload/';
                $pathTrue = 'https://rinnai.eoiyun.com/upload/';
            }else{
                $path = 'D:\www\rinnai_award_h5\web\public\upload\\';
                $pathTrue = 'http://localhost/upload/';
            }
            $rand_name = explode(".", $file['name']);
            $true_name = date("YmdHis",time()).'_'.rand(1000,9999).'.'.$rand_name[1];
            $upload_file_path = $path . $true_name;
            if (!@move_uploaded_file($filepath, $upload_file_path)){
                return array('res' => '' , 'msg' => '文件保存失败');
            }
            $dataSave = [
                "image" => $pathTrue . $true_name
            ];
            $this->ProductModel->where("id", $ids)->update($dataSave);
            return response()->json(['code' => 200, 'msg' => '', "data" => $upload_file_path]);
    	} catch (\Exception $e) {
            return response()->json(['code' => 200, 'msg' => $e->getMessage(), "data" => '']);
        }
    }

    /**
     * 重置抽奖
     */
    public function resettingDraw(Request $request){ 
        $input = $request->all();
        $activity = isset($input['activity'])?base64_decode($input['activity']):'';
        $keys = array();
        $listarray = array();
        if($activity==''){
            return response()->json(['code' => 301, 'msg' => '参数错误', "data" => '']);
        }
        // 删除参与用户数据
        $this->LdActivityMember->where("activity_id", $activity)->delete();
        // 删除黑名单
        $this->LdBlackList->truncate();
        // 修改活动状态
        $saveData = [
            "is_stop" => 0,
            "ac_type" => 3
        ];
        $this->ModelLdActivity->where("id", $activity)->update($saveData);
        return response()->json(['code' => 200, 'msg' => '', "data" => '']);
    }

    /**
     * 修改奖品数据
     */
    public function saveParze(Request $request){
        $input = $request->all();
        $dataList = isset($input['dataList']) ? $input['dataList'] : [];
        foreach ($dataList as $key => $value) {
            $name = $value['name'];
            $grade = $value['grade'];
            $dataSave = [
                "name" => $name,
                "grade" => $grade,
            ];
            $this->LdPrizeModel->where("id", $value['id'])->update($dataSave);
        }
        return response()->json(['code' => 200, 'msg' => '', "data" => $input]);
    }

    /**
     * 新增城市数据
     */
    public function addCity(Request $request){
        $input = $request->all();
        $dataSave = isset($input) ? $input : null;
        if($dataSave){
            $this->LdCityModel->insertGetId($dataSave);
            return response()->json(['code' => 200, 'msg' => '', "data" => $input]);
        }
        return response()->json(['code' => 401, 'msg' => '', "data" => '']);
    }

    /**
     * 删除城市数据
     */
    public function deletePrize(Request $request){
        $input = $request->all();
        $id = isset($input['id']) ? $input['id'] : 0;
        if($id){
            $this->LdCityModel->where("id",$id)->delete();
        }
        return response()->json(['code' => 200, 'msg' => '', "data" => $input]);
    }
}
