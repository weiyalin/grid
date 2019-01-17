<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\User;

class SystemController extends Controller
{
    /**
     * 组织机构管理页面
     */
    public function org_manage(){
        return view('admin.system.org_manage')
            ->with('title','机构管理')
            ->with('homeNav','组织机构')
            ->with('homeLink','/sys_org_mange')
            ->with('subNav','')
            ->with('activeNav','机构管理')
            ->with('menus',getMenus());
    }

    /**
     * 组织机构管理列表
     */
    public function org_manage_list(Request $request){
        $sEcho = intval(trim($request->input("sEcho")));
        $iDisplayStart  = intval(trim($request->input("iDisplayStart")));   //起始索引
        $iDisplayLength = intval(trim($request->input("iDisplayLength")));  //显示行数
        $sSearch    = trim($request->input("sSearch"));                     //全局搜索字段
        $status     = intval($request->input('status'));                    //机构状态  前端传过来的是0

        //$sSort      = trim($request->input('sSortDir_0'));  //排序方向 asc 、desc
        //$iColumns   = intval(trim($request->input('iColumns')));    //获取的列数
        //$mDataProp_0  = trim($request->input('mDataProp_0'));     //获取的第一个字段；其他字段 _1  _2...

        //DB::connection()->enableQueryLog(); // 开启查询日志

        $query = DB::table('organization');
        $query->where('status',$status);    //0：正常 1：已删除
        $count = $query->count();           //数据总数
        $query->skip($iDisplayStart)->take($iDisplayLength);    //分页

        /*for($i=0; $i<$iColumns; $i++){  //组装需要查询的字段
            $query->addSelect(trim($request->input('mDataProp_'.$i)));
        }*/

        if($sSearch != '') {
            $query->where('name','like','%'.mb_strtolower($sSearch).'%');
        }
        $query->select('id','name','province','city','district','address','contact','type'); //要获取的字段
        $list = $query->orderBy('id','asc')->get();  //获取数据

//        $getOrgType = [1=>'办事处',2=>'职能部门'];   //获取gor_type;与数据库中的对应
        $getOrgType = [1=>'镇办',2=>'职能部门',3=>'社区（村）'];   //获取gor_type;与数据库中的对应
        //var_dump(DB::getQueryLog());
        if($list){
            //数据中的 “省份+城市+县区+街道” 组成 detailAddress为键的"详细地址"
            //并把机构类型（int)转化成对应的名称
            foreach($list as $v){
                $type = $getOrgType[$v->type];         //把int转化成对应的名称
                $detailAddress = $v->province.' '.$v->city.' '.$v->district.' '.$v->address;    //把地址拼装成详细地址
                $tmpArr[] = ['id'=>$v->id,'name'=>$v->name,'detailAddress'=>$detailAddress,'contact'=>$v->contact,'type'=>$type];
            }

            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] =$count;
            $json["iTotalDisplayRecords"] = $json["iTotalRecords"];
            $json["aaData"] = $tmpArr;
            echo json_encode($json);
        } else{
            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] = 0;
            $json["iTotalDisplayRecords"] = 0;
            $json["aaData"] = "";
            echo json_encode($json);
        }
    }

    /**
     * 添加组织机构
     */
    public function org_manage_add(){
        //设置添加机构时默认的 省 市  区（河南省新乡市牧野区）
        //default_district = ['province'=>1,'city'=>85,'district'=>90];
        $default_district = ['province'=>'河南省','city'=>'新乡市','district'=>'牧野区'];
        //获取机构id和名称，供前台选择上级部门
        $org_list = $this->getOrgList();
        return view('admin.system.org_manage_add')
            ->with('title','新增机构')
            ->with('homeNav','机构管理')
            ->with('homeLink','/sys_org_manage')
            ->with('subNav','')
            ->with('activeNav','新增机构')
            ->with('menus',getMenus())
            ->with('default_district',$default_district)
            ->with('org_list',$org_list);
    }

    /**
     * 获取组织机构列表：只获取id、name字段，供...操作时选择上级部门、user所属部门时 使用
     */
    private function getOrgList(){
        $org_list = DB::table('organization')->where('status',0)->select('id','name')->get();
        return $org_list;
    }
    /**
     * 编辑页面
     */
    public function org_manage_edit(Request $request){
        $id = intval(trim($request->input('id')));
        $org_info = DB::table('organization')->where('id',$id)->where('status',0)->first();
        //获取机构id和名称，供前台选择上级部门
        $org_list = $this->getOrgList();

//        $getOrgType = [1=>'办事处',2=>'职能部门'];   //获取gor_type;与数据库中的对应
        $getOrgType = [1=>'镇办',2=>'职能部门',3=>'社区（村）']; //获取gor_type;与数据库中的对应
        $org_info->typeName = $getOrgType[$org_info->type]; //找到type对应的typename
        if($org_info->parent_id == 0){
            $org_info->parentName = '无上级部门';
        }else {
            $org_info->parentName = DB::table('organization')->where('id', $org_info->parent_id)->value('name'); //获取parentId对应的name
        }
        return view('admin.system.org_manage_add')  //和添加组织机构使用同一个页面
            ->with('title','编辑机构信息')
            ->with('homeNav','机构管理')
            ->with('homeLink','/sys_org_manage')
            ->with('subNav','')
            ->with('activeNav','编辑')
            ->with('menus',getMenus())
            ->with('org_info',$org_info)
            ->with('org_list',$org_list);
    }
    /**
     * 组织机构保存添加
     */
    public function org_manage_save(Request $request){
        $this->validate($request,[
            'name'      => 'required|max:45',
            'type'      => 'required',
            'province'  => 'required',
            'address'   => 'required',
            'city'      => 'required',
            'address'   => 'required|max:100',
            'contact'   => 'required|max:100',
            'type'      => 'required',
            'parent_id' => 'required',
            //'latitude'  => 'required',
            //'longitude'  => 'required',
            //'grid_1'     => 'required',
            //'grid_2'     => 'required',
            //'grid_3'     => 'required',
            //'grid_4'     => 'required',
        ]);

        $data = $request->all();
        if(isset($data['id'])){   //存在id则为更新
            if($data['id'] == $data['parent_id']){
                return responseToJson(1,'上级部门不能是自身');   //判断上级部门不能是自己本身
            }
            $id = $data['id'];
            unset($data['id']);
            $data['updator'] = $request->user()->id;
            $data['create_time'] = millisecond();
            $res = DB::table('organization')->where('id',$id)->update($data);
        }else {
            $data['creator'] = $request->user()->id;
            $data['create_time'] = millisecond();
            $res = DB::table('organization')->insert($data);
        }
        if($res){
            return responseToJson(0,'操作成功');
        }else{
            return responseToJson(1,'操作失败');
        }
    }

    /**
     * 组织机构删除
     */
    public function org_manage_delete(Request $request){
        $id = $request->input('id');
        $res = DB::table('organization')->whereIn('id',$id)->update([
            'status'=>1,
            'updator'   => $request->user()->id,        //获取用户id
            'update_time' => millisecond(),
        ]);
        if($res){
            return responseToJson(0,'删除成功');
        }else{
            return responseToJson(1,'删除失败');
        }
    }

    /**
     * 用户管理页面
     */
    public function user_manage(){
        //供高级搜索使用
        $data['org_list'] = $this->getOrgList();
        $data['role_list'] = DB::table('role')->where('status',0)->select('id','name')->get();

        return view('admin.system.user_manage')
            ->with('title','用户管理')
            ->with('homeNav','用户管理')
            ->with('homeLink','/sys_user_mange')
            ->with('subNav','')
            ->with('activeNav','用户列表')
            ->with('menus',getMenus())
            ->with('data',$data);
    }

    /**
     * 用户管理列表
     *  使用Eloquent ORM操作数据库
     */
    public function user_manage_list(Request $request){
        $sEcho = intval(trim($request->input("sEcho")));
        $iDisplayStart  = intval(trim($request->input("iDisplayStart"))); //起始索引
        $iDisplayLength = intval(trim($request->input("iDisplayLength"))); //显示行数
        $sSearch    = trim($request->input("sSearch"));     //全局搜索字段
        //$status     = intval($request->input('status'));    //角色状态

        //DB::connection()->enableQueryLog(); // 开启查询日志
        $query = DB::table('user');
        $query->whereIn('status',[0,1]);    //0：正常 1：禁用 2,删除

        if($sSearch != '') {        //名字搜索
            $query->where('name','like','%'.$sSearch.'%');
        }
        if($request->input('advanced_search')){ //高级搜索
            if($name = trim($request->input('name'))){    //姓名
                $query->where('name','like','%'.$name.'%');
            }
            if($login_name = trim($request->input('login_name'))){
                $query->where('login_name','like','%'.$login_name.'%');
            }
            if($title = trim($request->input('title'))){
                $query->where('title','like','%'.$title.'%');
            }
            if($phone = trim($request->input('phone'))){
                $query->where('phone','like','%'.$phone.'%');
            }
            if($role_id = trim($request->input('role_id'))){
                $user_ids = DB::table('user_role')->where('role_id',intval($role_id))->lists('user_id');
                $query->whereIn('id',$user_ids);
            }
            if($org_id = trim($request->input('org_id'))){
                $query->where('org_id',$org_id);
            }
            //dd($request->input('status'));
            if(trim($request->input('status')) != ''){      //有为0的可能
                $query->where('status',trim($request->input('status')));
            }
        }

        $count = $query->count();   //数据总数

        $list = $query->skip($iDisplayStart)->take($iDisplayLength)
            ->orderBy('id','desc')->get();  //获取数据

        //var_dump(DB::getQueryLog());

        //获取role和organization并组装成【id=>name】
        $orgList = DB::table('organization')->where('status',0)->select('id','name')->get();
        $roleList = DB::select('select user_role.user_id as id,role.name from role,user_role where user_role.role_id=role.id and role.status = 0');
        foreach($orgList as $v){
            $getOrgName[$v->id] = $v->name; //组装user_id对应的org_name数组
        }
        unset($orgList);
        foreach($roleList as $v){
            $getRoleName[$v->id] = $v->name;    //组装user_id对应的role_name数组
        }
        if($list){
            foreach($list as $k=>$v){
                //角色、机构均存在被删除的可能性！！所以作以下判断
                $list[$k]->orgName = isset($getOrgName[$list[$k]->org_id])?$getOrgName[$list[$k]->org_id]:'未设置或已删除';   //获取组织机构名
               $list[$k]->roleName = isset($getRoleName[$list[$k]->id])?$getRoleName[$list[$k]->id]:'未设置或已删除';      //获取角色名
                $list[$k]->status = $list[$k]->status==0?'正常':'已禁用';    //状态是启用还是禁用
            }
            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] =$count;
            $json["iTotalDisplayRecords"] = $json["iTotalRecords"];
            $json["aaData"] = $list;
            echo json_encode($json);
        } else{
            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] = 0;
            $json["iTotalDisplayRecords"] = 0;
            $json["aaData"] = "";
            echo json_encode($json);
        }
    }

    /**
     * 用户编辑添加页面
     */
    public function user_manage_view(Request $request){
        //先组织基本所需信息：角色列表、组织机构列表
        $data['org_list'] = $this->getOrgList();
        $data['role_list'] = DB::table('role')->where('status',0)->select('id','name')->get();

        $return = view('admin.system.user_manage_view')
            ->with('title','新增用户')
            ->with('homeNav','用户管理')
            ->with('homeLink','/sys_user_manage')
            ->with('subNav','')
            ->with('activeNav','新增用户')
            ->with('menus',getMenus())
            ->with('data',$data);

        $id = intval(trim($request->input('id')));
        if($id){  //若传递了id,则为查看/编辑；需取出user信息
            $user = DB::select('select user.*,user_role.role_id from user,user_role where user.id=? and user_role.user_id=? limit 1',[$id,$id]);
            $user[0]->pic = [];
            if(!empty($user[0]->photo)){
                $user[0]->pic = explode(',',$user[0]->photo);
            }
            $return->with('user',$user[0]);
//            dd($user);
        }
        return $return;
    }

    /**
     * 检测用户名是否重复
     */
    public function is_login_name_unique(Request $request){
        $login_name = trim($request->input('login_name'));
        $res = DB::select('select 1 from `user` where `login_name`=? limit 1',[$login_name]);
        if($res){       //如果为真，则表示存在，所有不是唯一:返回错误
            return responseToJson(1,'登录用户名已存在，请重新分配');
        }else{
            return responseToJson(0,'可以使用');
        }

    }
    /**
     * 用户保存添加
     */
    public function user_manage_save(Request $request){
        $this->validate($request,[
            'name'      => 'required',    //测试是一个汉字长度为一个字符
            //'sex'       => 'required',
            //'phone'     => 'required|numeric',
            //'email'     => 'required|email',
            'role_id'   => 'required|integer',
            //'title'     => 'required|max:45',
            'org_id'    => 'required|integer',
            //'password'  => 'required|between:6,20|confirmed',
        ]);
        $id = intval(trim($request->input('id')));  //根据id判断是新增？编辑？

        if(!$id) {  //新增 时需要处理的字段
            $this->validate($request, [
                'login_name'=> 'required|between:6,20|unique:user',     //新增：验证唯一性;编辑：不验证
                'password' => 'required|between:6,20'         //新增：需要编辑；编辑时：不显示此项
            ]);
            $data['salt'] = str_random(4);      //laravel自带生成随机字符串函数，参数：字符串长度
            $data['password'] = md5(md5(md5(trim($request->input('password')))).$data['salt']);
            $data['creator'] = $request->user()->id;    //创建者ID
            $data['create_time'] = millisecond();
        }else{  //编辑 时需要处理的字段
            $this->validate($request,[
                'login_name'=> 'required|between:6,20',     //新增：验证唯一性;编辑：不验证
            ]);
            $data['updator'] = $request->user()->id;
            $data['update_time'] = millisecond();
        }

        $data['name'] = trim($request->input('name')); //真实姓名
        $data['email'] = trim($request->input('email'));
        $data['login_name'] = trim($request->input('login_name'));
        $data['org_id'] = trim($request->input('org_id'));
        $data['phone'] = trim($request->input('phone'));
        $data['sex'] = trim($request->input('sex'));
        $data['title'] = trim($request->input('title'));
        $data['textarea'] = trim($request->input('textarea'));
        $data['photo'] = trim($request->input('photo'));


        if($id){    //编辑时
            $res = DB::table('user')->where('id',$id)->update($data);
            if($res !== false){
                responseToJson(0,'更新成功');
            }
        }else { //新增时
            $user_id = DB::table('user')->insertGetId($data);     //插入数据并返回user ID
            if (!$user_id) {
                return responseToJson(1, '添加失败');
            }
            $role_id = trim($request->input('role_id'));
            DB::table('user_role')->insert(['user_id' => $user_id, 'role_id' => $role_id]);  //向角色表中添加数据
            //不对最后一条sql语句的执行结果做判断，是因为担心重复插入数据：若失败，从‘编辑’页面再编辑 也可行
        }
        return responseToJson(0,'添加成功');
    }

    /**
     * 用户删除
     */
    public function user_manage_delete(Request $request){
        $ids = $request->input('id');
        $res = DB::table('user')->whereIn('id',$ids)->update(['status'=>2]);
        if($res){
            return responseToJson(0,'删除成功');
        }else{
            return responseToJson(1,'删除失败');
        }
    }

    /**
     * 用户重置密码
     */
    public function user_manage_reset(Request $request){
        $id = intval(trim($request->input('id')));
        $salt = str_random(4);
        $password = md5(md5(md5('123456')).$salt);
        $res = DB::table('user')->where('id',$id)->update([
            'password'  => $password,
            'salt'      => $salt
        ]);
        if($res !== false){
            return responseToJson(0,'重置密码成功，默认密码为<strong>123456</strong>');
        }else{
            return responseToJson(1,'重置失败，请重试');
        }
    }

    /**
     * 用户状态改变:启用/禁用
     */
    public function user_manage_status(Request $request){

        $ids = $request->input('id');

        //$res = DB::select('update user set status = case when status=0 then 1 else 0 end')
        $res = DB::select('UPDATE `user` set `status`= CASE WHEN `status`=0 THEN 1 ELSE 0 END where `id` in (?)',$ids);
        //更改成功返回空。。。
        if($res !== false){
            return responseToJson(0,'更改成功');
        }else{
            return responseToJson(1,'更改失败');
        }
    }



    /**
     * 权限管理首页
     */
    public function auth_manage(){
        return view('admin.system.auth_manage')
            ->with('title','运行管理')
            ->with('homeNav','运行管理')
            ->with('homeLink','/auth_manage')
            ->with('subNav','')
            ->with('activeNav','权限管理')
            ->with('menus',getMenus());
    }

    /**
     * 权限管理首页 -- 角色列表
     *      只取status = 0 的角色
     */
    public function auth_manage_rolelist(Request $request){
        $sEcho = intval(trim($request->input("sEcho")));
        $iDisplayStart  = intval(trim($request->input("iDisplayStart"))); //起始索引
        $iDisplayLength = intval(trim($request->input("iDisplayLength"))); //显示行数
        $sSearch    = trim($request->input("sSearch"));     //全局搜索字段
        $status     = intval($request->input('status'));    //角色状态

        //$sSort      = trim($request->input('sSortDir_0'));  //排序方向 asc 、desc
        //$iColumns   = intval(trim($request->input('iColumns')));    //获取的列数
        //$mDataProp_0  = trim($request->input('mDataProp_0'));     //获取的第一个字段；其他字段 _1  _2...

        //DB::connection()->enableQueryLog(); // 开启查询日志
        $query = DB::table('role');
        $query->where('status',$status);    //0：正常 1：已删除

        /*for($i=0; $i<$iColumns; $i++){  //组装需要查询的字段
            $query->addSelect(trim($request->input('mDataProp_'.$i)));
        }*/

        if($sSearch != '') {
            $query->where('name','like','%'.mb_strtolower($sSearch).'%');
        }

        $count = $query->count();   //数据总数

        $list = $query->skip($iDisplayStart)->take($iDisplayLength)
                ->orderBy('id','desc')->get();  //获取数据
        //var_dump(DB::getQueryLog());

        if($list){
            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] =$count;
            $json["iTotalDisplayRecords"] = $json["iTotalRecords"];
            $json["aaData"] = $list;
            echo json_encode($json);
        } else{
            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] = 0;
            $json["iTotalDisplayRecords"] = 0;
            $json["aaData"] = "";
            echo json_encode($json);
        }
    }

    /**
     * 权限列表页 -- 分配权限
     */
    public function auth_manage_assign(Request $request){
        //获取角色名称和ID
        $role['id'] = intval(trim($request->input('id')));
        $role['name'] = DB::table('role')->where('id',$role['id'])->value('name');

        $oldAuthNodeIds = DB::table('role_node')->where('role_id',$role['id'])->lists('node_id');    //只需获取此角色所有权限node_id（对应node表中的id）

        //var_dump($authList);
        return view('admin.system.auth_manage_assign')
            ->with('title','权限管理')
            ->with('homeNav','权限管理')
            ->with('homeLink','')
            ->with('subNav','')
            ->with('activeNav','事件查询')
            ->with('menus',getMenus())
            ->with('role',$role)
            ->with('oldAuthNodeIds',json_encode($oldAuthNodeIds));
    }

    /**
     * 获取前端ztree需要的数据，并组装成需要的格式
     */
    public function auth_manage_authlist(){
        //DB::connection()->enableQueryLog();
        //隔过第一个首页取出列表权限
        $nodes = DB::table('node')->where('type',0)->orderBy('code','asc')->select('id','code','name','depth')->get();   //如果去掉"首页"加上->whereNotIn('id',[1])
        $authList = []; //组装权限列表数组
        $tmpArr = []; //临时数组[code=>id]，方便子元素通过code查找父ID
        foreach($nodes as $node){
            switch($node->depth){
                case 1 :
                    array_push($authList,['id'=>$node->id,'pId'=>0,'name'=>$node->name,'open'=>true]);
                    $tmpArr[$node->code]=$node->id;
                    break;
                case 2 :
                    $pCode = substr($node->code,0,3);
                    array_push($authList,['id'=>$node->id,'pId'=>$tmpArr[$pCode],'name'=>$node->name,'open'=>true]);
                    $tmpArr[$node->code]=$node->id;
                    break;
                case 3 :
                    $pCode = substr($node->code,0,6);
                    array_push($authList,['id'=>$node->id,'pId'=>$tmpArr[$pCode],'name'=>$node->name,'open'=>true]);
                    break;
            }
        }
        unset($nodes);
        unset($tmpArr);
        //var_dump(DB::getQueryLog());
        echo json_encode($authList);
        exit;
    }

    /**
     * 保存权限
     */
    public function auth_manage_saveauth(Request $request){
        $ids = explode(',',$request->input('ids'));
        $role_id = $request->input('role_id');
        foreach($ids as $id){
            $tmpArr[] = ['role_id'=>$role_id,'node_id'=>$id];
        }
        //从数据库中取出该用户的所有权限
        $res1 = DB::table('role_node')->where('role_id',$role_id)->delete();    //先删除所有此用户权限
        if($res1 !== false) {
            $res2 = DB::table('role_node')->insert($tmpArr);
            if($res2 !== false){
                return responseToJson(0,'更新成功');
            }
        }
        return responseToJson(1,'更新失败，请重试');
    }

    /**
     * 角色编辑页面
     */
    public function auth_manage_editrole(Request $request){
        $id = intval(trim($request->input('id')));
        $role = DB::table('role')->where('id',$id)->first();                //获取此角色信息
        return view('admin.system.auth_manage_edit_role')
            ->with('title','角色编辑')
            ->with('homeNav','运行管理')
            ->with('homeLink','/auth_manage')
            ->with('subNav','')
            ->with('activeNav','权限管理')
            ->with('menus',getMenus())
            ->with('role',$role);
    }

    /**
     * 增加角色 ： 与编辑共用一个前端页面;
     */
    public function auth_manage_addrole(){
        return view('admin.system.auth_manage_edit_role')
            ->with('title','添加角色')
            ->with('homeNav','运行管理')
            ->with('homeLink','/auth_manage')
            ->with('subNav','')
            ->with('activeNav','权限管理')
            ->with('menus',getMenus());
    }

    /**
     * 保存角色信息   : 新增 + 编辑 角色
     */
    public function auth_manage_saverole(Request $request){
        $this->validate($request,[
            'role_name' => 'required|max:45',
            'role_desc' => 'required|max:225'
        ]);
        $role_id = intval(trim($request->input('role_id')));    //如果intval后的role_id为0（数据表id从1开始），表明不存在，则为新增；否则为编辑
        $role_name = trim($request->input('role_name'));
        $role_desc = trim($request->input('role_desc'));
        if($role_id){                                           //$role_id存在，则为编辑，操作updatte
            $res = DB::table('role')->where('id',$role_id)
                ->update([
                    'name'      => $role_name,
                    'desc'      => $role_desc,
                    'updator'   => $request->user()->id,        //获取用户id
                    'update_time' => millisecond(),
                ]);                                             //做改动后更新，返回1；未做改动去更新，返回0？？？
        }else{                                                  //$role_id为0，则不存在，为新增,操作insert
            $res = DB::table('role')->insert([
                'name'      => $role_name,
                'desc'      => $role_desc,
                'creator'   => $request->user()->id,
                'create_time' => millisecond(),
            ]);
        }

        if($res !== false) {
            return responseToJson(0, '保存成功');
        }
    }

    /**
     * 删除角色 ： 把status改为 1 （0：正常 1：删除）
     *      -->批量删除：传来的数据是以id为键的二维数组
     *      -->单个删除：传来以id为键的一维数组
     */
    public function auth_manage_delrole(Request $request){
        $id = $request->input('id');
        //使用whereIn，兼容批量删除
        $res = DB::table('role')->whereIn('id',$id)->update([
            'status'    => 1,
            'updator'   => $request->user()->id,        //获取用户id
            'update_time' => millisecond(),
        ]);

        if($res){
            //同时删除角色对应的权限
            DB::table('role_node')->whereIn('role_id',$id)->delete();
            return responseToJson(0,'删除成功');
        }else{
            return responseToJson(0,'删除失败');
        }

    }

}
