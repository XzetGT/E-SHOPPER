<?php

/**
 * Description of menuWidget
 *
 * @author xXx
 */

namespace app\components;

use yii\base\Widget; // наш виджет обязательно должен наследовать это класс
use app\models\Category;
use Yii;

class MenuWidget extends Widget{
    
    public $tpl;    // т.е. template
    public $data;   // здесь будут хранится массив данных из таблицы категории
    public $tree;   // будет хранится результат работы функции, котор будет из обычного массива строить массив дерево
    public $menuHtml;   // будет хранится уже готовый html код, в зависимости от того шаблона котор сохранится в свойстве $tpl


    public function init() { //в виджете могут быть два метода init и run(чаще используетя)
        parent::init();
        if($this->tpl === null){ //если $tpl пустой
            $this->tpl = 'menu'; //то присваиваем ему поумолчанию 'menu'
        }
        $this->tpl .= '.php';
    }

    public function run() { //run чаще используется для вывода виджета
        
        //get cashe. cache->get() - берем данные из кэша
        $menu = Yii::$app->cache->get('menu'); //здесь пытаемся прочитать данные из кэша,
        if($menu) return $menu; //если они прочитаны тогда мы возвращаем наше меню
        
        //если нет, тогда мы формируем его(меню)
        $this->data = Category::find()->indexBy('id')->asArray()->all(); //indexBy('id') - указывает по какокму полю индифицировать ключи массивов
        $this->tree = $this->getTree();
        $this->menuHtml = $this->getMenuHtml($this->tree);
        
        //set cashe. cache->set() - записываем данные в файл кэша
        $menu = Yii::$app->cache->set('menu', $this->menuHtml, 60); //и записываем в кэш, и при след обращении данные будут считаны уже с кэша 
        return $this->menuHtml;
    }
    
    protected function getTree(){ // наш метод, котор проходится в цикле по необходимому нам массиву и строит дерево
        $tree = [];
        foreach ($this->data as $id=>&$node){
            if (!$node['parent_id']){
                $tree[$id] = &$node;
            } else {
                $this->data[$node['parent_id']]['childs'][$node['id']] = &$node;
            }
        }
        return $tree;
    }
    
    protected function getMenuHtml($tree){
        $str = '';
        foreach ($tree as $category){
            $str .= $this->catToTemplate($category); //принимает параметром переданный элемент и помещает его в шаблон
        }
        return $str;
    }
    
    protected function catToTemplate($category){
        ob_start(); //Эта функция включает буферизацию вывода. Если буферизация вывода активна, вывод скрипта не высылается (кроме заголовков), а сохраняется во внутреннем буфере.
        include __DIR__ . '/menu_tpl/' . $this->tpl; //и подключает его вот здесь
        return ob_get_clean(); //ob_get_clean - Получает содержимое текущего буфера и затем удаляет текущий буфер.
    }

}