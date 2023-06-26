<?php

namespace Efectn\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Lunar\Models\Collection;
use Lunar\Models\Language;

class MenuItems extends Model
{

    protected $table = null;

    protected $fillable = ['label', 'link', 'parent_id', 'sort', 'class', 'menu_id', 'depth', 'role_id','object_type'];

    public function __construct(array $attributes = [])
    {
        //parent::construct( $attributes );
        $this->table = config('menu.table_prefix') . config('menu.table_name_items');
    }

    public function getSons($id)
    {
        return $this->where("parent_id", $id)->get();
    }
    public function getAll($id)
    {
        return $this->where("menu_id", $id)->orderBy("sort", "asc")->get();
    }

    public static function getNextSortRoot($menu)
    {
        return self::where('menu_id', $menu)->max('sort') + 1;
    }

    public function parentMenu()
    {
        return $this->belongsTo('Efectn\Menu\Models\Menus', 'menu_id');
    }

    public function child()
    {
        return $this->hasMany('Efectn\Menu\Models\MenuItems', 'parent_id')->orderBy('sort', 'ASC');
    }
    public function objectName()
    {
        if($this->object_type=='collection'){
            $collection = Collection::where(['id'=>$this->object_id])->first();
            
            if($collection){
                $local = \App::getLocale();
                $attribute = json_decode($collection->attribute_data);
                return $attribute->name->$local;
            }
        }
        return 'NA';
    }
    public function objectLink()
    {
        if($this->object_type=='collection'){
            $collection = Collection::where(['id'=>$this->object_id])->first();
            if($collection){
                $local = \App::getLocale();
                $language = Language::where(['code'=>$local])->first();
             
               if($language){
                    $urls = json_decode($collection->urls);
                 
                    $url = false;
                    foreach($urls as $_url){
                        if($_url->language_id==$language->id){
                            $url = $_url;
                        }
                    }
                    if($url){
                        return '/collections/'.$url->slug;
                    }
                } 

            }
        }
        return 'NA';
    }
}
