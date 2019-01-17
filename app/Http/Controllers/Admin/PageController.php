<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Gis;
use App\Models\Point;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    //新加首页
    public function index(){
        return view('admin.index.new_index');
    }
    //
    public function page_index(){
        //return view('admin.index')原测试页面
        return view('admin.index.index')
            ->with('title','首页')
            ->with('homeNav','首页')
            ->with('homeLink','/')
            ->with('subNav','')
            ->with('activeNav','首页')
            ->with('menus',getMenus());
    }

    public function index_test(){
        //模拟数据
        //$event = new \stdClass();
        //$event->title = '下雨后下水道堵塞，引起街面积水过多，影像交通';
        //$event->province = '河南省';
        //$event->city = '新乡市';
        //$event->district = '牧野区';
        //$event->address = '东风路新中大道';
        $event = \DB::table('event')->where('id',1)->first();
//        $phpWord = new \PhpOffice\PhpWord\PhpWord();
//        $document = new \PhpOffice\PhpWord\TemplateProcessor(public_path().'/admin/template/event_template.docx');

        $document = new \PHPWord_Template(public_path().'/admin/template/event_template.docx');

        $document->setValue('title',$event->title);
        $document->setValue('address',$event->district.' '.$event->address);
        $document->setValue('name',$event->reporter_name);
        $document->setValue('phone',$event->reporter_phone);
        $limit_end_time = $event->limit_end_time?date('Y-m-d H:i:s',($event->limit_end_time/1000)):'';
        $document->setValue('limit_end_time',$limit_end_time);
        $source = $event->source==0?'指挥中心':($event->source==1?'网格员':'微信用户');
        $document->setValue('source',$source);
        $document->setValue('desc',$event->desc);
        $document->setValue('additional',$event->additional_info);
        $document->setValue('suggest',$event->suggest_info);

//        $section = $phpWord->addSection();
//        $section->addImage(storage_path().'/app/public/1-1466821489105.jpg');

        //$image = $phpWord->addMemoryImage("http://localhost:8000/event_attachment?path=1-1466821489105.jpg&w=200&h=200");
        //dd($content);
        //$document->setValue('img',$content);

        $img1 = storage_path().'/app/public/1-1466821489105.jpg';
        $aImgs = [$img1];
        $document->replaceStrToImg('img', $aImgs);


        $document->save(storage_path().'/app/test.docx');

        //保存文件
//        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
//        $objWriter->save(storage_path().'/app/helloWorld.docx');    //路径和文件名

        exit;

        $this->event_output_word($event);
    }

    /**
     * 输入事件信息--数据库字段为键的对象
     * 输出word
     */
    protected function event_output_word($event){
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        //默认设置
        $phpWord->setDefaultFontName('黑体');
        $phpWord->setDefaultFontSize(12);
        //添加表格
        $section = $phpWord->addSection();
        $table   = $section->addTable(['cellMarginTop'=>20,'borderColor'=>'99999','borderSize'=>1,]);
        $table->addRow(150);
        $table->addCell(2000)->addText('事件标题');
        $table->addCell(7000)->addText($event->title);

        $table->addRow(150);
        $table->addCell()->addText('事件地址');
        $table->addCell()->addText($event->district.' '.$event->address);

        $event->fileName = '自定义文件名';
        //保存文件
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path().'/app/'.$event->fileName.'.docx');    //路径和文件名

        return responseToJson(0,'success');
    }

    /**
     * 输入事件信息--数据库字段为键的对象
     * 输出word
     */
    private function event_output_word_origin($event){
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
            . 'The important thing is not to stop questioning." '
            . '(Albert Einstein)'
        );
        $section->addText(
            '"Great achievement is usually born of great sacrifice, '
            . 'and is never the result of selfishness." '
            . '(Napoleon Hill)',
            array('name' => 'Tahoma', 'size' => 10)
        );
// Adding Text element with font customized using named font style...
        $fontStyleName = 'oneUserDefinedStyle';
        $phpWord->addFontStyle(
            $fontStyleName,
            array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
        );
        $section->addText(
            '"The greatest accomplishment is not in never falling, '
            . 'but in rising again after you fall." '
            . '(Vince Lombardi)',
            $fontStyleName
        );

        //设置字体信息
        // Adding Text element with font customized using explicitly created font style object...
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Tahoma');
        $fontStyle->setSize(13);
        $myTextElement = $section->addText(
            '"Believe you can and you\'re halfway there." (Theodor Roosevelt)'
        );
        $myTextElement->setFontStyle($fontStyle);


        //保存文件
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path().'/app/helloWorld.docx');    //路径和文件名

        return responseToJson(0,'success');
    }
    public function test(){




//        $text = '内蒙古自治区乌兰察布市四子王旗';
//        $result = Gis::split_location($text);
//        //$result = Gis::geo_address(new Point(112.79583, 41.933795));
//        dd($result);
//
//        //测试多边形
//        $point_list = [
//            new Point(116.339965,39.85609),
//            new Point(116.293972,39.92518),
//            new Point(116.296271,39.97297),
//            new Point(116.376759,39.990662),
//            new Point(116.475645,39.962353),
//            new Point(116.489443,39.908357),
//            new Point(116.444599,39.862293),
//            new Point(116.341115,39.851658),
//
//        ];
//
//        $inside_point = new Point(116.381359,39.926065);
//        $outside_point = new Point(116.531987,39.936688);
//        $online_point = new Point(116.454948,39.875584);
//
//        $is_true = Gis::is_inside($inside_point,$point_list);
//        $is_false = Gis::is_inside($outside_point,$point_list);
//        $online = Gis::is_inside($online_point,$point_list);
//
//        dd(['true'=>$is_true,'false'=>$is_false,'online'=>$online]);

//        return view('admin.test1')
//            ->with('title','首页')
//            ->with('homeNav','首页')
//            ->with('homeLink','/test')
//            ->with('subNav','')
//            ->with('activeNav','首页')
//            ->with('menus',getMenus());


    }


}
