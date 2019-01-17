<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
//登录
Route::get('/login','Admin\AuthController@getLogin');
Route::post('/login','Admin\AuthController@postLogin');
Route::get('/logout', 'Admin\AuthController@logout');

//图片不避免APP
Route::get('/event_attachment','Admin\EventController@attachment');

//人口数据导入
Route::get('/population_import','Admin\DataController@population_import');
Route::post('/excel_upload','Admin\DataController@excel_upload');
Route::get('/lowest_ensure','Admin\DataController@lowest_ensure');

//根据地址获得经纬度
Route::get('/geo_point','Admin\GisController@address_point');

//web页面访问的路由,需要csrf验证
Route::group(['middleware' => ['web','permission']], function () {
    Route::get('/','Admin\PageController@page_index');
    //Route::get('/index','Admin\PageController@page_index');
    Route::get('/index','Admin\PageController@index');




    //首页
    Route::get('/test','Admin\PageController@test');

    Route::get('/index_test','Admin\PageController@index_test');


    //业务中心
    Route::get('/event_index','Admin\EventController@index');
    Route::get('/event_pre_determine','Admin\EventController@pre_determine');
    Route::get('/event_pre_determine_list','Admin\EventController@event_list');


    Route::get('/event_timeline','Admin\EventController@timeline');
    Route::get('/event_export','Admin\EventController@export');



    Route::get('/event_detail','Admin\EventController@detail');
    Route::post('/event_process','Admin\EventController@process');
    Route::post('/event_delete','Admin\EventController@delete');

    Route::get('/event_already_determine','Admin\EventController@already_determine');
    Route::get('/event_already_determine_list','Admin\EventController@event_list');
    Route::post('/event_already_determine_back','Admin\EventController@already_determine_back');
    Route::post('/event_already_determine_close','Admin\EventController@already_determine_close');

    Route::get('/event_auto_determine','Admin\EventController@auto_determine');
    Route::get('/event_auto_determine_list','Admin\EventController@auto_determine_list');
    Route::get('/event_auto_determine_view','Admin\EventController@auto_determine_view');
    Route::post('/event_auto_determine_save','Admin\EventController@auto_determine_save');
    Route::post('/event_auto_determine_delete','Admin\EventController@auto_determine_delete');
    Route::post('/event_auto_determine_org','Admin\EventController@auto_determine_org');
    Route::post('/event_auto_determine_get_child','Admin\EventController@auto_determine_get_child');


    Route::get('/event_recycle_bin','Admin\EventController@recycle_bin');
    Route::get('/event_recycle_bin_list','Admin\EventController@event_list');
    Route::post('/event_recycle_bin_recorvery','Admin\EventController@recycle_bin_recorvery');
    Route::post('/event_recycle_bin_delete','Admin\EventController@recycle_bin_delete');

    Route::get('/event_feedback_determine','Admin\EventController@feedback_determine');
    Route::get('/event_feedback_determine_list','Admin\EventController@event_list');
    Route::post('/event_feedback_determine_back','Admin\EventController@feedback_determine_back');
//    Route::post('/event_feedback_determine_reply','Admin\EventController@feedback_determine_reply');

    Route::get('/event_query','Admin\EventController@query');
    Route::get('/event_query_list','Admin\EventController@query_list');

    Route::get('/event_map','Admin\EventController@map');
    Route::get('/event_map_data','Admin\EventController@map_data');

    Route::get('/event_union','Admin\EventController@union');
    Route::get('/event_union_supervise','Admin\EventController@union_supervise');//联合执法


    //GIS中心
    Route::get('/gps_user_location_map','Admin\GisController@user_location_map');
    Route::get('/gps_user_location_map_data','Admin\GisController@user_location_data');


    Route::get('/gps_index','Admin\GisController@index');
    Route::get('/gps_event_map','Admin\GisController@event_map');
//    Route::get('/gps_event_map_huanbao','Admin\GisController@event_map');
//    Route::get('/gps_event_map_chengguan','Admin\GisController@event_map');
    Route::get('/gps_event_map_data','Admin\GisController@map_data');

    Route::get('/gps_location_map','Admin\GisController@location_map');
//    Route::get('/gps_location_map_huanbao','Admin\GisController@location_map');
//    Route::get('/gps_location_map_chengguan','Admin\GisController@location_map');
    Route::get('/gps_location_map_data','Admin\GisController@location_data');
    //Route::get('/gps_location_user_detail','Admin\GisController@user_detail');


    Route::get('/gps_grid_map','Admin\GisController@grid_map');
    Route::get('/gps_grid_map_query','Admin\GisController@grid_map_query');
    Route::get('/gps_grid_map_org_query','Admin\GisController@grid_org_query');
    Route::get('/gps_grid_map_population_query','Admin\GisController@grid_population_query');
    Route::get('/gps_grid_map_event_query','Admin\GisController@grid_event_query');

    //Route::get('/gps_grid_dev','Admin\GisController@grid_dev');//网格工具
    //Route::get('/gps_geo_json','Admin\GisController@grid_geo_json');
    Route::get('/gps_grid_map_sub_geo_json','Admin\GisController@grid_sub_geo_json');

    //网格管理
    Route::get('/gps_grid_manage','Admin\GisController@grid_manage');//所有网格
    Route::get('/gps_grid_manage_data','Admin\GisController@grid_list_data');//获取所有网格数据
    Route::get('/gps_grid_manage_page','Admin\GisController@grid_manage_page');//网格添加或修改页面
    Route::post('/gps_grid_manage_parent','Admin\GisController@grid_parent');//获得父级网格
    Route::post('/gps_grid_manage_set','Admin\GisController@grid_set');//网格添加或修改
    Route::post('/gps_grid_manage_del','Admin\GisController@del_grid');//删除网格
    Route::get('/gps_grid_manage_map','Admin\GisController@map');//网格地图
    Route::post('/gps_grid_manage_parent_info','Admin\GisController@parent_info');//获取父级网格下的划分信息

    //人员管理
    Route::get('/gps_population','Admin\GisController@population');//人员管理
    Route::get('/gps_population_data','Admin\GisController@population_data');//获得人员数据
    Route::get('/gps_population_set','Admin\GisController@population_set'); //人员信息管理
    Route::post('/gps_population_info_set','Admin\GisController@population_info_set'); //人员信息设置
    Route::post('/gps_population_del','Admin\GisController@population_del'); //人员信息删除


    //职员管理
    Route::get('/gps_employee','Admin\GisController@employee');//职员管理
    Route::get('/gps_employee_data','Admin\GisController@employee_data');//获得职员数据
    Route::get('/gps_employee_set','Admin\GisController@employee_set'); //职员信息管理
    Route::post('/gps_employee_get_info','Admin\GisController@employee_get_info');         //职员信息管理获得职员信息
    Route::post('/gps_employee_info_set','Admin\GisController@employee_info_set'); //职员信息设置
    Route::post('/gps_employee_del','Admin\GisController@employee_del'); //职员信息删除



    //社区管理
    Route::get('/gps_grid_org','Admin\GisController@grid_org');//机构管理
    Route::get('/gps_grid_org_data','Admin\GisController@grid_org_data');//获得机构管理数据
    Route::get('/gps_grid_org_set','Admin\GisController@grid_org_set'); //机构信息管理
    Route::post('/gps_grid_org_info_set','Admin\GisController@grid_org_info_set'); //机构信息设置
    Route::post('/gps_grid_org_del','Admin\GisController@grid_org_del'); //机构信息删除




    //视频中心
    Route::get('/video_index','Admin\VideoController@index');
    Route::get('/video_index_map','Admin\VideoController@index_map');
    Route::get('/video_index_map_data','Admin\VideoController@index_map_data');
    Route::get('/video_center','Admin\VideoController@video_center');

    Route::get('/video_car_map','Admin\VideoController@car_map');
    Route::get('/video_car_map_data','Admin\VideoController@car_map_data');
    Route::get('/video_car_history','Admin\VideoController@car_history');
    Route::get('/video_car_history_data','Admin\VideoController@car_history_data');
    Route::get('/video_car_replay','Admin\VideoController@car_replay');
    Route::get('/video_car_replay_data','Admin\VideoController@car_replay_data');
    Route::get('/video_car_manage','Admin\VideoController@car_manage');
    Route::get('/video_car_manage_list','Admin\VideoController@car_manage_list');
    Route::get('/video_car_manage_view','Admin\VideoController@car_manage_view');
    Route::post('/video_car_manage_save','Admin\VideoController@car_manage_save');
    Route::post('/video_car_manage_delete','Admin\VideoController@car_manage_delete');


    //决策中心
    Route::get('/policy_index','Admin\PolicyController@index');
    Route::get('/policy_stat','Admin\PolicyController@stat');                           //总体统计
    Route::get('/policy_stat_event_statistics','Admin\PolicyController@stat_event_statistics');   //业务统计图表--第一行图表
    Route::get('/policy_stat_event_completion_rate','Admin\PolicyController@stat_event_completion_rate');   //业务办结率 -- 第二行图表
    Route::get('/policy_stat_map_data','Admin\PolicyController@map_data');
    Route::get('/policy_stat_statistics_analysis','Admin\PolicyController@statistics_analysis');

    //数据中心
    Route::get('/data_index','Admin\DataController@index');   //首页
    Route::get('/data_population','Admin\DataController@resident_population');         //默认常住人口
    Route::get('/data_population_resident_list','Admin\DataController@resident_population_list');    //列表
    Route::get('/data_population_detail','Admin\DataController@population_detail');         //详细信息页面
    Route::get('/data_population_add','Admin\DataController@population_add');                   //新增页
    Route::post('/data_population_save','Admin\DataController@population_save');                //保存
    Route::get('/data_population_isHouseholder','Admin\DataController@isHouseholder');          //验证是否为户主
    Route::get('/data_population_edit','Admin\DataController@population_edit');                   //编辑
    Route::post('/data_population_delete','Admin\DataController@population_delete');            //删除
    Route::post('/data_population_householder_edit','Admin\DataController@editHouseHolderInfo');            //编辑是否为户主信息
    Route::post('/data_population_export_excel','Admin\DataController@exportExcel');        //导出Excel
    Route::post('/data_population_import_excel','Admin\DataController@import_excel');        //人口导入
    Route::get('/data_population_template_download',function(){
        return response()->download(
            realpath(base_path('public/admin/template')).'/person_template.xls',
            '人口信息模板.xls'
        );
    });        //人口信息模板



    //标签管理
    Route::get('/data_label','Admin\DataController@label_manage');         //标签管理
    Route::get('/data_label_list','Admin\DataController@label_list');      //获取标签数据
    Route::get('/data_label_info','Admin\DataController@label_info');      //获取标签信息【应用信息及基本信息】
    Route::get('/data_label_use_info','Admin\DataController@label_use_info');      //获取标签信息【应用信息及基本信息】
    Route::post('/data_label_del','Admin\DataController@label_del');      //标签删除
    Route::post('/data_label_save','Admin\DataController@label_save');      //标签修改
    Route::post('/data_label_use_del','Admin\DataController@label_use_del');      //标签使用删除
//    Route::post('/data_label_add','Admin\DataController@label_add');      //标签添加

    //计生服务
    Route::get('/data_bear','Admin\DataController@planned_parenthood');         //计生服务
    Route::get('/data_bear_list','Admin\DataController@get_bear_list');         //计生服务信息
    Route::post('/data_bear_get_grid','Admin\DataController@bear_get_grid');         //获取网格信息
    Route::get('/data_bear_add','Admin\DataController@bear_add');         //计生服务添加信息
    Route::post('/data_bear_add_save','Admin\DataController@bear_add_save');         //计生服务添加或修改信息
    Route::post('/data_bear_get_women','Admin\DataController@bear_get_women');         //计生服务添加或修改信息


    //数据中心 --> 组户信息管理
    Route::get('/data_family_list','Admin\DataController@family_list');             //组户信息列表
        //数据中心 --> 特殊人群
    Route::get('/data_special_list','Admin\DataController@special_list');           //特殊人群信息列表
        //数据中心 --> 重点人群
    Route::get('/data_emphases_list','Admin\DataController@emphases_list');         //重点人群信息列表
        //数据中心 --> 流动人口
    Route::get('/data_fluid_list','Admin\DataController@fluid_list');               //流动人群信息列表
    Route::get('/data_fluid_wx','Admin\DataController@fluid_wx');                   //流动人口微信平台
      //数据中心 --> 回收站
    Route::get('/data_population_recycle_bin','Admin\DataController@recycle_bin');             //回收站列表
    Route::post('/data_population_recycle_bin_action','Admin\DataController@recycle_bin_action');   //回收站 的还原、彻底删除操作
        //获取下级网格信息
    Route::get('/data_population_next_grid_list','Admin\DataController@getNextGridList');      //新增、编辑页面用



    //日常办公
    Route::get('/job_event_report','Admin\JobController@event_report');             //事件上报页面
    Route::post('/job_event_report_save','Admin\JobController@event_report_save');  //事件上报保存

    Route::get('/job_pre_event','Admin\JobController@pre_event');                   //待办事件页面
    Route::get('/job_pre_event_list','Admin\JobController@pre_event_list');         //待办事件列表
    Route::get('/job_pre_event_view','Admin\JobController@pre_event_view');             //待办事件详细页面
    Route::post('/job_pre_event_get','Admin\JobController@pre_event_get');              //领取事件
    Route::get('/job_pre_event_back','Admin\JobController@pre_event_back');            //退回事件

    Route::get('/job_get_event','Admin\JobController@get_event');                   //已领事件页面
    Route::get('/job_get_event_list','Admin\JobController@get_event_list');         //已领事件列表
    Route::get('/job_get_event_down','Admin\JobController@event_down');                 //事件办结---即提交到业务中心审核
    Route::get('/job_get_event_back_to_pre','Admin\JobController@event_back_to_pre');    //把事件退回到待办事件

    Route::get('/job_already_event','Admin\JobController@already_event');           //已办事件
    Route::get('/job_already_event_list','Admin\JobController@already_event_list'); //已办事件列表

    Route::get('/job_event_stat','Admin\JobController@event_stat');
    Route::get('/job_event_stat_data','Admin\JobController@event_stat_data');    //事件统计数据
    Route::get('/job_event_stat_list','Admin\JobController@event_stat_list');


    //绩效考核
    Route::get('/exam_setting','Admin\ExamController@exam_setting');//考核设置
    Route::get('/exam_setting_data','Admin\ExamController@exam_data');//获取考核数据
    Route::post('/exam_setting_del','Admin\ExamController@exam_del');//考核项删除
    Route::get('/exam_setting_set','Admin\ExamController@exam_set');//考核项设置
    Route::post('/exam_setting_handle','Admin\ExamController@exam_set_handle');//考核项删除

    //系统管理
        //-->组织机构-->机构管理
    Route::get('/sys_org_manage','Admin\SystemController@org_manage');              //列表页
    Route::get('/sys_org_manage_list','Admin\SystemController@org_manage_list');    //ajax拉取列表信息
    Route::get('/sys_org_manage_edit','Admin\SystemController@org_manage_edit');
    //Route::get('/sys_org_manage_detail','Admin\SystemController@org_manage_detail');    //详细信息
    Route::get('/sys_org_manage_add','Admin\SystemController@org_manage_add');
    Route::post('/sys_org_manage_save','Admin\SystemController@org_manage_save');   //保存
    Route::get('/sys_org_manage_delete','Admin\SystemController@org_manage_delete');   //删除
        //-->组织机构-->用户管理
    Route::get('/sys_user_manage','Admin\SystemController@user_manage');
    Route::get('/sys_user_manage_list','Admin\SystemController@user_manage_list');
    Route::get('/sys_user_manage_view','Admin\SystemController@user_manage_view');          //添加 查看 编辑
    Route::post('/sys_user_manage_save','Admin\SystemController@user_manage_save');
    Route::get('/sys_user_manage_delete','Admin\SystemController@user_manage_delete');
    Route::post('/sys_user_manage_reset','Admin\SystemController@user_manage_reset');       //重置密码
    Route::get('/sys_user_manage_status','Admin\SystemController@user_manage_status');
    Route::get('/sys_user_login_name_unique','Admin\SystemController@is_login_name_unique');//用来检测登录名是否唯一

        //-->运行管理-->权限管理
    Route::get('/sys_auth_manage','Admin\SystemController@auth_manage');                    //权限管理首页
    Route::get('/sys_auth_manage_assign','Admin\SystemController@auth_manage_assign');      //权限分配页面
    Route::get('/sys_auth_manage_authlist','Admin\SystemController@auth_manage_authlist');  //获取权限列表
    Route::post('/sys_auth_manage_saveauth','Admin\SystemController@auth_manage_saveauth');     //保存权限

    Route::get('/sys_auth_manage_rolelist','Admin\SystemController@auth_manage_rolelist');    //查询角色--权限管理首页--ajax获取角色列表
    Route::get('/sys_auth_manage_editrole','Admin\SystemController@auth_manage_editrole');    //编辑角色
    Route::get('/sys_auth_manage_addrole','Admin\SystemController@auth_manage_addrole');    //增加角色
    Route::get('/sys_auth_manage_delrole','Admin\SystemController@auth_manage_delrole');    //删除角色
    Route::post('/sys_auth_manage_saverole','Admin\SystemController@auth_manage_saverole'); //保存角色信息

    //通用数据加载
    Route::get('/common_category','Admin\CommonController@category');//获取事件类型
    Route::get('/common_org','Admin\CommonController@org');//获取组织机构
    Route::get('/common_car','Admin\CommonController@car');//获取执法车
    Route::post('/attachment_upload','Admin\CommonController@attachment_upload');//获取执法车


    //地址选择的三级联动菜单 路由
    Route::get('/get_province','Admin\DistrictController@getProvince'); //获取 “省”
    Route::get('/get_city','Admin\DistrictController@getCity');         //获取 “市”
    Route::get('/get_district','Admin\DistrictController@getDistrict'); //获取 “区”
    Route::get('/get_default_data','Admin\DistrictController@getDefaultData');   //默认获取河南省新乡市牧野区



    //党建中心
    Route::get('/party_index','Admin\PartyController@index'); //首页
    Route::get('/party_place','Admin\PartyController@place'); //场所管理列表
    Route::get('/party_place_data','Admin\PartyController@place_data'); //获得场所信息
    Route::get('/party_place_set','Admin\PartyController@place_set'); //场所管理
    Route::post('/party_place_info_set','Admin\PartyController@place_info_set'); //场所信息设置
    Route::post('/party_place_name_check','Admin\PartyController@place_name_check'); //场所信息验证
    Route::post('/party_place_del','Admin\PartyController@place_del'); //场所信息删除


    Route::get('/party_money','Admin\PartyController@money'); //经费管理列表
    Route::get('/party_money_data','Admin\PartyController@money_data'); //获得经费信息
    Route::get('/party_money_set','Admin\PartyController@money_set'); //经费管理
    Route::post('/party_money_info_set','Admin\PartyController@money_info_set'); //经费信息设置
    Route::post('/party_money_del','Admin\PartyController@money_del'); //经费信息删除

    Route::get('/party_feedback','Admin\PartyController@feedback'); //矛盾反馈列表
    Route::get('/party_feedback_data','Admin\PartyController@feedback_data'); //获得反馈信息
    Route::get('/party_feedback_set','Admin\PartyController@feedback_set'); //反馈管理
    Route::post('/party_feedback_info_set','Admin\PartyController@feedback_info_set'); //反馈信息设置
    Route::post('/party_feedback_del','Admin\PartyController@feedback_del'); //反馈信息删除

    Route::get('/party_peace','Admin\PartyController@peace'); //平安建设列表
    Route::get('/party_peace_data','Admin\PartyController@peace_data'); //获得平安建设信息
    Route::get('/party_peace_set','Admin\PartyController@peace_set'); //平安建设管理
    Route::post('/party_peace_info_set','Admin\PartyController@peace_info_set'); //平安建设设置
    Route::post('/party_peace_del','Admin\PartyController@peace_del'); //平安建设删除

    Route::get('/party_twins','Admin\PartyController@twins'); //双城建设列表
    Route::get('/party_twins_data','Admin\PartyController@twins_data'); //获得双城建设信息
    Route::get('/party_twins_set','Admin\PartyController@twins_set'); //双城建设管理
    Route::post('/party_twins_info_set','Admin\PartyController@twins_info_set'); //双城建设设置
    Route::post('/party_twins_del','Admin\PartyController@twins_del'); //双城建设删除

    //鹰眼系统
    Route::get('/hawk_eye_video','Admin\HawkeyeController@hawkeye_video');

    //扶贫中心
    Route::get('/poor','Admin\HelppoorController@people_info_list');
    Route::get('/help_poor_index','Admin\HelppoorController@index');
    Route::get('/help_back','Admin\HelppoorController@help_back');
    Route::get('/help_back_data','Admin\HelppoorController@help_back_data'); //获得反馈信息
    Route::get('/help_back_set','Admin\HelppoorController@help_back_set'); //反馈管理
    Route::post('/help_back_info_set','Admin\HelppoorController@help_back_info_set'); //反馈信息设置
    Route::post('/help_back_del','Admin\HelppoorController@help_back_del'); //反馈信息删除

    //信访人员
    Route::get('/visit_people_list','Admin\VisitController@visit_people_list');
});


//api路由,可以定制访问规则:例如频率限制等
Route::group(['middleware' => ['api']], function () {


});