<?php
/**
 * Created by PhpStorm.
 * User: BJ
 * Date: 2018/9/13
 * Time: 下午12:56
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function content(){
        $content = ArticleContent::find($this->id);
        return $content->content;
    }
}
