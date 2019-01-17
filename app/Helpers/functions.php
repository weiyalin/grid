<?php

function description_trim($description, $limit = 500, $end = '...')
{
    $description = strip_tags(str_limit($description, $limit, $end));
    $description = str_replace("  ", "", $description);
    $description = str_replace("\n", "", $description);

    return $description;
}

//第一个是原串,第二个是 部份串
function start_with($str, $needle)
{

    return strpos($str, $needle) === 0;

}

//第一个是原串,第二个是 部份串
function end_with($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

function current_user_id(){
    return session('user')->id;
}
function current_user_name(){
    return session('user')->name;
}
function current_user_phone(){
    return session('user')->phone;
}
function current_user_org_id(){
    return session('user')->org_id;
}
function current_user_org_name(){
    return session('user')->org_name;
}


function is_permission($uri){
    $permissions = session('permissions');
    //dd($permissions);
    $allow = false;
    if($permissions){
        foreach ($permissions as $node) {
            if($node->path == '/' && $uri != '/'){
                continue;
            }
            $pos = strpos($uri, $node->path);
            if ($pos !== false) {
                $allow = true;
                //dd($pos);
                break;
            }
        }
    }

    return $allow;
}


function profile($key)
{
    //TODO 修改策略
    if ($key == 'name') {
        return session('user')->name;
    } else if ($key == 'avatar') {
        $avatar = session('admin.avatar');
        if (empty($avatar)) {
            return '/admin/img/avatar.jpg';
        }
        return $avatar;
    }
    else if($key == 'mobile'){
        return session('user')->phone;
    }
    return '';
}

function millisecond()
{
    return ceil(microtime(true) * 1000);
}

/**
 * 页面json 输出
 *
 * @param int $code
 * @param $msg
 * @param $paras
 */
function responseToJson($code = 0, $msg = '', $paras = null)
{
    $res["code"] = $code;
    $res["msg"] = $msg;
    if (!empty($paras)) {
        $res["result"] = $paras;
    }
    return response()->json($res);
}

function create_guid()
{
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = substr($charid, 0, 8) . $hyphen
        . substr($charid, 8, 4) . $hyphen
        . substr($charid, 12, 4) . $hyphen
        . substr($charid, 16, 4) . $hyphen
        . substr($charid, 20, 12);
    return $uuid;
}

/**
 *
 * 获取当前用户可访问的菜单列表
 *
 * @return array 菜单导航列表
 */
function getMenus(){
    if(\Illuminate\Support\Facades\Auth::check()){//todo:测试
        //$permissions = session('permissions');
        $permissions = \App\Models\User::get_menus();
        //dd($permissions);
        $uri = request()->getRequestUri();
        //dd($uri);
        //根据权限组合成菜单
        $menus = collect();
        $test = [];
        foreach($permissions as $node){
            $arr = explode("?",$node->path);
            $path = $arr[0];
            if(str_contains($uri,$path)){
                $node->active = true;

                if($node->depth ==2){
                    $mainNumber = substr($node->code,0,3);

                    foreach($permissions as $n){
                        if($n->code == $mainNumber){
                            $n->open='open';
                        }
                    }
                }
                else if($node->depth == 3){
                    $mainNumber = substr($node->code,0,3);
                    $subNumber = substr($node->code,0,6);

                    foreach($permissions as $n){
                        if($n->code == $mainNumber){
                            $n->open='open';
                            //$node->open='open';
                            //$test[] = $n;
                        }
                        if($n->code == $subNumber){
                            $n->open='open';
                        }
                    }
                }
            }
            else {
                $node->active = false;
                //$node->open='open';//todo:默认打开所有
            }
            if($node->depth == 1){
                $node->submenus = collect();
                $menus->push($node);
            }
            else if($node->depth == 2){
                $mainNumber = substr($node->code,0,3);
                foreach($menus as $m){
                    if($m->code == $mainNumber){
                        $node->submenus = collect();
//                        dd($node);
                        $m->submenus->push($node);
                    }
                }
            }
            else if($node->depth == 3){
                $mainNumber = substr($node->code,0,6);
                //dd($menus);
                foreach($menus as $m){
                    if($m->submenus && count($m->submenus)>0){
                        foreach($m->submenus as $submenu){
                            if($submenu->code == $mainNumber){
                                $submenu->submenus->push($node);
                            }
                        }
                    }

                }
            }
        }
//        dd($menus);
        return $menus;
    }
    else {
        //return redirect()->guest('/login');
        return collect();
    }
}


/**
 * 获取当前学期
 * @return array
 */
function get_current_term()
{
    $year = date('Y');
    $month = date('m');
    $term = 0;
    if ($month < 8) {
        $term = 1;
        $year = $year - 1;//学年修正;
    }
    //TODO 格式化学期
    $startYear = $year;
    $endYear = $year + 1;
    $termFormat = ($term == 1 ? '二' : '一');
    $title = "${startYear}-${endYear}学年第${termFormat}学期";
    return ['term' => $term, 'year' => $year, 'title' => $title];
}


function curl_post($pUrl, $data = '')
{
    if (is_array($data)) {
        $post_data = http_build_query($data);
    } else {
        $post_data = $data;
    }
    $ch = curl_init($pUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $html = curl_exec($ch);
    $html = trim($html);

    curl_close($ch);

    if ($html == "") {
        return false;
    }

    return $html;
}

function curl($pUrl, $pCookies = false, $pCookieSuffix = "")
{
    $ch = curl_init($pUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.");

    if ($pCookies) {
        $cookieFile = "cookie" . $pCookieSuffix . ".txt";
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }

    $html = curl_exec($ch);
    $html = trim($html);

    curl_close($ch);

    if ($html == "") {
        return false;
    }

    return $html;
}

function convertUrlQuery($query)
{
    $queryParts = explode('&', $query);
    $params = array();
    foreach ($queryParts as $param)
    {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }

    return $params;
}


function convert_pic($file_name,$w,$h){
    $full_path = storage_path().'/app/public/'.$file_name;

    $thumb_path = storage_path().'/app/public/'.'thumb_'.$w.'_'.$h.'_'.$file_name;
    if(file_exists($thumb_path)){
        $full_path = $thumb_path;
    }
    else {
        $extension = pathinfo($full_path, PATHINFO_EXTENSION);
        $thumb = \PhpThumbFactory::create($full_path);
        $thumb->adaptiveResize($w, $h);
        $thumb->save($thumb_path, $extension);

        $full_path = $thumb_path;
    }
    return $full_path;
}